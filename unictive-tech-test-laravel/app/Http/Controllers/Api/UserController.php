<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::with('hobbies')->get();
        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $dataValidated = $request->validated();

            $results = User::create([
                'name' => $dataValidated['name'],
                'email' => $dataValidated['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($dataValidated['password']),
            ]);

            $results->hobbies()->createMany($dataValidated['hobbies']);

            if (!$results)
                return response()->json(['message' => 'Error creating user'], 400);

            DB::commit();
            return response()->json([
                'message' => 'User created successfully',
                'data' => $results->load('hobbies'),
            ], 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating user',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('hobbies')->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $dataValidated = $request->validated();

            $user->update([
                'name' => $dataValidated['name'] ?? $user->name,
                'email' => $dataValidated['email'] ?? $user->email,
                'password' => isset($dataValidated['password']) ? Hash::make($dataValidated['password']) : $user->password,
            ]);
            if (isset($dataValidated['hobbies']) && !empty($dataValidated['hobbies'])) {
                $user->hobbies()->delete();
                $user->hobbies()->createMany($dataValidated['hobbies']);
            }
            if (!$user)
                return response()->json(['message' => 'Error updating user'], 400);
            DB::commit();
            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user->load('hobbies'),
            ], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating user',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Error deleting user',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }
}
