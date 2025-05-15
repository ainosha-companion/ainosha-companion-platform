<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\GuardName;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\CreateRoleRequest;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * GET api/v1/roles
     *
     * Get all roles
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $roles = Role::all(['id', 'name', 'guard_name']);

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * GET api/v1/roles/{id}
     *
     * Get role details
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    /**
     * POST api/v1/roles
     *
     * Create new role
     *
     * @param CreateRoleRequest $createRoleRequest
     * @return JsonResponse
     */
    public function store(CreateRoleRequest $createRoleRequest): JsonResponse
    {
        try {
            $validated = $createRoleRequest->validated();

            $role = Role::create(
                name: $validated['name'],
                guardName: $validated['guard_name'] ?? GuardName::WEB->value,
                permissions: $validated['permissions'] ?? [],
            );

            if (!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'data' => $role
            ], 201);
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về phản hồi thất bại
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role: ' . $e->getMessage()
            ], 500);
        }
        
    }

    /**
     * PUT api/v1/roles/{id}
     *
     * Update role
     *
     * @param Request $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(Request $request, Role $role): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'guard_name' => 'required|string|in:' . GuardName::WEB->value
        ]);

        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data' => $role
        ]);
    }

    /**
     * DELETE api/v1/roles/{id}
     *
     * Delete role
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }
}
