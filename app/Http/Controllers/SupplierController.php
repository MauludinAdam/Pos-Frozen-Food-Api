<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Supplier;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SupplierResource;
use App\Http\Requests\Supplier\RequestStoreSupplier;
use App\Http\Requests\Supplier\RequestUpdateSupplier;

class SupplierController extends Controller
{
    private function generateNumberSupplier()
    {
        $date = now()->format('Ymd');
        $prefix = 'SP-' . $date . '-';

        $lastSupplier = Supplier::query()
            ->whereDate('created_at', now()->toDateString())
            ->where('code', 'like', $prefix . '%')
            ->orderByDesc('code')
            ->first();

            if($lastSupplier){
                $lastNumber = (int) substr($lastSupplier->code, -4);
                $number = $lastNumber + 1;
            }else{
                $number = 1;
            }

            return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function generateNumber()
    {
        $numberSupplier = $this->generateNumberSupplier();

        return ResponseHelper::jsonResponse(true, 'Nomor Supplier berhasi dibuat', ['code' => $numberSupplier], 200);
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

        $supplier = Supplier::query()->when($search, function($query) use ($search) {
            $query->search('search');
        })->orderBy('created_at', 'desc')->paginate($rowPerPage);

        return ResponseHelper::jsonResponse(true, 'Data supplier berhasil diambli', PaginateResource::make($supplier, SupplierResource::class), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestStoreSupplier $request)
    {
        $data = $request->validated();
        
        try {
            $data['code'] = $this->generateNumberSupplier();

            $supplier = Supplier::create($data);

            return ResponseHelper::jsonResponse(true, 'Data supplier berhasil ditambahkan', new SupplierResource($supplier), 201);
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
            $supplier = Supplier::find($id);

            if(!$supplier){
                return ResponseHelper::jsonResponse(false, 'Data supplier tidak ditemukan.!', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data suppliet berhasil ditampilkan', new SupplierResource($supplier), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpdateSupplier $request, string $id)
    {
        $data = $request->validated();

        try {
            $supplier = Supplier::find($id);

            if(!$supplier){
                return ResponseHelper::jsonResponse(false, 'Data supplier tidak ditemukan.!', null, 404);
            }

            $supplier->update($data);

            return ResponseHelper::jsonResponse(true, 'Data supplier berhasil diperbarui', new SupplierResource($supplier), 200);
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
            $supplier = Supplier::find($id);

            if(!$supplier){
                return ResponseHelper::jsonResponse(false, 'Data supplier tidak ditemukan.!', null, 404);
            }

            $supplier->delete();

            return ResponseHelper::jsonResponse(true, 'Data supplier berhasil dihapus.!', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
