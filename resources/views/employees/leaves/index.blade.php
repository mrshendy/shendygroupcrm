@extends('layouts.master')

@section('title', 'قائمة الإجازات')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">قائمة الإجازات</h4>
            @livewire('employees.leaves.manage')
        </div>
    </div>
@endsection