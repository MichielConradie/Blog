<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'phone' => 'sometimes',
        ]);

        $request->user()->update($request->only(['first_name', 'last_name', 'phone']));

        return response()->json(['message' => 'Profile updated', 'user' => $request->user()]);
    }
}
