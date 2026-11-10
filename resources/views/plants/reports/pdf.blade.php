<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #20342b; font-size: 11px; }
        h1 { margin-bottom: 4px; }
        p { color: #65756b; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #d5e0d7; padding: 7px; text-align: left; }
        th { background: #e8f1e9; }
    </style>
</head>
<body>
    <h1>{{ $reportTitle }}</h1>
    <p>Generated {{ now()->format('Y-m-d H:i') }} · {{ $plants->count() }} plant(s)</p>
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
            @forelse ($plants as $plant)
                <tr>
                    <td>{{ $plant->plant_name }}</td>
                    <td>{{ $plant->category }}</td>
                    <td>{{ number_format((float) $plant->unit_price, 2) }}</td>
                    <td>{{ $plant->status === 'in-stock' ? 'In stock' : 'Out of stock' }}</td>
                    <td>{{ $plant->description }}</td>
                    <td>{{ $plant->user?->name ?? 'Former user' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No plants match the selected report and filters.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
