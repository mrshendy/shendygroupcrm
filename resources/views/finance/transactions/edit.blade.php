@extends('layouts.master')

@section('title', 'تعديل حركة مالية')

@section('content')
    @livewire('finance.transactions.edit', ['transactionId' => $transactionId])
@endsection
