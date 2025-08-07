@extends('layouts.master')

@section('title', 'إضافة مصروف')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('finance.transactions.create', ['type' => 'مصروف'])
        </div>
    </div>
@endsection
