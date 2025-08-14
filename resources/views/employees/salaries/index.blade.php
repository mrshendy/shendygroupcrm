@extends('layouts.master')

@section('title', 'قائمة المرتبات')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">قائمة المرتبات</h4>
            @livewire('employees.salaries.manage')
        </div>
    </div>
@endsection