<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json(['status' => true, 'data' => $request->user()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($request->user()->id)]]);
        $request->user()->update($data);
        return response()->json(['status' => true, 'message' => 'Profile updated successfully.', 'data' => $request->user()->fresh()]);
    }

    public function password(Request $request)
    {
        $data = $request->validate(['current_password' => ['required'], 'password' => ['required', 'confirmed', 'min:8']]);
        abort_unless(Hash::check($data['current_password'], $request->user()->password), 422, 'Current password is incorrect.');
        $request->user()->update(['password' => Hash::make($data['password'])]);
        return response()->json(['status' => true, 'message' => 'Password updated successfully.']);
    }
}
