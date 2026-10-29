<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Transaction;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\Transaction\RequestStoreTransaction;
use App\Http\Requests\Transaction\RequestUpdateTransaction;

class TransactionController extends Controller
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

        $transaction = Transaction::with([
            'user'
        ])->when($search, function($query) use ($search){
            $query->search('search');
        })->orderBy('created_at', 'desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data transaksi berhasil diambil', PaginateResource::make($transaction, TransactionResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreTransaction $request)
    {
        $data = $request->validated();

        $items = $data['items'];
        unset($data['items']);

        try {
            $transaction = DB::transaction(function () use ($data, $items) {

                // Menggabungkan product yang sama agar stok tidak lolos
                // validasi jika product_id dikirmlebih dari sekali.
                $quantities = [];

                foreach($items as $item){
                    $productId = $item['product_id'];
                    $quantity = (float) $item['quantity'];

                    $quantities[$productId] = ($quantities[$productId] ?? 0) + $quantity;
                }

                // kunci dan periksa stok setiap produk.
                $products = [];
                
                foreach($quantities as $productId => $quantity) {
                    $product = Product::whereKey($productId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if($product->stock < $quantity) {
                        throw ValidationException::withMessages([
                            'items' => ["Stok {$product->name} tidak mencukupi."],
                        ]);
                    }

                    $products[$productId] = $product;   
                }

                // Hitung subtotal berdasarkan harga didatabase.
                $subtotal = 0;
                
                foreach($items as $item) {
                    $product = $products[$item['product_id']];

                    $subtotal += $product->selling_price * $item['quantity'];
                }

                $discount = (float) ($data['discount_amount'] ?? 0);

                if($discount > $subtotal) {
                    throw ValidationException::withMessages(['discount_amount' => [
                        'Diskon tidak boleh melebihi subtotal.'
                        ],
                    ]);
                }

                $total = $subtotal - $discount;
                $paid = (float) $data['paid_amount'];

                if($data['status_payment'] !== 'paid'){
                    throw ValidationException::withMessages([
                        'status_payment' => [
                            'Transaksi harus dibayar lunas saat checkout.'
                        ],
                    ]);
                }

                if($paid < $total) {
                    throw ValidationException::withMessages([
                        'paid_amount' => [
                            'Pembayaran kurang dari total transaksi.'
                        ],
                    ]);
                }

                // Simpat Transaksi Utama
                $transaction = Transaction::create([
                    'user_id'           => $data['user_id'],
                    'subtotal'          => $subtotal,
                    'discount_amount'   => $discount,
                    'total_amount'      => $total,
                    'paid_amount'       => $paid,
                    'change_amount'     => $paid - $total,
                    'status_payment'    => 'paid',
                    'notes'             => $data['notes'] ?? null,
                ]);

                // Simpan detail transaksi dan kurangi stok
                foreach ($items as $item) {
                    $product = $products[$item['product_id']];
                    $quantity = (float) $item['quantity'];

                    DetailTransaction::create([
                        'transaction_id'    => $transaction->id,
                        'product_id'        => $product->id,
                        'quantity'          => $quantity,
                        'unit_price'        => $product->selling_price,
                        'subtotal'          => $subtotal,
                    ]);
                }

                foreach ($quantities as $productId => $quantity) {
                    $products[$productId]->decrement('stock', $quantity);
                }

                return $transaction;
            });

            return ResponseHelper::jsonResponse(true, 'Transaksi berhasil disimpan', new TransactionResource($transaction->load('detailTransactions')), 201);
        } catch (ValidationException $e) {
            throw $e;
        }catch (\Exception $e) {
            report($e);

            return ResponseHelper::jsonResponse(false, 'Transakasi gagal diproses', null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $transaction = Transaction::find($id);

            if(!$transaction){
                return ResponseHelper::jsonResponse(false, 'Data transaksi tidak ditemukan.!', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data transaksi berhasil ditampilkan.!', new TransactionResource($transaction), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpdateTransaction $request, string $id)
    {
        $data = $request->validated();

        try {
            $transaction = Transaction::find($id);

            if(!$transaction){
                return ResponseHelper::jsonResponse(false, 'Data transaksi tidak ditemukan.!', null, 404);
            }

            $transaction->update($data);

            return ResponseHelper::jsonResponse(true, 'Data transaksi berhasil diperbarui.!', new TransactionResource($transaction), 200);
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
            $transaction = Transaction::find($id);

            if(!$transaction){
                return ResponseHelper::jsonResponse(false, 'Data transaksi tidak ditemukan.!', null, 404);
            }

            $transaction->delete();

            return ResponseHelper::jsonResponse(true, 'Data transaksi berhasil dihapus', new TransactionResource($transaction), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
