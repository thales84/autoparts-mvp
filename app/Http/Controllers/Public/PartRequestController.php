<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartRequestRequest;
use App\Models\PartRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartRequestController extends Controller
{
    public function create(Request $request): View
    {
        $prefill = [
            'requested_part_name' => $request->query('part', ''),
            'reference'           => $request->query('reference', ''),
        ];

        return view('public.part-requests.create', compact('prefill'));
    }

    public function store(StorePartRequestRequest $request): RedirectResponse
    {
        PartRequest::create([
            'user_id'             => auth()->id(),
            'requested_part_name' => $request->requested_part_name,
            'reference'           => $request->reference,
            'vehicle_make'        => $request->vehicle_make,
            'vehicle_model'       => $request->vehicle_model,
            'vehicle_year'        => $request->vehicle_year,
            'description'         => $request->description,
            'contact_name'        => $request->contact_name,
            'contact_email'       => $request->contact_email,
            'contact_phone'       => $request->contact_phone,
            'status'              => 'new',
        ]);

        return redirect()->route('home')
            ->with('success', 'Votre demande a bien été enregistrée. Nous vous contacterons dès que possible.');
    }
}
