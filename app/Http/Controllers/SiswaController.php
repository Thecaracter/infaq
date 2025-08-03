<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['kelas', 'orangTua', 'tahunAjaran'])
            ->latest()
            ->paginate(10);

        $kelas = Kelas::where('is_active', true)
            ->with('tahunAjaran')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        $tahunAjarans = TahunAjaran::orderBy('is_active', 'desc')
            ->orderBy('nama_tahun', 'desc')
            ->get();

        return view('pages.admin.siswa', compact('siswas', 'kelas', 'tahunAjarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:255|unique:siswas,nis',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'nama_wali' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat_ortu' => 'required|string',
            'pekerjaan' => 'nullable|string|max:255',
            'hubungan' => 'required|in:ayah,ibu,wali',
            'is_active' => 'nullable|boolean'
        ], [
            'nis.required' => 'NIS harus diisi.',
            'nis.unique' => 'NIS sudah digunakan.',
            'nama_lengkap.required' => 'Nama lengkap harus diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'alamat.required' => 'Alamat siswa harus diisi.',
            'kelas_id.required' => 'Kelas harus dipilih.',
            'kelas_id.exists' => 'Kelas tidak valid.',
            'tahun_ajaran_id.required' => 'Tahun ajaran harus dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak valid.',
            'nama_wali.required' => 'Nama wali harus diisi.',
            'no_hp.required' => 'Nomor HP harus diisi.',
            'alamat_ortu.required' => 'Alamat orang tua harus diisi.',
            'hubungan.required' => 'Hubungan dengan siswa harus dipilih.',
            'hubungan.in' => 'Hubungan tidak valid.',
        ]);

        DB::beginTransaction();
        try {

            $orangTua = OrangTua::create([
                'nama_wali' => $request->nama_wali,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat_ortu,
                'pekerjaan' => $request->pekerjaan,
                'hubungan' => $request->hubungan,
            ]);


            Siswa::create([
                'nis' => $request->nis,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'kelas_id' => $request->kelas_id,
                'orang_tua_id' => $orangTua->id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'is_active' => $request->boolean('is_active', true)
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan siswa. ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'orangTua', 'tahunAjaran']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $siswa->id,
                'nis' => $siswa->nis,
                'nama_lengkap' => $siswa->nama_lengkap,
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'tanggal_lahir' => $siswa->tanggal_lahir->format('Y-m-d'),
                'alamat' => $siswa->alamat,
                'kelas_id' => $siswa->kelas_id,
                'tahun_ajaran_id' => $siswa->tahun_ajaran_id,
                'is_active' => $siswa->is_active,

                'nama_wali' => $siswa->orangTua->nama_wali,
                'no_hp' => $siswa->orangTua->no_hp,
                'alamat_ortu' => $siswa->orangTua->alamat,
                'pekerjaan' => $siswa->orangTua->pekerjaan,
                'hubungan' => $siswa->orangTua->hubungan,
            ]
        ]);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => [
                'required',
                'string',
                'max:255',
                Rule::unique('siswas', 'nis')->ignore($siswa->id)
            ],
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'nama_wali' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat_ortu' => 'required|string',
            'pekerjaan' => 'nullable|string|max:255',
            'hubungan' => 'required|in:ayah,ibu,wali',
            'is_active' => 'nullable|boolean'
        ], [
            'nis.required' => 'NIS harus diisi.',
            'nis.unique' => 'NIS sudah digunakan.',
            'nama_lengkap.required' => 'Nama lengkap harus diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'alamat.required' => 'Alamat siswa harus diisi.',
            'kelas_id.required' => 'Kelas harus dipilih.',
            'kelas_id.exists' => 'Kelas tidak valid.',
            'tahun_ajaran_id.required' => 'Tahun ajaran harus dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak valid.',
            'nama_wali.required' => 'Nama wali harus diisi.',
            'no_hp.required' => 'Nomor HP harus diisi.',
            'alamat_ortu.required' => 'Alamat orang tua harus diisi.',
            'hubungan.required' => 'Hubungan dengan siswa harus dipilih.',
            'hubungan.in' => 'Hubungan tidak valid.',
        ]);

        DB::beginTransaction();
        try {

            $siswa->orangTua->update([
                'nama_wali' => $request->nama_wali,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat_ortu,
                'pekerjaan' => $request->pekerjaan,
                'hubungan' => $request->hubungan,
            ]);


            $siswa->update([
                'nis' => $request->nis,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'kelas_id' => $request->kelas_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'is_active' => $request->boolean('is_active', true)
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diupdate.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data siswa. ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleActive(Siswa $siswa)
    {
        try {
            $siswa->update(['is_active' => !$siswa->is_active]);

            $message = $siswa->is_active
                ? 'Siswa berhasil diaktifkan.'
                : 'Siswa berhasil dinonaktifkan.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_active' => $siswa->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status siswa. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Siswa $siswa)
    {
        DB::beginTransaction();
        try {

            if ($siswa->transaksiInfaqs()->count() > 0 || $siswa->tunggakans()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak dapat dihapus karena masih memiliki transaksi atau tunggakan.'
                ], 422);
            }

            $orangTuaId = $siswa->orang_tua_id;


            $siswa->delete();


            $otherChildren = Siswa::where('orang_tua_id', $orangTuaId)->count();


            if ($otherChildren === 0) {
                OrangTua::find($orangTuaId)->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus siswa. ' . $e->getMessage()
            ], 500);
        }
    }
}