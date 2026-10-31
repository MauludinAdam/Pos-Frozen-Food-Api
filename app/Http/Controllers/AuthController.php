<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(LoginRequest $request)
    {
       if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
       }

       $user = Auth::user();

    //    load relasi role and roles untuk userResource
    $user->load('role','roles');

    $token = $user->createToken('auth_token')->plainTextToken;

    return ResponseHelper::jsonResponse(true, 'Login berhasil', 
        [
            'token' => $token,
            'data'  => new userResource($user),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message'   => 'logout berhasil',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function me(Request $request)
    {
        $user = $request->user()->load('roles');

        return ResponseHelper::jsonResponse(true, 'Data profile user berhasil diambil', new UserResource($user), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestUpdateUser $request, string $id)
    {
        try {
            $data = $request->validated();

            $user = $request->user()->load('role');

            if(empty($data['password'])){
                unset($data['password']);
            }else{
                $data['password'] = Hash::make($data['password']);
            }

            unset(
                $data['current_password'],
                $data['password_confirmation']
            );

            $user->update($data);

            return ResponseHelper::jsonResponse(true, 'Data profile user berhasil diperbarui', new UserResource($user), 200);
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
