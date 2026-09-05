<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'username' => ['required'],
        ]);

        $login = trim($request->input('username'));
        $user = User::where('username', $login)->orWhere('email', $login)->first();
        if (!$user) {
            return response()->json([
                'status' => true,
                'message' => 'If an account exists with these details, a password reset link has been sent to the registered email address.',
            ]);
        }
        $status = Password::sendResetLink(['email' => $user->email]);
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => true,
                'message' => 'If an account exists with these details, a password reset link has been sent to the registered email address.',
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => __($status),
        ], 422);
    }
}
