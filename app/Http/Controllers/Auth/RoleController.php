<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function allRoles()
    {
        if (Auth::user()->hasPermissionTo('role.list')) {
            try {
                $roles = Role::orderBy('id', 'DESC')->where('id', '!=', 1)->get();
                return response()->json([
                    'status' => true,
                    'roles' => $roles
                ], 200);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function allPermission()
    {
        if (Auth::user()->hasPermissionTo('permission.list')) {
            try {
                $loggedUserRole = Role::where('id', Auth::user()->roles->pluck('id'))->first();
                return response()->json([
                    'status' => true,
                    'permissions' => $loggedUserRole->permissions->pluck('name')->toArray()
                ], 200);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function getRole($id)
    {
        if (Auth::user()->hasPermissionTo('role.get')) {
            try {
                $role = Role::findOrFail($id);
                $roleHasPermissions = $role->permissions->pluck('name')->toArray();
                $loggedUserRole = Role::where('id', Auth::user()->roles->pluck('id'))->first();

                return response()->json([
                    'status' => true,
                    'role' => $role->name,
                    'roleHasPermissions' => $roleHasPermissions,
                    'permissions' => $loggedUserRole->permissions->pluck('name')->toArray()
                ], 200);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function createRole(Request $request)
    {
        if (Auth::user()->hasPermissionTo('role.create')) {
            try {
                $request->validate([
                    'name' => 'required|unique:roles,name',
                ]);
    
                $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
    
                if (!empty($request->permissions)) {
                    $role->syncPermissions($request->permissions);
                }
    
                return response()->json([
                    'status' => true,
                    'message' => 'Role created successfully'
                ], 200);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function updateRole(Request $request, $id)
    {
        if (Auth::user()->hasPermissionTo('role.update')) {
            try {
                $role = Role::findOrFail($id);
    
                $request->validate([
                    'name' => 'required',
                    'permissions' => 'required',
                ]);
    
                if ($role->name != $request->name) {
                    $request->validate([
                        'name' => 'required|unique:roles,name',
                    ]);
                }
    
                $role->update(['name' => $request->name]);
                if (!empty($request->permissions)) {
                    $role->syncPermissions($request->permissions);
                }
    
                return response()->json([
                    'status' => true,
                    'message' => 'Role updated successfully'
                ], 200);
            } catch (\Throwable $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->hasPermissionTo('role.delete')) {
            try {
                $role = Role::find($id);
                $role->delete();
    
                return response()->json([
                    'status' => true,
                    'message' => 'Role deleted successfully'
                ], 200);
            } catch (\Throwable $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }
}
