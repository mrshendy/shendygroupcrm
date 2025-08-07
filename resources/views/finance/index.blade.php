@extends('layouts.master')

@section('title', 'الحسابات المالية')

@section('content')
   
            @livewire('finance.transactions.index')
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('transactionSaved', () => {
                alert('تم حفظ الحركة بنجاح!');
            });

            Livewire.on('transactionDeleted', () => {
                alert('تم حذف الحركة بنجاح!');
            });
        });
    </script>
@endsection
