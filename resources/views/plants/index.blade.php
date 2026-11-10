@extends('layouts.plant')

@section('title', 'Plants')

@section('content')
    <div class="page-head">
        <div>
            <h1>Plant inventory</h1>
            <p class="muted">Manage nursery plants and their current stock status.</p>
        </div>
        <div class="actions">
            <a class="btn secondary" href="{{ route('plants.reports.index') }}">View reports</a>
            <a class="btn" href="{{ route('plants.create') }}">Add plant</a>
        </div>
    </div>

    <section class="panel">
        @if ($plants->isEmpty())
            <p class="muted">No plants have been added yet.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Plant</th>
                            <th>Category</th>
                            <th>Unit price</th>
                            <th>Status</th>
                            <th>Created by</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plants as $plant)
                            <tr>
                                <td><strong>{{ $plant->plant_name }}</strong></td>
                                <td>{{ $plant->category }}</td>
                                <td>{{ number_format((float) $plant->unit_price, 2) }}</td>
                                <td><span class="badge {{ $plant->status === 'in-stock' ? 'in' : 'out' }}">{{ $plant->status === 'in-stock' ? 'In stock' : 'Out of stock' }}</span></td>
                                <td>{{ $plant->user?->name ?? 'Former user' }}</td>
                                <td><a href="{{ route('plants.show', $plant) }}">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $plants->links() }}</div>
        @endif
    </section>
@endsection
