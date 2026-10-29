<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\DetailTransaction;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\DetailTransactionResource;
use App\Http\Requests\DetailTransaction\RequestStoreDetailTransaction;
use App\Http\Requests\DetailTransaction\RequestUpdateDetailTransaction;

class DetailTransactionController extends Controller
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

        $detailTransaction = DetailTransaction::with([
            'transaction',
            'product',
        ])->when($search, function($query) use ($search) {
            $query->search('search');
        })->orderBy('created_at', 'desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data detail transaksi berhasil diambil', PaginateResource::make($detailTransaction, DetailTransactionResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreDetailTransaction $request)
    {
        $data = $request->validated();

        try {
            $detailTransaction = DetailTransaction::create($data);

            return ResponseHelper::jsonResponse(true, 'Data detail transaksi berhasil ditambahkan.!', new DetailTransactionResource($detailTransaction), 201);
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
            $detailTransaction = DetailTransaction::find($id);

            if(!$detailTransaction){
                return ResponseHelper::jsonResponse(false, 'Data detail transaksi tidak ditemukan.!', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data detail transaksi berhasil ditampilkan', new DetailTransactionResource($detailTransaction), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpdateDetailtransaction $request, string $id)
    {
        $data = $request->validated();

        try {
            $detailTransaction = DetailTransaction::find($id);

        if(!$detailTransaction){
            return ResponseHelper::jsonResponse(false, 'Data detail transaksi tidak ditemukan.!', null, 404);
        }

        $detailTransaction->update($data);
        
        return ResponseHelper::jsonResponse(true, 'Data detail transaksi berhasil diperbarui', new DetailTransactionResource($detailTransaction), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
