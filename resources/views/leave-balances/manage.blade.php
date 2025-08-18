@extends('layouts.master')

@section('title', 'قائمة الإجازات')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('leave-balances.manage')
        </div>
    </div>
@endsection

