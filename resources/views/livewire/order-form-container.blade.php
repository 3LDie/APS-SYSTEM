@extends('layouts.app')

@section('title', 'Record Farmer Order - APS Distribution')

@section('content')
    <div class="max-w-7xl mx-auto px-6">
        @livewire('order-form')
    </div>
@endsection