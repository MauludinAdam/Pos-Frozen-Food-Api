<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Setting;
use App\Http\Resources\SettingResource;
use App\Http\Requests\Setting\RequestUpdateSetting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = Setting::first();

        return ResponseHelper::jsonResponse(true, 'Data pengaturan berhasil diambil', new SettingResource($setting), 200);
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
        $setting = Setting::findOrfail($id);

        return ResponseHelper::jsonResponse(true, 'Pengaturan aplikasi berhasil ditampilkan', new SettingResource($setting), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpdateSetting $request, string $id)
    {
        try {
            $data = $request->validate();

            $setting = Setting::firstOrFail();

            if($request->hasFile('logo')){

            // Hapus Logo Lama
                if($setting->logo && Storage::disk('public')->exists($setting->logo)){
                Storage::disk('public')->delete($setting->logo);
                }

                // Simpan Logo baru
                $file = $request->file('logo');
                $fileName = $file->getClientOriginalName();

                $data['logo'] = $file->storeAs('setting', $fileName, 'public');
            }else{
                unset($data['logo']);
            }

            $setting->update($data);

            return ResponseHelper::jsonResponse(true, 'Pengaturan profile aplikasi berhasil diperbarui', new SettingResource($setting), 200);
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
