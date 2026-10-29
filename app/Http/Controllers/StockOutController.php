<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\StockTransaction;
use App\Models\Product;
use App\Http\Resources\StockTransactionResource;
use App\Http\Requests\StockTransaction\RequestStoreStockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockOutController extends Controller
{
    private function genaretNumberTransaction()
    {
        $date = now()->format('Ymd');
        $prefix = 'ST-OUT-' . $date . '-';

        $lastStockTransaction = StockTransaction::query()
            ->where('type', 'Out')
            ->whereDate('created_at', now()->toDateString())
            ->where('number_transaction', 'like', $prefix . '%')
            ->orderByDesc('number_transaction')
            ->first();

            if($lastStockTransaction){
                $lastNumber = (int) substr($lastStockTransaction->number_transaction, -4);
                $number = $lastNumber + 1;
            }else{
                $number = 1;
            }

            return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function genaretNumber()
    {
        $numberTransaction = $this->generatetNumberTransaction();

        return ResponseHelper::jsonResponse(true, 'Nomor transaksi berhasi dibuat', ['number_transaction' => $numberTransaction], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreStockTransaction $request)
    {
        $data = $request->validated();

        $user = $request->user();

        return DB::transaction(function () use ($data, $user) {
        
            try {
                $totalQuantity = array_sum(array_column($data['items'], 'quantity'));

            $stockTransaction = StockTransaction::create([
                'number_transaction'    => $data['number_transaction'],
                'type'                  => 'Out',
                'date'                  => $data['date'],
                'total_quantity'        => $totalQuantity,
                'user_id'               => $user->id,
                'sender_name'           => $data['sender_name'] ?? null,
                'receiver_name'         => $data['receiver_name'] ?? null,
                'phone'                 => $data['phone'] ?? null,
                'description'           => $data['description'] ?? null,
            ]);

            foreach($data['items'] as $item) {

                $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);

                if($product->stock < $item['quantity']){
                    throw ValidationException::withMessages([
                        'items' => "Stock product {$product->name} tidak mencukupi.",
                    ]);
                }

                $stockTransaction->stockTransactionItems()->create([
                    'product_id'        => $item['product_id'],
                    'quantity'          => $item['quantity'],
                    'unit_price'        => 0,
                    'subtotal'          => 0,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            $stockTransaction->load([
                'user',
                'stockTransactionItems',
            ]);

            return ResponseHelper::jsonResponse(true, 'Data barang keluar berhasil ditambahkan', new StockTransactionResource($stockTransaction), 201);
            } catch (ValidationException $e){
                throw $e;

            } catch (\Exception $e) {
                report($e);

               throw $e;
            }

        });
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
