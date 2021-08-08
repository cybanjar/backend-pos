<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function __contruct() {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index() {
        $pengaduan = Pengaduan::paginate(100);
        return response()->json([
            'success'   => true,
            'data'      => $pengaduan
        ]);
    }

    public function store(Request $request) {
        $validator = $request->validate([
            'kategoriPengaduan'     => 'required',
            'deskripsi'             => 'required',
            'gambar'                => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);

        if(!$validator) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors(),
                'message' => 'Pengaduan Failed!'
            ], 401); 
        }

        $pengaduan = Pengaduan::create([
            'user_id' => auth()->user()->id,
            'kategoriPengaduan' => $request->kategoriPengaduan,
            'deskripsi' => $request->deskripsi,
            'gambar' => $request->file('gambar')->store('assets/pengaduan', 'public')
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Successfully',
            'data'      => $pengaduan,
        ], 201);
    }

    public function show($id) {
        $pengaduan = Pengaduan::find($id);
        return response()->json($pengaduan);
    }

    public function update(Request $request, Pengaduan $pengaduan) {
        if(auth()->user()->id !== $pengaduan->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only edit your own pengaduan!'
            ], 403);
        } else {
            $pengaduan->update($request->only(['kategoriPengaduan', 'deskripsi']));
            return response()->json([
                'success'   => true,
                'data'      => $pengaduan
            ], 200);
        }

    }

    public function destroy($id) {
        $pengaduan = Pengaduan::find($id);
        if (!$pengaduan) {
            return response()->json([
                'message' => 'Not found!'
            ]);
        } else {
            if(auth()->user()->id !== $pengaduan->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete!'
                ], 403);
            } else {

                $pengaduan->delete();
                return response()->json([
                    'message' => 'Success delete!'
                ]);
            }
        }
    }
}
