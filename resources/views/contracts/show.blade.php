@extends('layouts.master')

@section('title', 'قائمة التعاقدات')

@section('content')

            @livewire('contracts.show', ['contract' => $contract])
        </div>
    </div>
@endsection