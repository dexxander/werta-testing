<?php

namespace AdminDashboard\Http\Controllers;

use AdminDashboard\Models\Administrator;
use Illuminate\Http\Request;

class AdministratorController
{
    public function index($role)
    {
        return view('admindashboard::administrators', [
            'role' => $role,
            'administrators' => Administrator::orderByDesc('created_at')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:administrators,email',
        ]);

        Administrator::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => 'active',
        ]);

        return back()->with('success', 'Administrator account created.');
    }

    public function destroy(Administrator $administrator)
    {
        $administrator->delete();
        return back()->with('success', 'Administrator account removed.');
    }
}