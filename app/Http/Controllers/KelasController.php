<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('tahunAjaran')
            ->latest()
            ->paginate(10);

        $tahunAjarans = TahunAjaran::orderBy('is_active', 'desc')
            ->orderBy('nama_tahun', 'desc')
            ->get();

        return view('pages.admin.kelas', compact('kelas', 'tahunAjarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|integer|min:1|max:6',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'is_active' => 'nullable|boolean'
        ], [
            'nama_kelas.required' => 'Nama kelas harus diisi.',
            'tingkat.required' => 'Tingkat kelas harus diisi.',
            'tingkat.integer' => 'Tingkat kelas harus berupa angka.',
            'tingkat.min' => 'Tingkat kelas minimal 1.',
            'tingkat.max' => 'Tingkat kelas maksimal 6.',
            'tahun_ajaran_id.required' => 'Tahun ajaran harus dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak valid.',
        ]);

        // Validasi duplikasi nama kelas dalam tahun ajaran yang sama
        $existingKelas = Kelas::where('nama_kelas', $request->nama_kelas)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->first();

        if ($existingKelas) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'nama_kelas' => ['Nama kelas sudah ada di tahun ajaran ini.']
                ]
            ], 422);
        }

        try {
            Kelas::create([
                'nama_kelas' => $request->nama_kelas,
                'tingkat' => $request->tingkat,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil ditambahkan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kelas. ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Kelas $kelas)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $kelas->id,
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat,
                'tahun_ajaran_id' => $kelas->tahun_ajaran_id,
                'is_active' => $kelas->is_active
            ]
        ]);
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|integer|min:1|max:6',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'is_active' => 'nullable|boolean'
        ], [
            'nama_kelas.required' => 'Nama kelas harus diisi.',
            'tingkat.required' => 'Tingkat kelas harus diisi.',
            'tingkat.integer' => 'Tingkat kelas harus berupa angka.',
            'tingkat.min' => 'Tingkat kelas minimal 1.',
            'tingkat.max' => 'Tingkat kelas maksimal 6.',
            'tahun_ajaran_id.required' => 'Tahun ajaran harus dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak valid.',
        ]);

        // Validasi duplikasi nama kelas dalam tahun ajaran yang sama (kecuali kelas yang sedang di-edit)
        $existingKelas = Kelas::where('nama_kelas', $request->nama_kelas)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->where('id', '!=', $kelas->id)
            ->first();

        if ($existingKelas) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'nama_kelas' => ['Nama kelas sudah ada di tahun ajaran ini.']
                ]
            ], 422);
        }

        try {
            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
                'tingkat' => $request->tingkat,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil diupdate.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate kelas. ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleActive(Kelas $kelas)
    {
        try {
            $kelas->update(['is_active' => !$kelas->is_active]);

            $message = $kelas->is_active
                ? 'Kelas berhasil diaktifkan.'
                : 'Kelas berhasil dinonaktifkan.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_active' => $kelas->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status kelas. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Kelas $kelas)
    {
        try {
            // Cek apakah kelas masih digunakan oleh siswa
            if ($kelas->siswas()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak dapat dihapus karena masih memiliki siswa.'
                ], 422);
            }

            $kelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kelas. ' . $e->getMessage()
            ], 500);
        }
    }
}