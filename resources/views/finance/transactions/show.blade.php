@extends('layouts.master')

@section('title', 'عرض حركة مالية')

@section('content')
    @livewire('finance.transactions.show', ['transactionId' => $transactionId])
@endsection
