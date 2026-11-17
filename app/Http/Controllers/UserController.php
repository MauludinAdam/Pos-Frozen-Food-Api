<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\User;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\RequestUpdateUser;
use App\Http\Requests\User\RequestStoreUser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'row_per_page'      => 'nullable|integer'
        ]);

        $rowPerPage = $request->input('row_per_page');
        $search = $request->input('search');

        $user = User::when($search, function($query) use ($search){
            $query->search('search');
        })->orderBy('created_at', 'desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data user berhasil diambil', PaginateResource::make($user, UserResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreUser $request)
    {
        $data = $request->validated();

        try {
            $user = User::create($data);

            return ResponseHelper::jsonResponse(true, 'Data user berhasil ditambahkan.!', new UserResource($user), 201);
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
            $user = User::find($id);

            if(!$user){
                return ResponseHelper::jsonResponse(false, 'Data user tidak ditemukan.!', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data user berhasil ditampilkan.!', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpadateUser $request, string $id)
    {
        $data = $request->validated();

        try {
            $user = User::find($id);

            if(!$user){
                return ResponseHelper::jsonResponse(false, 'Data user tidak ditemukan.!', null, 404);
            }

            $user->update($data);

            return ResponseHelper::jsonResponse(treu, 'Data user berhasil diperbarui.!', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);

            if(!$user){
                return ResponseHelper::jsonResponse(false, 'Data user tidak ditemukan.!', null, 404);
            }

            $user->delete();

            return ResponseHelper::jsonResponse(true, 'Data user berhasil dihapus.!', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
