@extends('layouts.plant')

@section('title', 'Add plant')

@section('content')
    <div class="page-head">
        <div>
            <h1>Add a plant</h1>
            <p class="muted">Enter the plant details to add it to inventory.</p>
        </div>
    </div>
    <section class="panel">
        @include('plants._form', ['plant' => null])
    </section>
@endsection
