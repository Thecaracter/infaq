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
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

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
                'tanggal_mulai' => \Carbon\Carbon::parse($tahunAjaran->tanggal_mulai)->format('Y-m-d'),
                'tanggal_selesai' => \Carbon\Carbon::parse($tahunAjaran->tanggal_selesai)->format('Y-m-d'),
                'is_active' => $tahunAjaran->is_active
            ]
        ]);
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

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

    public function destroy(TahunAjaran $tahunAjaran)
    {
        try {
            DB::beginTransaction();

            // Nonaktifkan dulu jika masih aktif sebelum di-soft delete
            if ($tahunAjaran->is_active) {
                $tahunAjaran->update(['is_active' => false]);
            }

            // Soft delete the tahun ajaran
            $tahunAjaran->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tahun ajaran berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tahun ajaran. ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $tahunAjaran = TahunAjaran::withTrashed()->findOrFail($id);
            $tahunAjaran->restore();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tahun ajaran berhasil dipulihkan.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan tahun ajaran. ' . $e->getMessage()
            ], 500);
        }
    }

    public function trashed()
    {
        $tahunAjarans = TahunAjaran::onlyTrashed()
            ->latest('deleted_at')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $tahunAjarans
        ]);
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
}