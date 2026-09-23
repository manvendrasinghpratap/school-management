<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate(['login' => ['required', 'string'], 'password' => ['required', 'string'], 'device_name' => ['nullable', 'string', 'max:100']]);
        $user = User::where('email', $data['login'])->orWhere('username', $data['login'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['status' => false, 'message' => 'Invalid credentials.'], 422);
        }
        if (! $user->is_active || $user->is_deleted || ! $user->school_id) {
            return response()->json(['status' => false, 'message' => 'Account is inactive or not linked to a school.'], 403);
        }
        $plain = Str::random(64);
        ApiToken::create(['user_id' => $user->id, 'name' => $data['device_name'] ?? 'api', 'token' => hash('sha256', $plain), 'abilities' => ['portal:*']]);
        return response()->json(['status' => true, 'message' => 'Login successful.', 'token' => $plain, 'token_type' => 'Bearer', 'user' => $this->userPayload($user)]);
    }

    public function logout(Request $request)
    {
        $token = $request->attributes->get('api_token');
        if ($token) $token->delete();
        return response()->json(['status' => true, 'message' => 'Logged out successfully.']);
    }

    public function me(Request $request)
    {
        return response()->json(['status' => true, 'user' => $this->userPayload($request->user())]);
    }

    public function refresh(Request $request)
    {
        $old = $request->attributes->get('api_token');
        if ($old) $old->delete();
        $plain = Str::random(64);
        ApiToken::create(['user_id' => $request->user()->id, 'name' => 'refreshed-api', 'token' => hash('sha256', $plain), 'abilities' => ['portal:*']]);
        return response()->json(['status' => true, 'token' => $plain, 'token_type' => 'Bearer']);
    }

    public function store(Request $request)
    {
        return response()->json(['status' => false, 'message' => 'Portal account registration is administered by the school.'], 422);
    }

    private function userPayload(User $user): array
    {
        return ['id' => $user->id, 'school_id' => $user->school_id, 'name' => $user->name, 'email' => $user->email, 'username' => $user->username, 'roles' => $user->getRoleNames()->values()];
    }
}
