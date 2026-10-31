<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\GoodReceipt;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\GoodReceiptResource;
use App\Http\Requests\GoodReceipt\RequestStoreGoodReceipt;
use App\Http\Requests\GoodReceipt\RequestUpdateGoodReceipt;
use Illuminate\Support\Facades\DB;

class GoodReceiptController extends Controller
{
    private function generateGoodsReceiptNumber()
    {
        $date =  now()->format('Ymd');

        $prefix = 'GR-' . $date . '-';

        $lastGoodReceipt = GoodReceipt::query()
            ->whereDate('created_at', now()->toDateString())
            ->where('code', 'like', $prefix . '%')
            ->first();

            if($lastGoodReceipt){
                $lastNumber = (int) substr($lastGoodReceipt->code, -4);
                $number = $lastNumber + 1;
            }else{
                $number = 1;
            }

            return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    } 

    public function generateNumber()
    {
        $numberGoodReceipt = $this->generateGoodsReceiptNumber();

        return ResponseHelper::jsonResponse(true, 'Nomor penerima barang berhasil dibuat', ['code' => $numberGoodReceipt], 200);
    }
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

        $goodsReceipt = GoodReceipt::with([
            'purchaseOrder',
            'user',
            'goodReceiptItems.purchaseOrderItem.product',
        ])->when($search, function($query) use ($search) {
            $query->search('search');
        })->orderBy('created_at', 'desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data penerima barang berhasil ditampilkan.!', PaginateResource::make($goodsReceipt, GoodReceiptResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreGoodReceipt $request)
    {
        $data = $request->validated();

        $items = $data['items'];
        unset($data['items']);

        try {
            $goodsReceipt = DB::transaction(function () use ($data, $items) {
                
            $goodsReceipt = GoodReceipt::create([
                'code'          => $this->generateGoodsReceiptNumber(),
                'purchase_order_id' => $data['purchase_order_id'],
                'user_id'           => auth()->id(),
                'date'              => $data['date'],
                'notes'             => $data['notes'],
            ]);

            foreach($items as $item) {
                $goodsReceipt->goodReceiptItems()->create([
                        'goods_receipt_id'           => $goodsReceipt->id,
                        'purchase_order_item_id'    => $item['purchase_order_item_id'],
                        'quantity'                  => $item['quantity'],
                        'unit_price'                => $item['unit_price']               
                    ]);
                }

                return $goodsReceipt;
            });

            $goodsReceipt->load('goodReceiptItems');

            return ResponseHelper::jsonResponse(true, 'Data penerima barang berhasil ditambahkan.!', new GoodReceiptResource($goodsReceipt), 201);
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
            $goodsReceipt = GoodReceipt::with([
                'purchaseOrder',
                'user',
                'goodReceiptItems.purchaseOrderItem.product',
            ])->find($id);

            if(!$goodsReceipt){
                return ResponseHelper::jsonResponse(false, 'Data penerima barang tidak ditemukan.!', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data penerima barang berhasil ditampilkan', new GoodReceiptResource($goodsReceipt), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpdateGoodReceipt $request, string $id)
    {
        $data = $request->validated();

        $items = $data['items'];
        unset($data['items']);

        try {
           $goodsReceipt = DB::transaction(function () use ($data, $items, $id) {
               $goodsReceipt = GoodReceipt::findOrFail($id);

               $goodsReceipt->update($data);

            //    Hapus item lama
            $goodsReceipt->goodReceiptItems()->delete();

            // Buat ulang item berdasarkan request
            foreach($items as $item){
                $goodsReceipt->goodReceiptItems()->create([
                    'purchase_order_item_id'    => $item['purchase_order_item_id'],
                    'quantity'                  => $item['quantity'],
                    'unit_price'                => $item['unit_price'],
                ]);
            }

            return $goodsReceipt;

           });

        //    Load ulang relasi
        $goodsReceipt->load([
            'purchaseOrder',
            'user',
            'goodReceiptItems.purchaseOrderItem.product',
        ]);
           
            return ResponseHelper::jsonResponse(true, 'Data penerima barang berhasi diperbarui.!', new GoodReceiptResource($goodsReceipt), 200);
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
            $goodsReceipt = GoodReceipt::find($id);

            if(!$goodsReceipt){
                return ResponseHelper::jsonResponse(false, 'Data penerima barang tidak ditemukan.!', null, 404);
            }

            $goodsReceipt->delete();

            return ResponseHelper::jsonResponse(true, 'Data penerima barang berhasil dihapus.!', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
