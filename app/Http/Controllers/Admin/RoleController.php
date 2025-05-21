<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RoleCollection;
use App\Http\Resources\Admin\RoleResource;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Str;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roles = Role::all();
            if ($roles->isEmpty()) {
                return response()->json(['message' => 'No roles found'], 404);
            }

            return response()->json([
                'message' => 'Roles retrieved successfully',
                'status' => 200,

                'data' => new RoleCollection($roles),

            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch roles'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $role = Role::create([
                'name' => $request->name,
                'guard_name' => "web",
            ]);

            return response()->json([
                'message' => 'Role created successfully',
                // 'role' => new RoleCollection($role)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create role' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                return response()->json(['message' => 'Role not found'], 404);
            }
            $role->permissions;
            return response()->json([
                'message' => 'Role fetched successfully',
                //'role' => new RoleResource($role),
                'role' => (new RoleResource($role)),

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch role' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $id,
            ]);

            $role = Role::findOrFail($id);
            if (!$role) {
                return response()->json(['message' => 'Role not found'], 404);
            }

            $role->update([
                'name' => $request->name,
                'guard_name' => "web",
            ]);

            return response()->json([
                'message' => 'Role updated successfully',
                'role' => new RoleResource($role)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update role' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $role = Role::find($id);
            if (empty($role)) {
                return response()->json(['message' => 'Role not found'], 404);
            }
            $role->delete();
            return response()->json([
                'message' => 'Role deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete role' . $e->getMessage()], 500);
        }
    }



    ///========================================Synic Permissions To Role========================


    public function syncPermissions(Request $request, Role $role)
    {
        try {
            $request->validate([
                'permissions' => 'required|array|min:1',
                'permissions.*' => 'exists:permissions,name',
            ]);
            if (!$role) {
                return response()->json(['message' => 'Role not found'], 404);
            }
            $requestedPermissions = $request->permissions;
            $validPermissions = Permission::whereIn('name', $requestedPermissions)->pluck('name')->toArray();
            if (empty($validPermissions)) {
                return response()->json(['message' => 'No valid permissions found to sync'], 400);
            }
            $existingPermissions = $role->permissions()->pluck('name')->toArray() ?: [];
            $newPermissions = array_diff($validPermissions, $existingPermissions);
            if (empty($newPermissions)) {
                return response()->json([
                    'message' => 'No new permissions to sync',
                    'existing_permissions' => $existingPermissions,
                    'new_permissions' => []
                ], 404);
            }

            // Sync permissions with the role
            $role->syncPermissions($validPermissions);

            return response()->json([
                'message' => 'Permissions synced successfully',
                'existing_permissions' => $existingPermissions,
                'new_permissions' => $newPermissions
            ]);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'error' => 'Validation failed',
                    'details' => $e->errors()
                ],
                422
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to sync permissions' . $e->getMessage()], 500);
        }
    }

    //Delete The ppermissins which  signed  to role
    public function removePermissionsFromRole(Request $request, Role $role)
    {
        try {
            $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,name',
            ]);
            if (!$role) {
                return response()->json(['message' => 'Role not found'], 404);
            }
            if ($role->permissions->isEmpty()) {
                return response()->json(['message' => 'No permissions found'], 404);
            }
            // Sync permissions with the role
            $role->revokePermissionTo($request->permissions);

            return response()->json([
                'message' => 'Permissions removed successfully',
                'data' => $role->permissions
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove permissions' . $e->getMessage()], 500);
        }
    }
}//End Class..
