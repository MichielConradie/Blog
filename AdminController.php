<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function listUsers()
    {
        $users = User::with('roles')->paginate(10);
        return response()->json($users);
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $role = Role::where('name', $request->role)->first();
        $user->roles()->syncWithoutDetaching([$role->id]);

        return response()->json(['message' => 'Role assigned successfully', 'user' => $user->load('roles')]);
    }
}
