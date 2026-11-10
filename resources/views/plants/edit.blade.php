@extends('layouts.plant')

@section('title', 'Edit plant')

@section('content')
    <div class="page-head">
        <div>
            <h1>Edit {{ $plant->plant_name }}</h1>
            <p class="muted">Update the saved plant inventory record.</p>
        </div>
    </div>
    <section class="panel">
        @include('plants._form', ['plant' => $plant])
    </section>
@endsection
