@extends('layouts.master')

@section('title', 'قائمة العملاء')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">ادارة تسجيل الحضور </h4>
@livewire('attendance.edit', ['id' => $id])
        </div>
    </div>
@endsection
@section('scripts')
