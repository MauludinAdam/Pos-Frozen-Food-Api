<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\StockOpname;
use App\Models\Product;
use App\Http\Resources\StockOpnameResource;
use App\Http\Resources\PaginateResource;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StockOpname\RequestStoreStockOpname;

class StockOpnameController extends Controller
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

        $stockOpname = StockOpname::with([
            'product',
            'user',
        ])->when($search, function($query) use ($search) {
            $query->search('search');
        })->orderBy('created_at', 'desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data stock opname berhasil diambil', PaginateResource::make($stockOpname, StockOpnameResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreStockOpname $request)
    {
        $data = $request->validated();

        try {
            $stockOpname = DB::transaction(function () use ($data, $request) {
                
                $product = Product::query()
                    ->lockForUpdate()
                    ->findOrFail($data['product_id']);

                $stokSistem = $product->stock;
    
                $stokFisik = $data['stock_physical'];
    
                $stockOpname = StockOpname::create([
                    'date'              => $data['date'],
                    'product_id'        => $product->id,
                    'stock_system'      => $stokSistem,
                    'stock_physical'    => $stokFisik,
                    'difference'        => $stokFisik - $stokSistem,
                    'user_id'           => $request->user()->id,
                    'notes'             => $data['notes'] ?? null, 
                ]);

                if($stokFisik != $stokSistem){
                    $product->update([
                        'stock' => $stokFisik
                    ]);
                }

                return $stockOpname;
            });

            return ResponseHelper::jsonResponse(true, 'Data stok opname berhasil disimpan.!', new StockOpnameResource($stockOpname), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $stockOpname = StockOpname::find($id);

            if(!$stockOpname){
                return ResponseHelper::jsonResponse(false, 'Data stok opname tidak ditemukan.!', null, 404);
            }

            $stockOpname->delete();

            return ResponseHelper::jsonResponse(true, 'Data stok opname berhasil dihapus.!', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function report(Request $request)
    {
        $data = $request->validate([
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $stockOpnames = StockOpname::with(['product','user'])
                ->whereBetween('date', [
                    $data['start_date'],
                    $data['end_date']
                ])->orderBy('date', 'asc')->get();

                $pdf = Pdf::loadView('pdf.stock-opname', [
                    'stockOpnames'  => $stockOpnames,
                    'startDate'     => $data['start_date'],
                    'endDate'       => $data['end_date'],
                ])->setPaper('a4', 'landscape');

                return $pdf->download('laporan-stok-opname.pdf');
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function reportData(Request $request)
    {
        $data = $request->validate([
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $stockOpnames = StockOpname::with(['product', 'user'])
                ->whereBetween('date', [
                    $data['start_date'],
                    $data['end_date']
                ])->orderBy('date', 'asc')->get();

                return ResponseHelper::jsonResponse(true, 'Data laporan stok opname berhasil diambil', StockOpnameResource::collection($stockOpnames), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
