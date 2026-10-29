<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\PurchaseOrder;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\PurchaseOrderResource;
use App\Http\Requests\PurchaseOrder\RequestStorePurchaseOrder;
use App\Http\Requests\PurchaseOrder\RequestUpdatePurchaseOrder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    private function generateNumberPurchaseOrder()
    {
        $date = now()->format('Ymd');
        $prefix = 'PO-' . $date . '-';

        $lastPurchaseOrder = PurchaseOrder::query()
            ->whereDate('created_at', now()->toDateString())
            ->where('code', 'like', $prefix . '%')
            ->orderByDesc('code')
            ->first();

            if($lastPurchaseOrder){
                $lastNumber = (int) substr($lastPurchaseOrder->code, -4);
                $number = $lastNumber + 1;
            }else{
                $number = 1;
            }

            return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function generateNumber()
    {
        $numberPurchaseOrder = $this->generateNumberPurchaseOrder();

        return ResponseHelper::jsonResponse(true, 'Nomor purchase order berhasil di buat', ['code' => $numberPurchaseOrder], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'row_per_page'  => 'nullable|integer'
        ]);

        $rowPerPage = $request->input('row_per_page');
        $search = $request->input('search');

        $purchaseOrder = PurchaseOrder::with([
            'supplier',
            'user',
            'purchaseOrderItems',
        ])->when($search, function($query, $search) {
            $query->search('search');
        })->orderBy('created_at', 'desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data order pembelian berhasil diambil', PaginateResource::make($purchaseOrder, PurchaseOrderResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStorePurchaseOrder $request)
    {
        $data = $request->validated();

        $items = $data['items'];
        unset($data['items']);

        try {
            $purchaseOrder = DB::transaction(function () use ($data, $items) {
                $subtotal = 0;

                foreach ($items as $item){
                    $subtotal += $item['quantity'] * $item['unit_price'];
                }

                $shippingCost = $data['shipping_cost'] ?? 0;

                $purchaseOrder = PurchaseOrder::create([
                    'code'      => $this->generateNumberPurchaseOrder(),
                    'supplier_id' => $data['supplier_id'],
                    'user_id'     => auth()->id(),
                    'order_date'  => $data['order_date'],
                    'expected_date' => $data['expected_date'],
                    'subtotal'      => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'total'         => $subtotal + $shippingCost,
                    'notes'         => $data['notes'] ?? null,  
                ]);

                foreach($items as $item){
                    $purchaseOrder->purchaseOrderItems()->create([
                        'product_id'    => $item['product_id'],
                        'quantity'      => $item['quantity'],
                        'unit_price'    => $item['unit_price'],
                        'subtotal'      => $item['quantity'] * $item['unit_price'],
                    ]);
                }

                return $purchaseOrder;
            });

            $purchaseOrder->load([
                'supplier',
                'purchaseOrderItems.product',
            ]);

            return ResponseHelper::jsonResponse(true, 'Data purchase order berhasil dibuat', new PurchaseOrderResource($purchaseOrder), 201);
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
            $purchaseOrder = PurchaseOrder::find($id);

            if(!$purchaseOrder){
                return ResponseHelper::jsonResponse(false, 'Data purchase order tidak ditemukan.!', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data purchase order berhasil ditampilkan.!', new PurchaseOrderResource($purchaseOrder), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpdatePurchaseOrder $request, string $id)
    {
        $data = $request->validated();

        try {
            $purchaseOrder = PurchaseOrder::find($id);

            if(!$purchaseOrder){
                return ResponseHelper::jsonResponse(false, 'Data purchase order tidak ditemukan.!', null, 404);
            }

            $purchaseOrder->update($data);

            return ResponseHelper::jsonResponse(true, 'Data purchase order berhasil diperbarui', new PurchaseOrderResource($purchaseOrder), 200);
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
            $purchaseOrder = PurchaseOrder::find($id);

            if(!$purchaseOrder){
                return ResponseHelper::jsonResponse(false, 'Data purchase order tidak ditemukan.!', null, 404);
            }

            $purchaseOrder->delete();

            return ResponseHelper::jsonResponse(true, 'Data purchase order berhasil di hapus.!', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
