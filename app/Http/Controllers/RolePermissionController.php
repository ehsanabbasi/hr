<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        // Search and filtering
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        
        $usersQuery = User::with('roles', 'permissions');
        
        // Apply search
        if ($search) {
            $usersQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Apply role filter
        if ($roleFilter) {
            $usersQuery->whereHas('roles', function($query) use ($roleFilter) {
                $query->where('id', $roleFilter);
            });
        }
        
        // Paginate results
        $users = $usersQuery->paginate(10)->withQueryString();
        $roles = Role::all();
        
        return view('admin.role-permissions.index', compact('users', 'roles', 'search', 'roleFilter'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        $userPermissions = $user->permissions->pluck('id')->toArray();
        
        return view('admin.role-permissions.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'permissions' => 'array',
        ]);

        // Sync roles
        $user->syncRoles($request->input('roles', []));
        
        // Sync direct permissions
        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.role-permissions.index')
            ->with('success', 'User roles and permissions updated successfully.');
    }
    
    // Role management
    public function roles(Request $request)
    {
        $search = $request->input('search');
        $query = Role::with('permissions');
        
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        
        $roles = $query->paginate(10)->withQueryString();
        $permissions = Permission::all();
        
        return view('admin.role-permissions.roles', compact('roles', 'permissions'));
    }
    
    public function createRole()
    {
        $permissions = Permission::all();
        return view('admin.role-permissions.create-role', compact('permissions'));
    }
    
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
        ]);
        
        DB::transaction(function() use ($request) {
            $role = Role::create(['name' => $request->name]);
            
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
        });
        
        return redirect()->route('admin.role-permissions.roles')
            ->with('success', 'Role created successfully.');
    }
    
    public function editRole(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.role-permissions.edit-role', compact('role', 'permissions', 'rolePermissions'));
    }
    
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);
        
        DB::transaction(function() use ($request, $role) {
            $role->name = $request->name;
            $role->save();
            
            $role->syncPermissions($request->input('permissions', []));
        });
        
        return redirect()->route('admin.role-permissions.roles')
            ->with('success', 'Role updated successfully.');
    }
    
    public function deleteRole(Role $role)
    {
        if($role->name === 'admin') {
            return redirect()->route('admin.role-permissions.roles')
                ->with('error', 'The admin role cannot be deleted.');
        }
        
        $role->delete();
        
        return redirect()->route('admin.role-permissions.roles')
            ->with('success', 'Role deleted successfully.');
    }
    
    // Permission management
    public function permissions(Request $request)
    {
        $search = $request->input('search');
        $query = Permission::with('roles');
        
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        
        $permissions = $query->paginate(10)->withQueryString();
        
        return view('admin.role-permissions.permissions', compact('permissions'));
    }
    
    public function createPermission()
    {
        return view('admin.role-permissions.create-permission');
    }
    
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);
        
        Permission::create(['name' => $request->name]);
        
        return redirect()->route('admin.role-permissions.permissions')
            ->with('success', 'Permission created successfully.');
    }
    
    public function editPermission(Permission $permission)
    {
        return view('admin.role-permissions.edit-permission', compact('permission'));
    }
    
    public function updatePermission(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);
        
        $permission->name = $request->name;
        $permission->save();
        
        return redirect()->route('admin.role-permissions.permissions')
            ->with('success', 'Permission updated successfully.');
    }
    
    public function deletePermission(Permission $permission)
    {
        // Check if permission is being used by any roles
        $usedByRoles = $permission->roles()->count() > 0;
        
        if($usedByRoles) {
            return redirect()->route('admin.role-permissions.permissions')
                ->with('error', 'This permission cannot be deleted as it is being used by one or more roles.');
        }
        
        $permission->delete();
        
        return redirect()->route('admin.role-permissions.permissions')
            ->with('success', 'Permission deleted successfully.');
    }
} 