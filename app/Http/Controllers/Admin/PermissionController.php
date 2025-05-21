<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionCollection;
use Illuminate\Http\Request;
use App\Http\Resources\PermissionResource;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $permissions = Permission::all();
            if ($permissions->isEmpty()) {
                return response()->json(['message' => 'No permissions found'], 404);
            }
            return response()->json([
                'message' => 'Permission index',
                'permissions' => new PermissionCollection(Permission::all())
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch permissions'], 500);
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
                'name' => 'required|unique:permissions,name|string|max:255',

            ]);
            if (Permission::where('name', $request->name)->exists()) {
                return response()->json([
                    'message' => 'Permission already exists',
                    'statusCode' => 401
                ]);
            }
            $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            return response()->json([
                'message' => 'Permission created successfully',
                "permission" =>  PermissionResource::collection(Permission::all()),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'error' => 'Validation failed',
                    'details' => $e->errors()
                ],
                422
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'error' => 'Failed to create permission',
                    'details' => $e->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'sometimes|required|string|max:255|unique:permissions,name,' . $id,
                'guard_name' => 'sometimes|required|string|max:255',
            ]);

            $permission = Permission::findOrFail($id);
            if (!$permission) {
                return response()->json([
                    'message' => "Permission with this ID not found",
                    "statusCode" => 401
                ]);
            }
            if (Permission::where('name', $request->name)->exists()) {
                return response()->json([
                    'message' => 'Permission already exists',
                    'statusCode' => 401
                ]);
            }
            $permission->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'message' => 'Permission updated successfully',
                'permission' => new PermissionResource($permission),
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
            return response()->json(['error' => 'Failed to update permission', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $permission = Permission::find($id);
            if (!$permission) {
                return response()->json([
                    'message' => "Permission wtht this ID not found",
                    "statusCode" => 401
                ]);
            }
            $permission->delete();

            return response()->json([
                'message' => 'Permission deleted successfully',
                'removed_permission' => new PermissionResource($permission),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete permission', 'details' => $e->getMessage()], 500);
        }
    }
}
