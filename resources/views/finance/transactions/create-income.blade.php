@extends('layouts.master')

@section('title', 'إضافة تحصيل')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('finance.transactions.create-collection', ['type' => 'تحصيل'])
        </div>
    </div>
@endsection
