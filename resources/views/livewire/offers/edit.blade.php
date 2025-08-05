<div class="container py-4">
    <h3>تعديل العرض</h3>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="update" class="row g-3">
        <div class="col-md-6">
            <label>العميل</label>
            <select wire:model="client_id" class="form-select">
                <option value="">اختر العميل</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label>المشروع</label>
            <select wire:model="project_id" class="form-select">
                <option value="">اختر المشروع</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label>تاريخ البدء</label>
            <input type="date" wire:model="start_date" class="form-control">
        </div>

        <div class="col-md-6">
            <label>تاريخ الانتهاء</label>
            <input type="date" wire:model="end_date" class="form-control">
        </div>

        <div class="col-md-6">
            <label>الحالة</label>
            <select wire:model="status" class="form-select">
             
                <option value="active">نشط</option>
                <option value="closed">مغلق</option>
                <option value="expired">منتهي</option>
            </select>
        </div>

        <div class="col-md-6">
            <label>قيمة العرض</label>
            <input type="number" wire:model="value" class="form-control">
        </div>

        <div class="col-12">
            <label>الوصف</label>
            <textarea wire:model="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">تحديث</button>
        </div>
    </form>
</div>
