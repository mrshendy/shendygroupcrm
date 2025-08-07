@extends('layouts.master')

@section('title', 'البنود')

@section('content')
    <div class="card">
        <div class="card-body">
          
            @livewire('finance.items.manage')
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        window.addEventListener('itemAdded', function () {
    alert('تم إضافة البند جديد بنجاح!');
});

window.addEventListener('itemUpdated', function () {
    alert('تم تحديث بيانات البند بنجاح!');
});

window.addEventListener('itemDeleted', function () {
    alert('تم حذف البند بنجاح!');
});

        
    </script>