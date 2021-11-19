<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::paginate(10);
        return $this->apiResponse(200, 'Users list.', ['data' => $users]);
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

        return $this->apiResponse(201, 'User created.');
    }

    public function show(User $user): JsonResponse
    {
        return $this->apiResponse(201, 'User show.', ['data' => $user]);
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

        return $this->apiResponse(201, 'User updated.');
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return $this->apiResponse(201, 'User deleted.');
    }
}
