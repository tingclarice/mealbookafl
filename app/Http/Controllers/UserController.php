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
    // Update User Role (Site Manager or User)
    public function updateAdmin(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => [
                'required',
                Rule::in(['USER', 'ADMIN']), // Only allow 'USER' or 'ADMIN'
            ],
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()->route('dashboard.users')
            ->with('success', 'User role updated successfully!');
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $request->user()->fill($request->validated());

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            $request->user()->save();

            return Redirect::route('profile.edit')->with('success', 'Profile information updated successfully!');
        } catch (\Exception $e) {
            return Redirect::route('profile.edit')->with('error', 'Failed to update profile. ' . $e->getMessage());
        }
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
