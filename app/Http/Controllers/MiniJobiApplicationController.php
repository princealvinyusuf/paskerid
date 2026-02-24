<?php

namespace App\Http\Controllers;

use App\Models\MiniJobiJob;
use App\Models\MiniJobiJobApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MiniJobiApplicationController extends Controller
{
    public function store(Request $request, int $id)
    {
        $job = MiniJobiJob::query()
            ->where('is_active', true)
            ->findOrFail($id);

        $application = MiniJobiJobApplication::query()->firstOrCreate(
            [
                'mini_jobi_job_id' => $job->id,
                'user_id' => $request->user()->id,
            ],
            [
                'status' => 'submitted',
                'applied_at' => Carbon::now(),
            ]
        );

        if (!$application->wasRecentlyCreated) {
            return redirect()
                ->route('minijobi.show', $job->id)
                ->with('success', 'Anda sudah pernah melamar pekerjaan ini.');
        }

        return redirect()
            ->route('minijobi.show', $job->id)
            ->with('success', 'Lamaran berhasil dikirim.');
    }
}

