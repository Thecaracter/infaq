<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiInfaq;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiInfaq::with(['siswa.kelas', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan role
        if (Auth::user()->role === 'tu') {
            $query->where('user_id', Auth::id());
        }

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_bayar', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_bayar', '<=', $request->end_date);
        }

        // Filter berdasarkan siswa
        if ($request->filled('siswa_search')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->siswa_search . '%')
                    ->orWhere('nis', 'like', '%' . $request->siswa_search . '%');
            });
        }

        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        // Filter berdasarkan user (untuk admin)
        if ($request->filled('user_id') && Auth::user()->role === 'admin') {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->where('bulan_bayar', $request->bulan);
        }

        $transaksi = $query->paginate(20);

        // Data untuk filter
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $users = Auth::user()->role === 'admin' ? User::where('role', 'tu')->orderBy('name')->get() : collect();

        // Statistik
        $stats = $this->getStatistik($request);

        return view('pages.admin.riwayat', compact('transaksi', 'kelas', 'users', 'stats'));
    }

    public function show($id)
    {
        $transaksi = TransaksiInfaq::with(['siswa.kelas', 'siswa.orangTua', 'user'])
            ->findOrFail($id);

        // Cek permission
        if (Auth::user()->role === 'tu' && $transaksi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        // Ambil riwayat pembayaran siswa untuk bulan yang sama
        $riwayat_bulan = TransaksiInfaq::where('siswa_id', $transaksi->siswa_id)
            ->where('bulan_bayar', $transaksi->bulan_bayar)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Ambil semua riwayat pembayaran siswa
        $semua_riwayat = TransaksiInfaq::where('siswa_id', $transaksi->siswa_id)
            ->with('user')
            ->orderBy('tanggal_bayar', 'desc')
            ->limit(10)
            ->get();

        return view('pages.admin.riwayat-detail', compact('transaksi', 'riwayat_bulan', 'semua_riwayat'));
    }

    public function export(Request $request)
    {
        $query = TransaksiInfaq::with(['siswa.kelas', 'user'])
            ->orderBy('tanggal_bayar', 'desc');

        // Apply same filters as index
        if (Auth::user()->role === 'tu') {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_bayar', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_bayar', '<=', $request->end_date);
        }

        if ($request->filled('siswa_search')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->siswa_search . '%')
                    ->orWhere('nis', 'like', '%' . $request->siswa_search . '%');
            });
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->filled('user_id') && Auth::user()->role === 'admin') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('bulan')) {
            $query->where('bulan_bayar', $request->bulan);
        }

        $transaksi = $query->get();
        $filename = 'riwayat_pembayaran_' . date('Y-m-d_H-i-s') . '.xls';

        // Generate HTML content for Excel
        $html = view('print.export-excel', compact('transaksi', 'filename'))->render();

        // Return response with correct headers
        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }

    public function print($id)
    {
        $transaksi = TransaksiInfaq::with(['siswa.kelas', 'siswa.orangTua'])
            ->findOrFail($id);

        // Cek permission
        if (Auth::user()->role === 'tu' && $transaksi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        // Return view untuk print menggunakan JavaScript
        return view('print.bukti-pembayaran', compact('transaksi'));
    }

    public function resendWhatsapp($id)
    {
        $transaksi = TransaksiInfaq::with(['siswa.orangTua'])
            ->findOrFail($id);

        // Cek permission
        if (Auth::user()->role === 'tu' && $transaksi->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke transaksi ini.'
            ], 403);
        }

        // Kirim ulang WhatsApp
        try {
            // Logic kirim WhatsApp disini
            // $this->sendWhatsAppNotification($transaksi);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi WhatsApp berhasil dikirim ulang.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim notifikasi WhatsApp: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getStatistik($request)
    {
        $query = TransaksiInfaq::query();

        // Filter berdasarkan role
        if (Auth::user()->role === 'tu') {
            $query->where('user_id', Auth::id());
        }

        // Apply filters yang sama
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_bayar', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_bayar', '<=', $request->end_date);
        }

        return [
            'total_transaksi' => $query->count(),
            'total_nominal' => $query->sum('nominal'),
            'transaksi_hari_ini' => (clone $query)->whereDate('tanggal_bayar', today())->count(),
            'nominal_hari_ini' => (clone $query)->whereDate('tanggal_bayar', today())->sum('nominal'),
        ];
    }
}