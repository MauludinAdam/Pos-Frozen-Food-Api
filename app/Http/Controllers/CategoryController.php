<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\CategoryResource;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Category\RequestCategoryStore;
use App\Http\Requests\Category\RequestUpdateCategory;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'row_per_page'  => 'nullable|integer',
        ]);

        $rowPerPage = $request->input('row_per_page');
        $search = $request->input('search');

        $category = Category::when($search, function ($query) use ($search){
            $query->search($search);
        })->orderBy('created_at', 'desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data categori product berhasil diambil', PaginateResource::make($category, CategoryResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestCategoryStore $request)
    {
        $data = $request->validated();

        try {
            $category = Category::create($data);

            return ResponseHelper::jsonResponse(true, 'Data categori berhasil ditambahkan', new CategoryResource($category), 201);
        } catch (\exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = Category::find($id);

            if(!$category) {
                return ResponseHelper::jsonResponse(false, 'Data category tidak ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data kategori berhasil ditampilkan', new CategoryResource($category), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpdateCategory $request, string $id)
    {
        $data = $request->validated();

        try {
            $category = Category::findOrFail($id);

            if(!$category){
                return ResponseHelper::jsonResponse(false, 'Data kategori tidak ditemukan!', null, 404);
            }

            $category->update($data);

            return ResponseHelper::jsonResponse(true, 'Data kategori berhasil diupdate', new CategoryResource($category), 200);
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
            $category = Category::findOrfail($id);

            if(!$category){
                return ResponseHelper::jsonResponse(false, 'Data kategori tidak ditemukan!', null, 404);
            }

            $category->delete();

            return ResponseHelper::jsonResponse(true, 'Data kategori berhasil dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
