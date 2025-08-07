@extends('layouts.master')

@section('title', 'إضافة تحصيل')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('finance.transactions.create', ['type' => 'تحصيل'])
        </div>
    </div>
@endsection
