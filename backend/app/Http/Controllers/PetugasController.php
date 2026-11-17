<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    /**
     * =========================================================
     * TAMPILKAN SEMUA PETUGAS
     * GET /api/petugas
     * =========================================================
     */
    public function index()
    {
        $petugas = User::where('role', 'petugas')
            ->orderBy('id', 'desc')
            ->get([
                'id',
                'name',
                'username',
                'email',
                'no_telepon',
                'role',
                'status',
                'created_at',
                'updated_at',
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Data petugas berhasil diambil',
            'data' => $petugas,
        ]);
    }


    /**
     * =========================================================
     * TAMBAH PETUGAS
     * POST /api/petugas
     * =========================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                'unique:users,username',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'no_telepon' => [
                'nullable',
                'string',
                'max:20',
                'unique:users,no_telepon',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | STATUS OTOMATIS
        |--------------------------------------------------------------------------
        | Tidak perlu dikirim dari frontend.
        | Setiap petugas baru otomatis aktif.
        |--------------------------------------------------------------------------
        */

        $petugas = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'no_telepon' => $validated['no_telepon'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Petugas berhasil ditambahkan',
            'data' => [
                'id' => $petugas->id,
                'name' => $petugas->name,
                'username' => $petugas->username,
                'email' => $petugas->email,
                'no_telepon' => $petugas->no_telepon,
                'role' => $petugas->role,
                'status' => $petugas->status,
            ],
        ], 201);
    }


    /**
     * =========================================================
     * DETAIL PETUGAS
     * GET /api/petugas/{id}
     * =========================================================
     */
    public function show($id)
    {
        $petugas = User::where('role', 'petugas')
            ->find($id);


        if (!$petugas) {
            return response()->json([
                'success' => false,
                'message' => 'Petugas tidak ditemukan',
            ], 404);
        }


        return response()->json([
            'success' => true,
            'message' => 'Data petugas berhasil diambil',
            'data' => [
                'id' => $petugas->id,
                'name' => $petugas->name,
                'username' => $petugas->username,
                'email' => $petugas->email,
                'no_telepon' => $petugas->no_telepon,
                'role' => $petugas->role,
                'status' => $petugas->status,
                'created_at' => $petugas->created_at,
                'updated_at' => $petugas->updated_at,
            ],
        ]);
    }


    /**
     * =========================================================
     * UPDATE PETUGAS
     * PUT /api/petugas/{id}
     * =========================================================
     */
    public function update(Request $request, $id)
    {
        $petugas = User::where('role', 'petugas')
            ->find($id);


        if (!$petugas) {
            return response()->json([
                'success' => false,
                'message' => 'Petugas tidak ditemukan',
            ], 404);
        }


        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')
                    ->ignore($petugas->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($petugas->id),
            ],

            'no_telepon' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'no_telepon')
                    ->ignore($petugas->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $petugas->name =
            $validated['name'];

        $petugas->username =
            $validated['username'];

        $petugas->email =
            $validated['email'];

        $petugas->no_telepon =
            $validated['no_telepon'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        | Password hanya diubah kalau user mengisinya.
        |--------------------------------------------------------------------------
        */

        if (
            isset($validated['password']) &&
            !empty($validated['password'])
        ) {
            $petugas->password =
                Hash::make(
                    $validated['password']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        | Status tidak lagi berasal dari frontend.
        | Status petugas yang sudah ada tetap dipertahankan.
        |--------------------------------------------------------------------------
        */

        if (empty($petugas->status)) {
            $petugas->status = 'aktif';
        }


        $petugas->save();


        return response()->json([
            'success' => true,
            'message' => 'Petugas berhasil diperbarui',
            'data' => [
                'id' => $petugas->id,
                'name' => $petugas->name,
                'username' => $petugas->username,
                'email' => $petugas->email,
                'no_telepon' => $petugas->no_telepon,
                'role' => $petugas->role,
                'status' => $petugas->status,
            ],
        ]);
    }


    /**
     * =========================================================
     * HAPUS PETUGAS
     * DELETE /api/petugas/{id}
     * =========================================================
     */
    public function destroy($id)
    {
        $petugas = User::where('role', 'petugas')
            ->find($id);


        if (!$petugas) {
            return response()->json([
                'success' => false,
                'message' => 'Petugas tidak ditemukan',
            ], 404);
        }


        $petugas->delete();


        return response()->json([
            'success' => true,
            'message' => 'Petugas berhasil dihapus',
        ]);
    }
}