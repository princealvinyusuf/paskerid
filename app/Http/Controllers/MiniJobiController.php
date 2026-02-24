<?php

namespace App\Http\Controllers;

use App\Models\MiniJobiJob;
use Illuminate\Http\Request;

class MiniJobiController extends Controller
{
    public function index(Request $request)
    {
        $jobsQuery = MiniJobiJob::query()->where('is_active', true);

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $jobsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('location')) {
            $jobsQuery->where('location', $request->input('location'));
        }

        if ($request->filled('employment_type')) {
            $jobsQuery->where('employment_type', $request->input('employment_type'));
        }

        if ($request->filled('category')) {
            $jobsQuery->where('category', $request->input('category'));
        }

        $jobs = $jobsQuery
            ->orderByDesc('created_at')
            ->paginate(9);

        $locations = MiniJobiJob::query()
            ->where('is_active', true)
            ->whereNotNull('location')
            ->where('location', '<>', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        $employmentTypes = MiniJobiJob::query()
            ->where('is_active', true)
            ->whereNotNull('employment_type')
            ->where('employment_type', '<>', '')
            ->distinct()
            ->orderBy('employment_type')
            ->pluck('employment_type');

        $categories = MiniJobiJob::query()
            ->where('is_active', true)
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('jobs.index', compact('jobs', 'locations', 'employmentTypes', 'categories'));
    }

    public function show(int $id)
    {
        $job = MiniJobiJob::query()
            ->where('is_active', true)
            ->findOrFail($id);

        $relatedJobs = MiniJobiJob::query()
            ->where('is_active', true)
            ->where('id', '<>', $job->id)
            ->where(function ($query) use ($job) {
                $query->where('category', $job->category)
                    ->orWhere('location', $job->location);
            })
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        return view('jobs.show', compact('job', 'relatedJobs'));
    }
}

