<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Fungsi untuk registrasi admin baru.
     * Menerima 'name', 'email', dan 'password'.
     */
    public function register(Request $request)
    {
        // Validasi input dari request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Di Laravel, kita bisa menggunakan 'role' jika sudah ditambahkan di migrasi
            // Untuk saat ini, semua yang mendaftar adalah admin
        ]);

        // Mengembalikan response sukses
        return response()->json([
            'message' => 'Registrasi berhasil!',
            'user' => $user
        ], 201);
    }

    /**
     * Fungsi untuk login.
     * Menerima 'email' dan 'password'.
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mencoba untuk otentikasi pengguna
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Jika gagal, kirim response error
            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // Jika berhasil, ambil data user
        $user = User::where('email', $request->email)->firstOrFail();

        // Buat token menggunakan Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Kirim response sukses beserta token
        return response()->json([
            'message' => 'Login berhasil!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Fungsi untuk logout.
     */
    public function logout(Request $request)
    {
        // Menghapus token otentikasi saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil!'
        ]);
    }
}