<?php

namespace AdminDashboard\Http\Controllers;

use AdminDashboard\Models\Client;
use Illuminate\Http\Request;

class UserManagementController
{
    public function index($role)
    {
        return view('admindashboard::user-management', [
            'role' => $role,
            'clients' => Client::orderByDesc('created_at')->get(),
        ]);
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('success', 'Client account removed.');
    }
}