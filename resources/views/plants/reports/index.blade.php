@extends('layouts.plant')

@section('title', 'Plant reports')

@section('content')
    <div class="page-head">
        <div>
            <h1>Plant reports</h1>
            <p class="muted">Review current inventory and export the same filtered results.</p>
        </div>
        <a class="btn secondary" href="{{ route('plants.index') }}">Back to plants</a>
    </div>

    <section class="panel" style="margin-bottom:20px">
        <form method="GET" action="{{ route('plants.reports.index') }}">
            <div class="filters">
                <div>
                    <label for="report_type">Report</label>
                    <select id="report_type" name="report_type">
                        <option value="all" @selected($filters['report_type'] === 'all')>All plants</option>
                        <option value="in-stock" @selected($filters['report_type'] === 'in-stock')>In-stock plants</option>
                        <option value="out-of-stock" @selected($filters['report_type'] === 'out-of-stock')>Out-of-stock plants</option>
                        <option value="category" @selected($filters['report_type'] === 'category')>Plants by category</option>
                        <option value="price-list" @selected($filters['report_type'] === 'price-list')>Plant price list</option>
                    </select>
                </div>
                <div>
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected($filters['category'] === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status">Stock status</label>
                    <select id="status" name="status">
                        <option value="">All statuses</option>
                        <option value="in-stock" @selected($filters['status'] === 'in-stock')>In stock</option>
                        <option value="out of stock" @selected($filters['status'] === 'out of stock')>Out of stock</option>
                    </select>
                </div>
                <div class="actions">
                    <button class="btn" type="submit">Apply filters</button>
                    <a class="btn secondary" href="{{ route('plants.reports.index') }}">Reset</a>
                </div>
            </div>
        </form>
    </section>

    <div class="page-head">
        <div>
            <h2>{{ $reportTitle }}</h2>
            <p class="muted">{{ $plants->count() }} plant(s)</p>
        </div>
        <div class="actions">
            <a class="btn secondary" href="{{ route('plants.reports.pdf', $filters) }}">Export PDF</a>
            <a class="btn secondary" href="{{ route('plants.reports.excel', $filters) }}">Export for Excel (CSV)</a>
        </div>
    </div>

    <section class="panel">
        @if ($plants->isEmpty())
            <p class="muted">No plants match the selected report and filters.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Plant</th>
                            <th>Category</th>
                            <th>Unit price</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Created by</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plants as $plant)
                            <tr>
                                <td><a href="{{ route('plants.show', $plant) }}">{{ $plant->plant_name }}</a></td>
                                <td>{{ $plant->category }}</td>
                                <td>{{ number_format((float) $plant->unit_price, 2) }}</td>
                                <td>{{ $plant->status === 'in-stock' ? 'In stock' : 'Out of stock' }}</td>
                                <td>{{ $plant->description }}</td>
                                <td>{{ $plant->user?->name ?? 'Former user' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
