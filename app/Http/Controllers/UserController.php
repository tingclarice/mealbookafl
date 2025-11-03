<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function update(Request $request, User $user)
    {
        // 1. Validate the incoming request
        $validated = $request->validate([
            'role' => [
                'required',
                Rule::in(['USER', 'ADMIN']), // Only allow 'USER' or 'ADMIN'
            ],
        ]);

        // 2. Update the user
        // Because we used route-model binding (User $user),
        // Laravel automatically fetched the correct user from the database.
        $user->update([
            'role' => $validated['role'],
        ]);

        // 3. Redirect back to the index page with a success message
        return redirect()->route('dashboard.users') // Assumes your index route is named 'admin.users.index'
                         ->with('success', 'User role updated successfully!');
    }
}
