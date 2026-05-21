<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartRequestController extends Controller
{
    public function index(): View
    {
        $requests = PartRequest::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.part-requests.index', compact('requests'));
    }

    public function show(PartRequest $partRequest): View
    {
        return view('admin.part-requests.show', compact('partRequest'));
    }

    public function updateStatus(Request $request, PartRequest $partRequest): RedirectResponse
    {
        $request->validate([
            'status'      => ['required', 'in:new,in_progress,found,closed'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $partRequest->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Demande mise à jour.');
    }
}
