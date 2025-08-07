<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {

        $users = User::where('role', 'tu')->latest()->paginate(10);

        return view('pages.admin.user', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:tu',
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'tu',
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User TU berhasil ditambahkan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan user. ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user)
    {

        if ($user->role !== 'tu') {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function update(Request $request, User $user)
    {

        if ($user->role !== 'tu') {
            return response()->json([
                'success' => false,
                'message' => 'User tidak dapat diedit.'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:tu',
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'tu',
                'is_active' => $request->boolean('is_active', true)
            ];


            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Data user TU berhasil diupdate.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data user. ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleActive(User $user)
    {

        if ($user->role !== 'tu') {
            return response()->json([
                'success' => false,
                'message' => 'Status user ini tidak dapat diubah.'
            ], 403);
        }

        try {
            $user->update(['is_active' => !$user->is_active]);

            $message = $user->is_active
                ? 'User TU berhasil diaktifkan.'
                : 'User TU berhasil dinonaktifkan.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_active' => $user->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status user. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {

            if ($user->role === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'User administrator tidak dapat dihapus.'
                ], 422);
            }


            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus akun sendiri.'
                ], 422);
            }


            if ($user->transaksiInfaqs()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak dapat dihapus karena masih memiliki transaksi.'
                ], 422);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User TU berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user. ' . $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(User $user)
    {
        try {

            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gunakan menu ganti password untuk mengubah password Anda sendiri.'
                ], 422);
            }


            $newPassword = 'password123';

            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset.',
                'new_password' => $newPassword
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password. ' . $e->getMessage()
            ], 500);
        }
    }
}