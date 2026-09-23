<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'username' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Determine if the input is an email or a username
        $loginField = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Find the user by email or username so we can pass their email to Password::reset()
        $user = User::where($loginField, $request->input('username'))->first();

        $credentials = [
            'email' => $user ? $user->email : $request->input('username'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
            'token' => $request->input('token'),
        ];

        $status = Password::reset(
            $credentials,
            function ($userInstance) use ($request) {
                $userInstance->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($userInstance));
            }
        );

        if ($request->wantsJson() || $request->ajax()) {
            return $status == Password::PASSWORD_RESET
                ? response()->json([
                    'status' => true,
                    'message' => __($status),
                    'redirect' => route('login')
                ])
                : response()->json([
                    'status' => false,
                    'message' => __($status),
                    'errors' => ['username' => [__($status)]]
                ], 422);
        }

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('username'))
                        ->withErrors(['username' => __($status)]);
    }


   
}
