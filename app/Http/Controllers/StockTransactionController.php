<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\StockTransaction;
use App\Http\Resources\StockTransactionResource;
use App\Http\Resources\PaginateResource;

class StockTransactionController extends Controller
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
        $search     = $request->input('search');

        $stockTransaction = StockTransaction::with([
            'user',
            'stockTransactionItems',
        ])->when($search, function($query) use ($search) {
            $query->search('search');
        })->orderBy('created_at','desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data stock transaction berhasil diambil', PaginateResource::make($stockTransaction, StockTransactionResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $stockTransaction = StockTransaction::find($id);

            if(!$stockTransaction){
                return ResponseHelper::jsonResponse(false, 'Data stock transaksi tidak ditemukan.!', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data stock transaksi berhasil ditampilkan', new StockTransactionResource($stockTransaction), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
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
            $stockTransaction = StockTransaction::find($id);

            if(!$stockTransaction){
                return ResponseHelper::jsonResponse(false, 'Data stock transaksi tidak ditemukan!', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data stock transaksi berhasil dihapus', new StockTransactionResource($stockTransaction), 200);
        } catch (\Exceptio $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
