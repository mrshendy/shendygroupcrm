@extends('layouts.master')

@section('title', 'تفاصيل المشروع')

@section('content')
    <div class="card">
        <div class="card-body">
            @livewire('projects.show', ['id' => $id])
        </div>
    </div>
@endsection
