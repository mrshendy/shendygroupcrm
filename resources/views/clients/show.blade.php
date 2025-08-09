@extends('layouts.master')

@section('title', 'ملف العميل')

@section('content')

            @livewire('clients.show', ['client' => $id])
        </div>
    </div>
@endsection

    