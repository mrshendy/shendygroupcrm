<div class="card shadow-sm border-0 overflow-hidden" dir="rtl">
    <div class="card-header bg-light d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0">تعديل حركة مالية</h5>
        <a href="{{ route('finance.transactions.index') }}" class="btn btn-sm btn-outline-secondary">رجوع</a>
    </div>

    <div class="card-body p-4">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="row g-3">

                <!-- نوع الحركة -->
                <div class="col-12">
                    <span class="badge 
                        @if(in_array($type, ['مصروف','expense','expenses'])) bg-danger 
                        @else bg-success @endif">
                        {{ $type }}
                    </span>
                </div>

                <!-- من -->
                <div class="col-md-6">
                    <label class="form-label">من *</label>
                    <select wire:model.defer="from_account_id" class="form-select" required>
                        <option value="">— اختر —</option>
                        @foreach($accounts as $a)
                            @if($a->id != $to_account_id) {{-- استبعاد الحساب اللي مختاره في "إلى" --}}
                                <option value="{{ $a->id }}">{{ $a->name }}</option>
                            @endif
                        @endforeach
                    </select>
