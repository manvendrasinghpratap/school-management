<?php

namespace App\Providers;

use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Auth::viaRequest('sms-api-token', function (Request $request) {
            $plain = $request->bearerToken();
            if (! $plain) return null;
            $token = ApiToken::with('user')->where('token', hash('sha256', $plain))->first();
            if (! $token || ($token->expires_at && $token->expires_at->isPast())) return null;
            if (! $token->user || ! $token->user->is_active || $token->user->is_deleted || ! $token->user->school_id) return null;
            $token->forceFill(['last_used_at' => now()])->save();
            $request->attributes->set('api_token', $token);
            return $token->user;
        });
    }
}
