@extends('layouts.master')

@section('title', 'إدارة الشيفتات')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4>إدارة الشيفتات</h4>
            @livewire('shifts.manage')
        </div>
    </div>
@endsection
