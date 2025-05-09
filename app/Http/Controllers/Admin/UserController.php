<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = new UserCollection(User::all());
            if ($users->isEmpty()) {
                return response()->json(['message' => 'No users found'], 404);
            }
            return response()->json(
                [
                    'message' => 'Users retrieved successfully',
                    'status' => 200,
                    'total' => $users->count(),
                    'data' => $users,

                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch users'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $valited = $request->validated();
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $valited['photo'] = 'uploads/' . $filename;
            }
            //Add new User
            $user = User::create([
                'full_name' => $valited['name'],
                'email' => $valited['email'],
                'national_id' => $valited['national_id'],
                'photo' => $valited['photo'],
                'medical_council_id' => $valited['medical_council_id'],
                'contract_number' => $valited['contract_number'],
                'role' => $valited['role'],
                'status' => $valited['status'],
                'address' => $valited['address'],
                'password' => bcrypt($valited['password']),
            ]);
            return response()->json(
                [
                    'message' => 'User created successfully',
                    'status' => 201,
                    'data' => new UserResource($user),
                ]
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'status' => 422,
                'errors' => $e->validator->errors(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create user',
                'status' => 500,
                'data' => null,
            ]);
        }
    }








    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        try {
            $user = new UserResource($user);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                    'status' => 404,
                    'data' => null,
                ]);
            }
            return response()->json(
                [
                    'message' => 'User retrieved successfully',
                    'status' => 200,
                    'data' => $user,
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch user'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
