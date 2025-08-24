@extends('layouts.master')

@section('title', 'قائمة العملاء')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">قائمة العملاء</h4>
            @livewire('clients.index')
        </div>
    </div>
@endsection

@section('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('clientAdded', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح',
                    text: 'تم إضافة عميل جديد بنجاح!',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            Livewire.on('clientUpdated', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم التحديث',
                    text: 'تم تحديث بيانات العميل بنجاح!',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            Livewire.on('clientDeleted', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم الحذف',
                    text: 'تم حذف العميل بنجاح!',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
    </script>
@endsection
