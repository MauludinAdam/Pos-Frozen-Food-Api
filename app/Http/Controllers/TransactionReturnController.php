<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\TransactionReturn;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\TransactionReturnResource;
use App\Http\Requests\TransactionReturn\RequestStoreTransactionReturn;

class TransactionReturnController extends Controller
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

        $transactionReturn = TransactionReturn::when($search, function ($query) use ($search) {
            $query->search('search');
        })->orderBy('created_at','desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data transaksi return berhasil ditampilkan', PaginateResource::make($transactionReturn, TransactionReturnResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreTransactionReturn $request)
    {
        $data = $request->validated();

        try {
            $transactionReturn = TransactionReturn::create($data);

            return ResponseHelper::jsonResponse(true, 'Data transaksi berhasil.!', new TransactionReturnResource($transactionReturn), 201);
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
        //
    }
}
