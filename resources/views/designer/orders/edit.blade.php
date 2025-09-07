@extends('designer.layouts.app')

@section('pageTitle', 'Edit Order')
@section('title', 'Edit Order')

@section('content')
    @livewire('designer-edit-order', ['appointment' => $appointment])
@endsection
