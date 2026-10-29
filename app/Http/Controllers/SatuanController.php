<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Satuan;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SatuanResource;
use App\Http\Requests\Satuan\RequestStoreSatuan;
use App\Http\Requests\Satuan\RequestUpdateSatuan;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'row_per_page'      => 'nullable|integer',
        ]);

        $rowPerPage = $request->input('row_per_page');
        $search = $request->input('search');

        $satuan = Satuan::when($search, function($query) use ($search){
            $query->search('search');
        })->orderBy('created_at', 'desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data satuan berhasil diambil', PaginateResource::make($satuan, SatuanResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreSatuan $request)
    {
        $data = $request->validated();

        try {
            $satuan = Satuan::create($data);

            return ResponseHelper::jsonResponse(true, 'Data satuan berhasil ditambahkan', new SatuanResource($satuan), 201);
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
            $satuan = Satuan::find($id);

            if(!$satuan){
                return ResponseHelper::jsonResponse(false, 'Data satuan tidak ditemukan!', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data satuan berhasil ditampilkan', new SatuanResource($satuan), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpdateSatuan $request, string $id)
    {
        $data = $request->validated();

        try {
            $satuan = Satuan::find($id);

            if(!$satuan){
                return ResponseHelper::jsonResponse(false, 'Data satuan tidak ditemukan!', null, 404);
            }

            $satuan->update($data);

            return ResponseHelper::jsonResponse(true, 'Data satuan berhasil diupdate', new SatuanResource($satuan), 200);
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
            $satuan = Satuan::find($id);

            if(!$satuan){
                return ResponseHelper::jsonResponse(false, 'Data satuan tidak ditemukan!', null, 404);
            }

            $satuan->delete();

            return ResponseHelper::jsonResponse(true, 'Data satuan berhasil dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
