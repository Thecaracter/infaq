<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::latest()->paginate(10);

        return view('pages.admin.tahun-ajaran', compact('tahunAjarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tahun' => 'required|string|max:255|unique:tahun_ajarans,nama_tahun',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'nullable|boolean'
        ], [
            'nama_tahun.required' => 'Nama tahun ajaran harus diisi.',
            'nama_tahun.unique' => 'Tahun ajaran sudah ada.',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        DB::beginTransaction();
        try {
            if ($request->boolean('is_active')) {
                TahunAjaran::where('is_active', true)->update(['is_active' => false]);
            }

            TahunAjaran::create([
                'nama_tahun' => $request->nama_tahun,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'is_active' => $request->boolean('is_active', false)
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tahun ajaran berhasil ditambahkan.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan tahun ajaran. ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(TahunAjaran $tahunAjaran)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $tahunAjaran->id,
                'nama_tahun' => $tahunAjaran->nama_tahun,
                'tanggal_mulai' => $tahunAjaran->tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $tahunAjaran->tanggal_selesai->format('Y-m-d'),
                'is_active' => $tahunAjaran->is_active
            ]
        ]);
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'nama_tahun' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tahun_ajarans', 'nama_tahun')->ignore($tahunAjaran->id)
            ],
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'nullable|boolean'
        ], [
            'nama_tahun.required' => 'Nama tahun ajaran harus diisi.',
            'nama_tahun.unique' => 'Tahun ajaran sudah ada.',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        DB::beginTransaction();
        try {
            if ($request->boolean('is_active') && !$tahunAjaran->is_active) {
                TahunAjaran::where('is_active', true)
                    ->where('id', '!=', $tahunAjaran->id)
                    ->update(['is_active' => false]);
            }

            $tahunAjaran->update([
                'nama_tahun' => $request->nama_tahun,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'is_active' => $request->boolean('is_active', false)
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tahun ajaran berhasil diupdate.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate tahun ajaran. ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleActive(TahunAjaran $tahunAjaran)
    {
        DB::beginTransaction();
        try {
            if (!$tahunAjaran->is_active) {
                TahunAjaran::where('is_active', true)->update(['is_active' => false]);
                $tahunAjaran->update(['is_active' => true]);
                $message = 'Tahun ajaran berhasil diaktifkan.';
            } else {
                $tahunAjaran->update(['is_active' => false]);
                $message = 'Tahun ajaran berhasil dinonaktifkan.';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_active' => $tahunAjaran->fresh()->is_active
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status tahun ajaran. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        try {
            if ($tahunAjaran->siswas()->count() > 0 || $tahunAjaran->kelas()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tahun ajaran tidak dapat dihapus karena masih digunakan oleh data siswa atau kelas.'
                ], 422);
            }

            $tahunAjaran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tahun ajaran berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tahun ajaran. ' . $e->getMessage()
            ], 500);
        }
    }
}