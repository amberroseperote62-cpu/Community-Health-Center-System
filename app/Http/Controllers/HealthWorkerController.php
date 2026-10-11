<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHealthWorkerRequest;
use App\Http\Requests\UpdateHealthWorkerRequest;
use App\Models\HealthWorker;

/**
 * Resource controller for managing health workers (clinic staff).
 */
class HealthWorkerController extends Controller
{
    /**
     * Display a searchable, paginated list of all health workers.
     */
    public function index()
    {
        $search       = request('search');
        $healthWorkers = HealthWorker::when($search, function ($query, $search) {
            $query->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
        })
        ->orderBy('last_name')
        ->paginate(10)
        ->withQueryString();

        return view('health-workers.index', compact('healthWorkers', 'search'));
    }

    public function create()
    {
        return view('health-workers.create');
    }

    public function store(StoreHealthWorkerRequest $request)
    {
        HealthWorker::create($request->validated());

        return redirect()->route('health-workers.index')
            ->with('success', 'Health worker added successfully.');
    }

    public function show(HealthWorker $healthWorker)
    {
        $healthWorker->load(['appointments.patient', 'consultations.patient']);
        return view('health-workers.show', compact('healthWorker'));
    }

    public function edit(HealthWorker $healthWorker)
    {
        return view('health-workers.edit', compact('healthWorker'));
    }

    public function update(UpdateHealthWorkerRequest $request, HealthWorker $healthWorker)
    {
        $healthWorker->update($request->validated());

        return redirect()->route('health-workers.show', $healthWorker)
            ->with('success', 'Health worker updated successfully.');
    }

    /**
     * Delete the health worker record.
     * Only accessible by admins (enforced by route middleware).
     */
    public function destroy(HealthWorker $healthWorker)
    {
        $healthWorker->delete();

        return redirect()->route('health-workers.index')
            ->with('success', 'Health worker removed successfully.');
    }
}
