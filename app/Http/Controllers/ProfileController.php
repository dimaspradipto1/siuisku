<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\ActivityLog;
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

        $photoChanged = false;
        if ($request->hasFile('photo')) {
            if (filled($user->photo) && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('avatars', 'public');
            $photoChanged = true;
        }

        $user->update($data);

        ActivityLog::record(
            $user,
            'profile',
            'Profil diperbarui',
            $photoChanged ? 'Informasi profil dan foto profil diperbarui.' : 'Informasi profil akun diperbarui.',
            'info',
        );

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

        ActivityLog::record(
            $user,
            'security',
            'Password berhasil diubah',
            'Password akun diperbarui melalui halaman My Profile.',
            'success',
        );

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

        ActivityLog::record(
            $user,
            'profile',
            'Foto profil dihapus',
            'Foto profil dikembalikan ke avatar inisial.',
            'warning',
        );

        return redirect()
            ->route('profile.index')
            ->with('success', 'Foto profil dihapus dan kembali ke avatar inisial.');
    }
}
