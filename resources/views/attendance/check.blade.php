@extends('layouts.master')

@section('title', 'الحضور والإجازات')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="mb-4">قائمة الحضور والانصراف</h4>
                @livewire('attendance.check')
            </div>
        </div>
    </div>  
@endsection
