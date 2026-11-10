<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use RuntimeException;

class PlantReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('plants.reports.index', $this->reportData($request));
    }

    public function pdf(Request $request)
    {
        $data = $this->reportData($request);

        return Pdf::loadView('plants.reports.pdf', $data)
            ->setPaper('a4', 'landscape')
            ->download('plant-report.pdf');
    }

    public function excel(Request $request): Response
    {
        $data = $this->reportData($request);
        $stream = fopen('php://temp', 'r+');

        if ($stream === false) {
            throw new RuntimeException('Unable to create the plant report export.');
        }

        fwrite($stream, "\xEF\xBB\xBF");
        fputcsv($stream, ['Plant name', 'Category', 'Unit price', 'Status', 'Description', 'Created by']);

        foreach ($data['plants'] as $plant) {
            fputcsv($stream, [
                $this->spreadsheetSafe($plant->plant_name),
                $this->spreadsheetSafe($plant->category),
                number_format((float) $plant->unit_price, 2, '.', ''),
                $plant->status === 'in-stock' ? 'In stock' : 'Out of stock',
                $this->spreadsheetSafe($plant->description),
                $this->spreadsheetSafe($plant->user?->name ?? ''),
            ]);
        }

        rewind($stream);
        $csv = stream_get_contents($stream);
        fclose($stream);

        if ($csv === false) {
            throw new RuntimeException('Unable to read the generated plant report export.');
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plant-report.csv"',
        ]);
    }

    /**
     * @return array{plants: \Illuminate\Database\Eloquent\Collection<int, Plant>, categories: Collection<int, string>, filters: array<string, string>, reportTitle: string}
     */
    private function reportData(Request $request): array
    {
        $filters = $request->validate([
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:in-stock,out of stock'],
            'report_type' => ['nullable', 'in:all,in-stock,out-of-stock,category,price-list'],
        ]);

        $type = $filters['report_type'] ?? 'all';
        $query = Plant::with('user');

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if ($type === 'in-stock') {
            $query->where('status', 'in-stock');
        } elseif ($type === 'out-of-stock') {
            $query->where('status', 'out of stock');
        } elseif (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if ($type === 'price-list') {
            $query->orderBy('unit_price')->orderBy('plant_name');
        } elseif ($type === 'category') {
            $query->orderBy('category')->orderBy('plant_name');
        } else {
            $query->orderBy('plant_name');
        }

        $reportTitle = match ($type) {
            'in-stock' => 'In-stock plants',
            'out-of-stock' => 'Out-of-stock plants',
            'category' => 'Plants by category',
            'price-list' => 'Plant price list',
            default => 'Plant inventory report',
        };

        return [
            'plants' => $query->get(),
            'categories' => Plant::query()->distinct()->orderBy('category')->pluck('category'),
            'filters' => [
                'category' => $filters['category'] ?? '',
                'status' => $filters['status'] ?? '',
                'report_type' => $type,
            ],
            'reportTitle' => $reportTitle,
        ];
    }

    private function spreadsheetSafe(string $value): string
    {
        return preg_match('/^[\t\r\n ]*[=+\-@]/u', $value) === 1
            ? "'".$value
            : $value;
    }
}
