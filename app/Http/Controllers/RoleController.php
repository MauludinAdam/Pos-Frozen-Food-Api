<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Helpers\ResponseHelper;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = Role::with('permissions')->orderBy('name', 'desc')->get();

        return ResponseHelper::jsonResponse(true, 'Data role berhasil diambil', RoleResource::collection($role), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:roles,name',
        ]);

        try {
            $role = Role::create([
                'name'          => $request->name,
                'guard_name'    => 'web',
            ]);

            return ResponseHelper::jsonResponse(true, 'Role berhasil ditambahkan', $role, 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);

            return ResponseHelper::jsonResponse(true, 'Data role berhasil diambil', new RoleResource($role), 200);
        } catch (ModelNotFoundException $e){
            return ResponseHelper::jsonResponse(false, 'Role tidak ditemukan', null, 404);
        }
        catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:roles,name' . $id, 
        ]);

        try {
            $role = Role::find($id);

            $role->update([
                'name'  => $request->name,
            ]);

            return ResponseHelper::jsonResponse(true, 'Role berhasil diperbarui', $role, 200);
        }catch (\Exception $e){
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = Role::findOrFail($id);

            // Cek apakah role masih digunakan oleh user
            if($role->user()->exists()){
                return ResponseHelper::jsonResponse(false, 'Role masih digunakan oleh user, maka role ini tidak bisa dihapus', null, 422);
            }

            $role->delete();

            return ResponseHelper::jsonResponse(true, 'Role berhasil diperbarui', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function updatePermissions(Request $request, string $id)
    {
        try {
            $request->validate([
                'permissions'    => 'required|array',
                'permissions.*.' => 'exists:permissions, id',
            ]);

            $role = Role::find($id);

            if(!$role){
                return ResponseHelper::jsonResponse(false, 'Role tidak ditemukan', null, 404);
            }

            $role->syncPermissions($request->permissions);

            $role->load('permissions');

            return ResponseHelper::jsonResponse(true, 'Permission berhasil diperbarui', new RoleResource($role), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
