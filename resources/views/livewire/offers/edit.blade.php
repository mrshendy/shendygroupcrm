<div class="container py-4">
    <h3 class="mb-4">تعديل العرض</h3>

    @if (session()->has('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="mdi mdi-check-circle-outline me-2 fs-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="update" class="row g-3 needs-validation" novalidate>
        <!-- العميل -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">العميل <span class="text-danger">*</span></label>
            <select wire:model="client_id" class="form-select" required>
                <option value="">اختر العميل</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
            @error('client_id') 
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- المشروع -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">المشروع</label>
            <select wire:model="project_id" class="form-select">
                <option value="">اختر المشروع</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
            @error('project_id') 
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- التواريخ -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">تاريخ البدء</label>
            <input type="date" wire:model="start_date" class="form-control">
            @error('start_date') 
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">تاريخ الانتهاء</label>
            <input type="date" wire:model="end_date" class="form-control">
            @error('end_date') 
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- الحالة -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">الحالة</label>
            <select wire:model="status" class="form-select">
                <option value="new">جديد</option>
                <option value="under_review">تحت المتابعة</option>
                <option value="approved">تمت الموافقة</option>
                <option value="contracting">جارٍ التعاقد</option>
                <option value="rejected">مرفوض</option>
                <option value="pending">قيد الانتظار</option>
                <option value="signed">تم التعاقد</option>
                <option value="closed">مغلق</option>
            </select>
            @error('status') 
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- قيمة العرض -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">قيمة العرض</label>
            <input type="number" wire:model="amount" class="form-control" step="0.01" placeholder="0.00">
            @error('amount') 
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- الوصف -->
        <div class="col-12">
            <label class="form-label fw-semibold">الوصف</label>
            <textarea wire:model="description" class="form-control" rows="3"></textarea>
            @error('description') 
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- زر التحديث -->
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="mdi mdi-content-save-edit-outline me-1"></i> تحديث
            </button>
        </div>
    </form>
</div>
