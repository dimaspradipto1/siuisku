<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('layouts.pages.profile.index', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('photo')) {
            if (filled($user->photo) && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(ProfileRequest $request)
    {
        $user = $request->user();
        $user->update([
            'password' => $request->validated()['password'],
        ]);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Password berhasil diperbarui.');
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        if (filled($user->photo) && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->update(['photo' => null]);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Foto profil dihapus dan kembali ke avatar inisial.');
    }
}
