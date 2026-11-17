<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\StockTransaction;
use App\Models\StockTransactionItem;
use App\Models\Product;
use App\Http\Resources\StockTransactionResource;
use App\Http\Resources\PaginateResource;
use App\Http\Requests\StockTransaction\RequestStoreStockTransaction;
use Illuminate\Support\Facades\DB;

class StockTransactionController extends Controller
{
    private function generateNumberTransaction() 
    {
        $date = now()->format("Ymd");
        $prefix = 'TR-' . $date . '-';

        $lastTransaction = StockTransaction::query()
            ->whereDate('created_at', now()->toDateString())
            ->where('number_transaction', 'like', $prefix . '%')
            ->orderByDesc('number_transaction')
            ->first();

            if($lastTransaction) {
                $lastNumber = (int) substr($lastTransaction->number_transaction, -4);
                $number = $lastNumber + 1;
            }else {
                $number = 1;
            }

            return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function generateTransactionNumber()
    {
        $numberTransaction = $this->generateNumberTransaction();

        return ResponseHelper::jsonResponse(true, 'Nomor transaksi berhasil dibuat', ['number_transaction' => $numberTransaction], 200);
    }
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
            'transaction',
            'stockTransactionItems',
        ])->when($search, function($query) use ($search) {
            $query->search($search);
        })->orderBy('created_at','desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data stock transaction berhasil diambil', PaginateResource::make($stockTransaction, StockTransactionResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreStockTransaction $request)
    {
        $data = $request->validated();

        try {
            $stockTransaction = DB::transaction(function () use ($data) {
                
            // Generate nomor transaksi
            $numberTransaction = $this->generateNumberTransaction();

            // Hitung total quantity
            $totalQuantity = collect($data['items'])
                ->sum('quantity');

                // Simpan Stock Transaction
                $stockTransaction = StockTransaction::create([
                    'number_transaction'    => $numberTransaction,
                    'type'                  => 'In',
                    'date'                  => $data['date'],
                    'total_quantity'        => $totalQuantity,
                    'transaction_id'        => null,
                    'sender_name'           => $data['sender_name'] ?? null,
                    'user_id'               => auth()->id(),
                    'receiver_name'         => auth()->user()->name,
                    'phone'                 => $data['phone'] ?? null,
                    'description'           => $data['description'] ?? null,
                ]);

                // Simpan detail transaksi
                foreach($data['items'] as $item) {
                    $product = Product::query()
                        ->lockForUpdate()
                        ->findOrFail($item['product_id']);

                    $quantity = $item['quantity'];
                    $unitPrice = $item['unit_price'];

                    $subtotal = $quantity * $unitPrice;

                    StockTransactionItem::create([
                        'stock_transaction_id'  => $stockTransaction->id,
                        'product_id'            => $product->id,
                        'quantity'              => $quantity,
                        'unit_price'            => $unitPrice,
                        'subtotal'              => $subtotal,
                    ]);

                    // Tambahkan stock product
                    $product->increment('stock', $quantity);
                }

                return $stockTransaction;
            });

            $stockTransaction->load([
                'user',
                'transaction',
                'stockTransactionItems.product'
            ]);

            return ResponseHelper::jsonResponse(true, 'Data stok transaksi berhasil disimpan', new StockTransactionResource($stockTransaction), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data stok transaksi gagal disimpan'  . $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $stockTransaction = StockTransaction::with([
                'user',
                'transaction',
                'stockTransactionItems.product'
            ])->find($id);

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
