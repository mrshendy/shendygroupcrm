@extends('layouts.master')

@section('title', 'قائمة الإجازات')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">قائمة أرصدة الإجازات</h4>
            @livewire('leave-balances.manage')
        </div>
    </div>
@endsection

