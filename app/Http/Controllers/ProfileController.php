<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(private readonly AuditService $auditService) {}

    public function edit() { return view('profile.edit'); }

    public function update(UpdateProfileRequest $request)
    {
        $request->user()->update($request->validated());
        $this->auditService->log('profile.updated');
        return back()->with('status', 'Профиль обновлен.');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $request->user()->update(['password' => $request->input('password')]);
        $this->auditService->log('profile.password_changed');
        return back()->with('status', 'Пароль обновлен.');
    }

    public function destroy(Request $request)
    {
        $request->validate(['password' => ['required', 'current_password']]);
        $user = $request->user();
        $this->auditService->log('profile.deleted');
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
