<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\GuardName;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission as SpatiePermission;

class PermissionController extends Controller 
{   
    /**
     * GET api/v1/permissions
     *
     * Get all permissions
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $permissions = SpatiePermission::where('guard_name', GuardName::WEB->value)
            ->get(['id', 'name', 'guard_name']);

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    /**
     * GET api/v1/permissions/{permission}
     *
     * Get permission details
     *
     * @param SpatiePermission $permission
     * @return JsonResponse
     */
    public function show(SpatiePermission $permission): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $permission
        ]);
    }

    /**
     * POST api/v1/permissions
     *
     * Create new permission
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'required|string|in:' . GuardName::WEB->value
        ]);

        $permission = SpatiePermission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully',
            'data' => $permission
        ], 201);
    }

    /**
     * PUT api/v1/permissions/{permission}
     *
     * Update permission
     *
     * @param Request $request
     * @param SpatiePermission $permission
     * @return JsonResponse
     */
    public function update(Request $request, SpatiePermission $permission): JsonResponse
    {
        try {
            // Validate request
            $request->validate([
                'name' => 'required|string|unique:permissions,name,' . $permission->id,
                'guard_name' => 'required|string|in:' . GuardName::WEB->value
            ]);

            $permission->name = $request->name;
            $permission->guard_name = $request->guard_name;
            $permission->save(); 

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully',
                'data' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * DELETE api/v1/permissions/{permission}
     *
     * Delete permission
     *
     * @param SpatiePermission $permission
     * @return JsonResponse
     */
    public function destroy(SpatiePermission $permission): JsonResponse
    {
        try {
            if (!$permission) {
                throw new \Exception("Permission not found");
            }

            $permission->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
