<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]);
            $user = User::where('email', $validatedData['email'])->first();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            if (!Auth::attempt($validatedData)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            $authenticatedUser = Auth::user();


            return response()->json(
                [
                    'message' => 'Login successful',
                    'status' => 200,
                    'access_token' => $user->createToken('auth_token')->plainTextToken,
                    'token_type' => 'Bearer',
                    'expires_in' => 60 * 24 * 7,

                ]
            );
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Login failed',
                'status' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function logout(Request $request)
    {
        // Handle the logout logic here
        return response()->json(['message' => 'Logout successful']);
    }

    public function register(Request $request)
    {
        try {
            $valited = $request->validate([
                'full_name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
                'national_id' => 'required|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'medical_council_id' => 'required|string',
                'contract_number' => 'required|string',
                'status' => 'required|in:active,inactive',
                'address' => 'required|string',
            ]);
            /* if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $filename);
                $valited['photo'] = 'images/' . $filename;
            } else {
                $valited['photo'] = null;
            } */

            $photoPath = $this->handleImage($request);
            //Add new User
            $user = User::create([
                'full_name' => $valited['full_name'],
                'email' => $valited['email'],
                'national_id' => $valited['national_id'],
                'photo' => $photoPath,
                'medical_council_id' => $valited['medical_council_id'],
                'contract_number' => $valited['contract_number'],
                'status' => $valited['status'],
                'address' => $valited['address'],
                'password' => bcrypt($valited['password']),
            ]);
           // $user->assignRole('user');
            return response()->json(
                [
                    'message' => 'User created successfully',
                    'status' => 201,
                    // 'data' => $valited //new UserResource($user),
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
                'message' => 'Failed to create user' . $e->getMessage(),
                'status' => 500,
                'data' => null,
            ]);
        }
    }

    public function handleImage($request)
    {
        if (!$request->hasFile('photo')) {
            return null;
        }
        $image = $request->file('photo');
        //Validate the image
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // Get the file extension
        $extension = $image->getClientOriginalExtension();
        // Generate a unique name for the image
        $imageName = uniqid() . '.' . $extension;
        // Move the image to the public directory
        $image->move(public_path('images'), $imageName);
        // Return the image name
        return $imageName;
    }
}//end class
