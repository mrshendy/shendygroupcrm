@extends('layouts.master')

@section('title', 'انشاء عرض')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">انشاء عرض</h4>
            @livewire('offers.create')
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('offersAdded', () => {
                alert('تم إضافة عرض جديد بنجاح!');
            });

            Livewire.on('offersUpdated', () => {
                alert('تم تحديث بيانات العرض بنجاح!');
            });

            Livewire.on('offersDeleted', () => {
                alert('تم حذف العرض بنجاح!');
            });
        });
    </script>