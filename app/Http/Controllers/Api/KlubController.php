<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Klub;
use Validator;
use Storage;
use Illuminate\Http\Request;

class KlubController extends Controller
{
    public function index()
    {
        $klub = Klub::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Pemain Sepak bola',
            'data' => $klub, 
        ],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_klub' => 'required',
            'logo' => 'required|image|max:2048',
            'id_liga' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'data tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // upload image
            $path = $request->file('logo')->store('public/logo');
            $klub = new Klub;
            $klub->nama_klub = $request->nama_klub;
            $klub->logo = $path;
            $klub->id_liga = $request->id_liga;
            $klub->save();
            return response()->json([
                'success' => true,
                'message' => 'data berhasil dibuat',
                'data' => $klub,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try{
            $klub = Klub::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Detail Klub',
                'data' => $klub,
            ]);
        } catch (\Exceptoin $e) {
            return response()->json([
                'success' => false,
                'message' => 'data tidak ada',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {
       $klub = Klub::findOrFail($id);
       $validator = Validator::make($request->all(), [
        'nama_klub' => 'required',
        'logo' => 'required|image|max:2048',
        'id_liga' => 'required',
    ]);
    
    if ($validator->fails()){
        return response()->json([
            'success' => false,
            'message' => 'Data tidak valid',
            'errors' => $validator->errors(),
        ],422);
    }

    try {
        $klub = Klub::findOrFail($id);

        if ($request->hasFile('logo')) {
            // delete foto / logo lama
            Storage::delete($klub->logo);
            $path = $request->file('logo')->store('public/logo');
            $klub->logo = $path;
        }
        $klub->nama_klub = $request->nama_klub;
        $klub->id_liga = $request->id_liga;
        $klub->save();
        return response()->json([
            'success' => true,
            'message' => 'Data berhassil diperbarui',
            'data' => $klub,
        ], 200);
    } catch (Exception $e) {
         return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
        ], 500);
    }
} 

   
    public function destroy(string $id)
    {
        try {
            $klub = Klub::findOrFail($id);
            Storage::delete($klub->logo);
            $klub->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data' . $klub->nama_klub . 'berhasil dihapus',
        ], 200);
        } catch (\Exceptoin $e) {
        return response()->json([
            'success' => false,
            'message' => 'data tidak ada',
            'errors' => $e->getMessage(),
        ], 404);
        }
    }
}
