@extends('layouts.plant')

@section('title', $plant->plant_name)

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $plant->plant_name }}</h1>
            <p class="muted">Plant inventory record</p>
        </div>
        <div class="actions">
            <a class="btn secondary" href="{{ route('plants.index') }}">Back to plants</a>
            <a class="btn" href="{{ route('plants.edit', $plant) }}">Edit plant</a>
        </div>
    </div>

    <section class="panel">
        <div class="grid">
            <div><p class="muted">Category</p><strong>{{ $plant->category }}</strong></div>
            <div><p class="muted">Unit price</p><strong>{{ number_format((float) $plant->unit_price, 2) }}</strong></div>
            <div><p class="muted">Stock status</p><span class="badge {{ $plant->status === 'in-stock' ? 'in' : 'out' }}">{{ $plant->status === 'in-stock' ? 'In stock' : 'Out of stock' }}</span></div>
            <div><p class="muted">Created by</p><strong>{{ $plant->user?->name ?? 'Former user' }}</strong></div>
        </div>
        <hr style="border:0;border-top:1px solid #e7eee8;margin:24px 0">
        <h2>Description</h2>
        <p>{{ $plant->description }}</p>
        <div class="actions" style="margin-top:24px">
            <form method="POST" action="{{ route('plants.destroy', $plant) }}" onsubmit="return confirm('Delete this plant from inventory?')">
                @csrf
                @method('DELETE')
                <button class="btn danger" type="submit">Delete plant</button>
            </form>
        </div>
    </section>
@endsection
