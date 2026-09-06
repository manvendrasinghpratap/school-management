<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request. 
     */
    public function store(LoginRequest $request): RedirectResponse|JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Authenticate User
        |--------------------------------------------------------------------------
        */
        $request->authenticate();

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */
        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Get Authenticated User
        |--------------------------------------------------------------------------
        */
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Set User Role Session Flags
        |--------------------------------------------------------------------------
        */
        session([
            'is_admin' => ($user->designation && $user->designation->name === 'Admin') ? 1 : 0,
            'is_cashier' => ($user->designation && $user->designation->name === 'Cashier') ? 1 : 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Role-Based Redirect
        |--------------------------------------------------------------------------
        */
        $redirectUrl = $this->redirectByRole($user);

        /*
        |--------------------------------------------------------------------------
        | AJAX Response
        |--------------------------------------------------------------------------
        */
        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Login successful.',
                'redirect' => $redirectUrl,
                /*
                |--------------------------------------------------------------------------
                | User Information
                |--------------------------------------------------------------------------
                */
                'username' => $user->username,
                'email' => $user->email,
            ]);
        }
    }

    

   public function modellogin(Request $request): JsonResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($request->input('login'));

        /*
        |--------------------------------------------------------------------------
        | Find user by email OR username
        |--------------------------------------------------------------------------
        */
        $user = User::where('email', $login)
            ->orWhere('username', $login)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => trans('auth.failed'),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Account status check
        |--------------------------------------------------------------------------
        */
        /*
        if (
            $user->id != 1 &&
            (!$user->account || $user->account->status != 1)
        ) {
            return response()->json([
                'status' => false,
                'message' => 'Your subscription account has been deactivated. Please contact support.',
            ], 422);
        }
            */

        /*
        |--------------------------------------------------------------------------
        | Authenticate using email OR username
        |--------------------------------------------------------------------------
        */
        $authenticated = Auth::attempt(
            ['email' => $login, 'password' => $request->password],
            $request->boolean('remember')
        );

        if (!$authenticated) {
            $authenticated = Auth::attempt(
                ['username' => $login, 'password' => $request->password],
                $request->boolean('remember')
            );
        }
        if (!$authenticated) {
            return response()->json([
                'status' => false,
                'message' => trans('auth.failed'),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerate session
        |--------------------------------------------------------------------------
        */
        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Set user role/session flags
        |--------------------------------------------------------------------------
        */
        $loggedInUser = Auth::user();

        session([
            'is_admin' => (
                $loggedInUser->designation &&
                $loggedInUser->designation->name === 'Admin'
            ) ? 1 : 0,

            'is_cashier' => (
                $loggedInUser->designation &&
                $loggedInUser->designation->name === 'Cashier'
            ) ? 1 : 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Success response
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'redirect' => $this->redirectByRole($loggedInUser),
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirectByRole($user)
    {   
        switch ($user->user_type_id) {

            case 1:
                return route('administrator.dashboard');

            case 2:
                return route('admin.dashboard');

            case 3:
                return route('dashboard');

            default:
                return url('/');
                //return route('home');
        }
    }
}
