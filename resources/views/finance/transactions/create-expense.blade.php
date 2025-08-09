@extends('layouts.master')

@section('title', 'إضافة مصروف')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('finance.transactions.create-expense', ['type' => 'مصروف'])
        </div>
    </div>
@endsection
