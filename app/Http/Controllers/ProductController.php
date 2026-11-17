<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Product;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\ProductResource;
use App\Http\Requests\Product\RequestStoreProduct;
use App\Http\Requests\Product\RequestUpdateProduct;


class ProductController extends Controller
{
    private function generateProductNumber()
    {
        $date = now()->format("Ymd");
        $prefix = 'PR-' . $date . '-';

        $lastProduct = Product::query()
            ->whereDate('created_at', now()->toDateString())
            ->where('code', 'like', $prefix . '%')
            ->orderByDesc('code')
            ->first();

            if($lastProduct){
                $lastNumber = (int) substr($lastProduct->code, -4);
                $number = $lastNumber + 1;
            }else{
                $number = 1;
            }

            return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function generateProductCode()
    {
        $numberProduct = $this->generateProductNumber();

        return ResponseHelper::jsonResponse(true, 'Code Product berhasil dibuat', ['code' => $numberProduct], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'row_per_page'  => 'nullable|integer|min:1',
            'search'        => 'nullable|string',
            'category_id'   => 'nullable|exists:categories,id'
        ]);

        $rowPerPage = $request->input('row_per_page', 10);
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $product = Product::with([
            'category',
            'satuan'
            ])->when($search, function($query) use ($search){
                $query->search($search);
            })->when($categoryId, function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })->orderBy('created_at', 'desc')->paginate($rowPerPage);

            return ResponseHelper::jsonResponse(true, 'Data product berhasil diambil', PaginateResource::make($product, ProductResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreProduct $request)
    {
        $data = $request->validated();

        try {
            if($request->hasFile('image')){
                $data['image'] =$request->file('image')->store('products', 'public');
            }
            $product = Product::create($data);

            return ResponseHelper::jsonResponse(true, 'Data product berhasil ditambahkan', new ProductResource($product), 201);
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
            $product = Product::find($id);

            if(!$product){
                return ResponseHelper::jsonResponse(false, 'Data product tidak ditemukan!', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data product berhasil ditampilkan', new ProductResource($product), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpdateproduct $request, string $id)
    {
        $data = $request->validated();

        try {
            $product = Product::find($id);

            if(!$product){
                return ResponseHelper::jsonResponse(false, 'Data product tidak ditemukan', null, 404);
            }

            $product->update($data);

            return ResponseHelper::jsonResponse(true, 'Data product berhasil diupdate', new ProductResource($product), 200);
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
            $product = Product::find($id);

            if(!$product){
                return ResponseHelper::jsonResponse(false, 'Data product tidak ditemukan!', null, 404);
            }

            $product->delete();

            return ResponseHelper::jsonResponse(true, 'Data product berhasil dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
