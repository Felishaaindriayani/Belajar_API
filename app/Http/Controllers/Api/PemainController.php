<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemain;
use Validator;
use Storage;

class PemainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemain = Pemain::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar pemain',
            'data' => $pemain, 
        ],200);
    }


    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pemain' => 'required',
            'foto' => 'required|image|mimes:png,jpg',
            'tgl_lahir' => 'required',
            'harga_pasar' => 'required|numeric',
            'posisi' => 'required|in:gk,df,mf,fw',
            'id_klub' => 'required',
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
            $path = $request->file('foto')->store('public/foto');
            $pemain = new Pemain;
            $pemain->nama_pemain = $request->nama_pemain;
            $pemain->foto = $path;
            $pemain->tgl_lahir = $request->tgl_lahir;
            $pemain->harga_pasar = $request->harga_pasar;
            $pemain->posisi = $request->posisi;
            $pemain->negara = $request->negara;
            $pemain->id_klub = $request->id_klub;
            $pemain->save();
            return response()->json([
                'success' => true,
                'message' => 'data berhasil dibuat',
                'data' => $pemain,
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
            $pemain = Pemain::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Detail pemain',
                'data' => $pemain,
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
        $validator = Validator::make($request->all(), [
            'nama_pemain' => 'required',
            'foto' => 'required|image|mimes:png,jpg',
            'tgl_lahir' => 'required',
            'harga_pasar' => 'required|numeric',
            'posisi' => 'required|in:gk,df,mf,fw',
            'id_klub' => 'required',
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
            $path = $request->file('foto')->store('public/foto');
            $pemain = Pemain::findOrFail($id);
            $pemain->nama_pemain = $request->nama_pemain;
            $pemain->foto = $path;
            $pemain->tgl_lahir = $request->tgl_lahir;
            $pemain->harga_pasar = $request->harga_pasar;
            $pemain->posisi = $request->posisi;
            $pemain->negara = $request->negara;
            $pemain->id_klub = $request->id_klub;
            $pemain->save();
            return response()->json([
                'success' => true,
                'message' => 'data berhasil dibuat',
                'data' => $pemain,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pemain = pemain::findOrFail($id);
            Storage::delete($pemain->foto);
            $pemain->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data' . $pemain->nama_pemain . 'berhasil dihapus',
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
