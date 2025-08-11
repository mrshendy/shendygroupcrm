<?php

namespace App\Http\Controllers\Shendy;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContractsController extends Controller
{
    public function index()
    {
        // لو هتعرض Livewire صفحة تحتوي @livewire('contracts.index')
        return view('contracts.index');
        // أو لو هتجيب البيانات مباشرة:
        // $contracts = Contract::with(['client','project','offer','items','payments'])->latest()->paginate(15);
        // return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        return view('contracts.create');
    }

    public function store(Request $request)
    {
        // validate
        $validTypes = array_keys(Contract::TYPES ?? []);
        $data = $request->validate([
            'client_id'   => 'required|exists:clients,id',
            'project_id'  => 'nullable|exists:projects,id',
            'offer_id'    => 'nullable|exists:offers,id',
            'type'        => ['required', Rule::in($validTypes ?: ['software','maintenance','marketing','data_entry','call_center','supply_install'])],
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'amount'      => 'required|numeric|min:0',
            'include_tax' => 'boolean',
            'status'      => 'nullable|in:draft,active,suspended,completed,cancelled',
            'contract_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:8192',
        ]);

        // create
        $contract = Contract::create([
            'client_id'   => $data['client_id'],
            'project_id'  => $data['project_id'] ?? null,
            'offer_id'    => $data['offer_id'] ?? null,
            'type'        => $data['type'],
            'start_date'  => $data['start_date'] ?? null,
            'end_date'    => $data['end_date'] ?? null,
            'amount'      => $data['amount'],
            'include_tax' => $request->boolean('include_tax'),
            'status'      => $data['status'] ?? 'active',
        ]);

        if ($request->hasFile('contract_file')) {
            $path = $request->file('contract_file')->store('contracts', 'public');
            $contract->update(['contract_file' => $path]);
        }

        return redirect()->route('contracts.show', $contract)->with('success', 'تم إنشاء العقد بنجاح.');
    }

    public function show(Contract $contract)
    {
        // لو عندك Blade عادي
        return view('contracts.show', compact('contract'));
        // ولو عندك Livewire للعرض، خليه Route يروح للمكوّن مباشرة بدل الكنترولر
    }

    public function edit(Contract $contract)
    {
        return view('contracts.edit', compact('contract'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validTypes = array_keys(Contract::TYPES ?? []);
        $data = $request->validate([
            'client_id'   => 'required|exists:clients,id',
            'project_id'  => 'nullable|exists:projects,id',
            'offer_id'    => 'nullable|exists:offers,id',
            'type'        => ['required', Rule::in($validTypes ?: ['software','maintenance','marketing','data_entry','call_center','supply_install'])],
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'amount'      => 'required|numeric|min:0',
            'include_tax' => 'boolean',
            'status'      => 'nullable|in:draft,active,suspended,completed,cancelled',
            'contract_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:8192',
        ]);

        $contract->update([
            'client_id'   => $data['client_id'],
            'project_id'  => $data['project_id'] ?? null,
            'offer_id'    => $data['offer_id'] ?? null,
            'type'        => $data['type'],
            'start_date'  => $data['start_date'] ?? null,
            'end_date'    => $data['end_date'] ?? null,
            'amount'      => $data['amount'],
            'include_tax' => $request->boolean('include_tax'),
            'status'      => $data['status'] ?? ($contract->status ?? 'active'),
        ]);

        if ($request->hasFile('contract_file')) {
            $path = $request->file('contract_file')->store('contracts', 'public');
            $contract->update(['contract_file' => $path]);
        }

        return redirect()->route('contracts.show', $contract)->with('success', 'تم تعديل العقد بنجاح.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'تم حذف العقد بنجاح.');
    }
}
