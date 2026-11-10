<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlantRequest;
use App\Http\Requests\UpdatePlantRequest;
use App\Models\Plant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class PlantsController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Plant::class);
        $plants = Plant::with('user')->latest()->paginate(12);

        return view('plants.index', compact('plants'));
    }

    public function create(): View
    {
        Gate::authorize('create', Plant::class);

        return view('plants.create');
    }

    public function store(StorePlantRequest $request): RedirectResponse
    {
        Gate::authorize('create', Plant::class);

        // Creating through the relationship always records the authenticated creator.
        $request->user()->plants()->create($request->validated());

        return redirect()->route('plants.index')->with('success', 'Plant created successfully.');
    }

    public function show(Plant $plant): View
    {
        Gate::authorize('view', $plant);

        return view('plants.show', compact('plant'));
    }

    public function edit(Plant $plant): View
    {
        Gate::authorize('update', $plant);

        return view('plants.edit', compact('plant'));
    }

    public function update(UpdatePlantRequest $request, Plant $plant): RedirectResponse
    {
        Gate::authorize('update', $plant);

        $plant->update($request->validated());

        return redirect()->route('plants.show', $plant)->with('success', 'Plant updated successfully.');
    }

    public function destroy(Plant $plant): RedirectResponse
    {
        Gate::authorize('delete', $plant);

        $plant->delete();

        return redirect()->route('plants.index')->with('success', 'Plant deleted successfully.');
    }
}
