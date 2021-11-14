<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return $this->apiResponse(201, 'user created.');
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email', 'max:255', Rule::exists('users', 'email')],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return $this->apiResponse(422, 'The provided credentials are incorrect.');
        }
        $token = $user->createToken($request->input('email'))->plainTextToken;

        return $this->apiResponse(200, 'Login Successful.', ['token' => $token,]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->apiResponse(200, 'Logout successful.');
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'old_password' => ['required', 'string'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($request->input('old_password'), auth()->user()->password)) {
            return $this->apiResponse(422, 'Old Password is incorrect.');
        }
        auth()->user()->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return $this->apiResponse(201, 'Password changed successfully.');
    }

    public function forgetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::exists('users', 'email')]
        ]);

        // Password::sendResetLink($validator->validated());

        return $this->apiResponse(200, 'We send a password reset link to your email.');
    }
}
