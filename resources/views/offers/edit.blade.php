@extends('layouts.master')

@section('title', 'قائمة العروض')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">قائمة العروض</h4>
            @livewire('offers.edit',['id' => $id])
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('offerAdded', () => {
                alert('✅ تم إضافة العرض بنجاح!');
            });

            Livewire.on('offerUpdated', () => {
                alert('✏️ تم تحديث بيانات العرض بنجاح!');
            });

            Livewire.on('offerDeleted', () => {
                alert('🗑️ تم حذف العرض بنجاح!');
            });
        });
    </script>
@endsection
