<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $query = User::query();

        // if (!auth()->user()->hasRole('superadmin')) {
        //     $query->where('id', auth()->id());

        try {
            $users = User::with('roles')->get();
            if ($users->isEmpty()) {
                return response()->json(['message' => 'No users found'], 404);
            }
            if (!Auth::user() || !Auth::user()->hasRole('superadmin')) {
                $users = $users->where('id', Auth::id());
            }

            return response()->json(
                [
                    'message' => 'Users retrieved successfully',
                    'status' => 200,
                    'total' => $users->count(),
                    'data' => new UserCollection($users),
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
            ])->assignRole('superadmin');
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
    public function update(UpdateUserRequest $request, User $user)
    {

        try {

            $data = $request->validated();
            //Handel Password
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            //Handel update  photo 
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('images'), $photoName);
                if (!empty($user->photo)) {
                    $oldPhotoPath = public_path('images/' . $user->photo);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                $data['photo'] = $photoName;
            }


            //Update user
            $user->update($data);
            return response()->json(
                [
                    'message' => 'User updated successfully',
                    'status' => 200,
                    'data' => $user->refresh() //new UserResource($user->refresh()),
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
                'message' => 'Failed to update user' . $e->getMessage(),
                'status' => 500,
                'data' => null,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            if ($user) {

                if (!empty($user->photo)) {
                    $oldPhotoPath = public_path('images/' . $user->photo);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                $user->delete();
                return response()->json([
                    'message' => 'User deleted successfully',
                    'status' => 200,
                    'data' => null,
                ]);
            } else if ($user->is_empty()) {
                return response()->json([
                    "message" => "User Not Found",
                    "data" => null,
                    "status" => 404
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
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

    //Assigne Role
    public function assignRoleToUser(Request $request, User $user)
    {
        try {
            $request->validate([
                'roles' => 'required|array|min:1',
                'roles.*' => 'exists:roles,name',
            ]);
            $currentRoles = $user->roles()->pluck('name')->toArray()?:[];
            $newRoles =array_diff($request->roles, $currentRoles);  ;
          if (empty($newRoles)) {
                return response()->json([
                    'message' => 'User already has this role',
                    'status' => 400,
                    'data' => $currentRoles,
                ]);
            }
           
            //Assign the role to the user
            $user->assignRole($newRoles);

            return response()->json([
                'message' => 'Role assigned successfully',
                'status' => 200,
                "exiting_roles" => $currentRoles,
                'new_roles' => $newRoles,
            ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'status' => 422,
                'errors' => $e->validator->errors(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to assign role',
                "error" => $e->getMessage(),
                'status' => 500,
                'data' => null,
            ]);
        }
    }

    //Remove Role from user
    public function removeRolesFromUser(Request $request, User $user)
{
    try {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $roles = $request->roles;
        $removedRoles = [];
        $notFoundRoles = [];

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                $user->removeRole($role);
                $removedRoles[] = $role;
            } else {
                $notFoundRoles[] = $role;
            }
        }

        $message = empty($notFoundRoles) 
            ? 'Roles removed successfully' 
            : 'Some roles were not found on the user: ' . implode(', ', $notFoundRoles);

        return response()->json([
            'message' => $message,
            'status' => 200,
            'removed_roles' => $removedRoles,
            'not_found_roles' => $notFoundRoles
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors(),
            'status' => 422
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to remove roles',
            'error' => $e->getMessage(),
            'status' => 500
        ]);
    }
}


    public function getUserRoles($id)
    {
        try {
            $user = User::select('id', 'full_name', 'email')->find($id);
            $roles = $user->roles()
                ->select('roles.id', 'roles.name')
                ->get();
            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                    'status' => 404,
                    'data' => null,
                ]);
            }
            if ($roles->isEmpty()) {
                return response()->json([
                    'message' => 'No roles found for this user',
                    'status' => 404,
                    'data' => null,
                ]);
            }


            return response()->json([
                'message' => 'User roles retrieved successfully',
                'status' => 200,
                'user' => $user,
                'roles' => $roles,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch user roles' . $e->getMessage(),
                'status' => 500,
                'data' => null,
            ]);
        }
    }
}//end class
