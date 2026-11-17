<?php

namespace App\Http\Controllers;

use App\Models\TiketParkir;
use Illuminate\Http\Request;

class TiketParkirController extends Controller
{
    /**
     * Menampilkan semua tiket
     */
    public function index()
    {
        $data = TiketParkir::orderBy('id', 'desc')->get();

        return response()->json($data);
    }


    /**
     * Detail tiket
     */
    public function show($id)
    {
        $tiket = TiketParkir::find($id);

        if (!$tiket) {
            return response()->json([
                'message' => 'Tiket tidak ditemukan.'
            ], 404);
        }

        return response()->json($tiket);
    }


    /**
     * Proses pembayaran tiket
     */
    public function bayar(Request $request, $id)
    {
        $tiket = TiketParkir::find($id);

        if (!$tiket) {
            return response()->json([
                'message' => 'Tiket tidak ditemukan.'
            ], 404);
        }

        // Kalau sudah bayar jangan bisa bayar lagi
        if ($tiket->status_bayar === 'sudah_bayar') {
            return response()->json([
                'message' => 'Tiket ini sudah dibayar.'
            ], 400);
        }

        $request->validate([
            'jumlah_bayar' => [
                'required',
                'numeric',
                'min:0'
            ],
        ], [
            'jumlah_bayar.required' => 'Jumlah pembayaran wajib diisi.',
            'jumlah_bayar.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'jumlah_bayar.min' => 'Jumlah pembayaran tidak boleh negatif.',
        ]);

        $biaya = (float) ($tiket->biaya ?? 0);
        $jumlahBayar = (float) $request->jumlah_bayar;

        if ($biaya <= 0) {
            return response()->json([
                'message' => 'Biaya parkir belum ditentukan.'
            ], 400);
        }

        if ($jumlahBayar < $biaya) {
            return response()->json([
                'message' => 'Uang pembayaran kurang.',
                'biaya' => $biaya,
                'jumlah_bayar' => $jumlahBayar,
                'kurang' => $biaya - $jumlahBayar,
            ], 422);
        }

        $kembalian = $jumlahBayar - $biaya;

        $tiket->update([
            'jumlah_bayar' => $jumlahBayar,
            'kembalian' => $kembalian,
            'status_bayar' => 'sudah_bayar',
            'waktu_bayar' => now(),
        ]);

        return response()->json([
            'message' => 'Pembayaran berhasil.',
            'data' => $tiket->fresh(),
        ]);
    }
}