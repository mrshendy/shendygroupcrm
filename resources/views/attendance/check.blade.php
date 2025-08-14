@extends('layouts.master')

@section('title', 'قائمة الحضور والانصراف')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">قائمة الحضور والانصراف</h4>
            @livewire('attendance.check')
        </div>
    </div>
@endsection