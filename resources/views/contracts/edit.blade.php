@extends('layouts.master')

@section('title', 'قائمة التعاقدات')

@section('content')

            @livewire('contracts.edit', ['contract' => $contract])
        </div>
    </div>
@endsection