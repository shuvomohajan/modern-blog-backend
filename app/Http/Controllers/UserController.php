<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(User::paginate(10));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validate($request, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $validated['password'] = \Hash::make($validated['password']);
        User::create($validated);

        return response()->json(['message' => 'user created.'], 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $this->validate($request, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $validated['password'] = $request->input('password')
            ? encrypt($validated['password'])
            : $user->password;

        $user->update($validated);

        return response()->json(['message' => 'user updated.'], 201);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(['message' => 'user deleted.']);
    }
}
