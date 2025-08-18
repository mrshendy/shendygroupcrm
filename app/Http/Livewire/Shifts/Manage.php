<?php

namespace App\Http\Livewire\Shifts;

use App\Models\Employee;
use App\Models\Shift;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class Manage extends Component
{
    public $shift_id;

    public $name = '';
    public $days = [];           // ['saturday','sunday',...]
    public $start_time = '';     // H:i في الواجهة
    public $end_time   = '';     // H:i في الواجهة
    public $leave_allowance = 0;

    public $employee_ids = [];   // الموظفون المختارون للشيفت
    public $allEmployees = [];   // كل الموظفين للاختيار
    public $shifts = [];         // جدول الشيفتات

    /** ترتيب/لستة الأيام المسموحة */
    private array $allowedDays = [
        'saturday','sunday','monday','tuesday','wednesday','thursday','friday'
    ];

    /** قواعد/رسائل التحقق */
    protected $rules = [
        'name'            => 'required|string|max:255',
        'days'            => 'required|array|min:1',
        'days.*'          => 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
        'start_time'      => 'required|date_format:H:i',
        'end_time'        => 'required|date_format:H:i',
        'leave_allowance' => 'required|integer|min:0',
        'employee_ids'    => 'array',
        'employee_ids.*'  => 'exists:employees,id',
    ];

    protected $messages = [
        'name.required'            => 'اسم الشيفت مطلوب',
        'days.required'            => 'اختر يومًا واحدًا على الأقل',
        'days.*.in'                => 'يوم غير صالح',
        'start_time.required'      => 'وقت البداية مطلوب',
        'start_time.date_format'   => 'صيغة وقت البداية يجب أن تكون على هيئة HH:MM مثل 08:30',
        'end_time.required'        => 'وقت النهاية مطلوب',
        'end_time.date_format'     => 'صيغة وقت النهاية يجب أن تكون على هيئة HH:MM مثل 16:30',
        'leave_allowance.required' => 'مدة الإجازة مطلوبة',
    ];

    public function mount()
    {
        $this->loadShifts();
        $this->allEmployees = Employee::select('id','full_name','shift_id')
            ->orderBy('full_name')->get();
    }

    public function loadShifts()
    {
        $this->shifts = Shift::with(['employees:id,full_name,shift_id'])
            ->orderBy('name')->get();
    }

    /** تطبيع قيمة وقت واحدة إلى H:i */
    private function normalizeTime(?string $val): ?string
    {
        if (!$val) return null;

        // 08:30:00 → 08:30
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $val)) {
            return substr($val, 0, 5);
        }

        // أي صيغة مدعومة من Carbon → H:i
        try {
            return Carbon::parse($val)->format('H:i');
        } catch (\Throwable $e) {
            return $val; // سيقع تحت التحقق لاحقًا لو كانت غير صالحة
        }
    }

    /** تطبيع وقتي البدء/الانتهاء قبل التحقق */
    private function normalizeTimes(): void
    {
        $this->start_time = $this->normalizeTime($this->start_time);
        $this->end_time   = $this->normalizeTime($this->end_time);
    }

    /** تطبيع مباشر عند تغيّر المدخلات (اختياري لكنه مفيد) */
    public function updatedStartTime($value) { $this->start_time = $this->normalizeTime($value); }
    public function updatedEndTime($value)   { $this->end_time   = $this->normalizeTime($value); }

    public function save()
    {
        // 1) طبّع الأوقات قبل التحقق لتفادي اختلافات المتصفحات/الثواني
        $this->normalizeTimes();

        // 2) تحقّق أساسي
        $this->validate();

        // 3) تحقّق منطقي: النهاية بعد البداية
        $start = Carbon::createFromFormat('H:i', $this->start_time);
        $end   = Carbon::createFromFormat('H:i', $this->end_time);
        if ($end->lessThanOrEqualTo($start)) {
            $this->addError('end_time', 'وقت الانتهاء يجب أن يكون بعد وقت البدء.');
            return;
        }

        // 4) ثبّت ترتيب/تمييز أيام العمل (Unique + Ordered)
        $uniqueDays = array_values(array_unique($this->days));
        $ordered    = array_values(array_intersect($this->allowedDays, $uniqueDays));

        DB::transaction(function () use ($ordered, $start, $end) {

            // 5) إنشاء/تحديث الشيفت
            $payload = [
                'name'            => $this->name,
                'days'            => $ordered,                    // Array → JSON (بوجود cast بالموديل)
                'start_time'      => $start->format('H:i:s'),     // خزّن H:i:s
                'end_time'        => $end->format('H:i:s'),
                'leave_allowance' => (int)$this->leave_allowance,
            ];

            if ($this->shift_id) {
                $shift = Shift::findOrFail($this->shift_id);
                $shift->update($payload);
            } else {
                $shift = Shift::create($payload);
                $this->shift_id = $shift->id;
            }

            // 6) ربط الموظفين:
            if (Schema::hasTable('employee_shift')) {
                // جدول وسيط (بدون الاعتماد على نوع العلاقة)
                DB::table('employee_shift')->where('shift_id', $shift->id)->delete();

                $ids = array_values(array_unique(array_map('intval', $this->employee_ids)));
                if (!empty($ids)) {
                    $now = now();
                    $rows = [];
                    foreach ($ids as $eid) {
                        $rows[] = ['employee_id'=>$eid, 'shift_id'=>$shift->id, 'created_at'=>$now, 'updated_at'=>$now];
                    }
                    // أدخِل على دفعات للحماية
                    foreach (array_chunk($rows, 500) as $chunk) {
                        DB::table('employee_shift')->insert($chunk);
                    }
                }
            } else {
                // عمود shift_id على employees
                $ids = array_values(array_unique(array_map('intval', $this->employee_ids)));

                if (!empty($ids)) {
                    Employee::whereIn('id', $ids)->update(['shift_id' => $shift->id]);
                }

                Employee::where('shift_id', $shift->id)
                    ->when(!empty($ids), fn($q) => $q->whereNotIn('id', $ids))
                    ->update(['shift_id' => null]);
            }
        });

        $this->resetForm();
        $this->loadShifts();
        session()->flash('success', 'تم حفظ الشيفت بنجاح');
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);

        $this->shift_id        = $shift->id;
        $this->name            = $shift->name;
        $this->days            = is_array($shift->days) ? $shift->days : [];
        // حوّل من H:i:s (المخزّن) إلى H:i لواجهة time input
        $this->start_time      = $shift->start_time ? Carbon::parse($shift->start_time)->format('H:i') : '';
        $this->end_time        = $shift->end_time   ? Carbon::parse($shift->end_time)->format('H:i')   : '';
        $this->leave_allowance = (int) $shift->leave_allowance;

        // حمّل الموظفين المرتبطين
        if (Schema::hasTable('employee_shift')) {
            $this->employee_ids = DB::table('employee_shift')
                ->where('shift_id', $shift->id)
                ->pluck('employee_id')->map(fn($v)=>(int)$v)->toArray();
        } else {
            $this->employee_ids = Employee::where('shift_id', $shift->id)->pluck('id')->toArray();
        }
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $shift = Shift::findOrFail($id);

            if (Schema::hasTable('employee_shift')) {
                DB::table('employee_shift')->where('shift_id', $shift->id)->delete();
            } else {
                Employee::where('shift_id', $shift->id)->update(['shift_id' => null]);
            }

            $shift->delete();
        });

        $this->loadShifts();
        session()->flash('success', 'تم حذف الشيفت بنجاح');
    }

    public function resetForm()
    {
        $this->shift_id        = null;
        $this->name            = '';
        $this->days            = [];
        $this->start_time      = '';
        $this->end_time        = '';
        $this->leave_allowance = 0;
        $this->employee_ids    = [];
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.shifts.manage');
    }
}
