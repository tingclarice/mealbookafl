<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroyProfile(Request $request)
    {
        $user = $request->user();

        if (strtolower($request->input('confirm_text')) !== 'delete my account') {
            return back()->withErrors([
                'confirm_text' => 'You must type "delete my account" to confirm.',
            ]);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been deleted.');
    }
}
