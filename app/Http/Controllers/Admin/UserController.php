<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::where('role', 'customer')
            ->withCount('orders')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->loadCount('orders');
        $orders = $user->orders()->latest()->paginate(10);

        return view('admin.users.show', compact('user', 'orders'));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $user->status = $user->status === 'active' ? 'blocked' : 'active';
        $user->save();

        $label = $user->status === 'active' ? 'réactivé' : 'bloqué';

        return back()->with('success', "Compte de {$user->name} {$label}.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Compte de {$name} supprimé.");
    }
}
