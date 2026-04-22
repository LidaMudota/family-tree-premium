<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use App\Models\Tree;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(private readonly AuditService $auditService) {}

    public function index()
    {
        return view('admin.index', [
            'users' => User::latest()->paginate(20),
            'trees' => Tree::with('user')->latest()->paginate(20, ['*'], 'trees_page'),
            'events' => AuditEvent::latest()->limit(100)->get(),
        ]);
    }

    public function toggleBlock(Request $request, User $user)
    {
        $reason = $request->validate(['reason' => ['nullable', 'string', 'max:255']]);
        $user->update([
            'is_blocked' => ! $user->is_blocked,
            'blocked_at' => ! $user->is_blocked ? now() : null,
            'blocked_reason' => ! $user->is_blocked ? ($reason['reason'] ?? 'Blocked by admin') : null,
        ]);
        $this->auditService->log('admin.user_block_toggled', User::class, $user->id, ['is_blocked' => $user->is_blocked]);

        return back()->with('status', 'Статус пользователя обновлен.');
    }
}
