<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user’s profile.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user’s profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the authenticated user’s profile.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'city'        => 'nullable|string|max:100',
            'bio'         => 'nullable|string|max:500',
            'avatar'      => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Retrieve real User model so IDE/Laravel recognize update()
        /** @var User $user */
        $user = User::findOrFail(Auth::id());

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Update other fields
        $user->update($data);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
