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

    public function show($id): JsonResponse
    {
        return response()->json(User::findOrFail($id));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validate($request, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $validated['password'] = encrypt($validated['password']);
        User::create($validated);

        return response()->json(['message' => 'user created.'], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validated = $this->validate($request, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::findOrFail($id);
        $validated['password'] = $request->input('password')
            ? encrypt($validated['password'])
            : $user->password;

        $user->update($validated);

        return response()->json(['message' => 'user updated.'], 201);
    }

    public function destroy($id): JsonResponse
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'user deleted.']);
    }
}
