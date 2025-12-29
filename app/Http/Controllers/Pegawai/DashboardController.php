<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kehadiran;
use App\Models\PotonganGaji;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // Ambil user login → relasi ke karyawan
        $user = Auth::user();
        $karyawan = $user->karyawan;

        // Jika data karyawan belum ada atau jabatan belum di-set, tetap kirim variabel ke view
        if (!$karyawan || !$karyawan->jabatan) {
            return view('pegawai.dashboard', [
                'karyawan' => $karyawan,  // bisa null, tidak error
                'dataGaji' => null,
                'jumlahHadir' => 0,
                'jumlahSakit' => 0,
                'jumlahAlpha' => 0,
            ]);
        }

        // =========================
        // 1. REKAP ABSENSI BULAN INI
        // =========================
        $rekapKehadiran = Kehadiran::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->get();

        $jumlahHadir = $rekapKehadiran->where('status_kehadiran', 'Hadir')->count();
        $jumlahSakit = $rekapKehadiran->where('status_kehadiran', 'Sakit')->count();
        $jumlahAlpha = $rekapKehadiran->where('status_kehadiran', 'Alpha')->count();

        // =========================
        // 2. HITUNG GAJI BULAN INI
        // =========================
        $potonganAlphaSetting = 50000; // potongan per hari alpha
        $totalPotonganAlpha = $jumlahAlpha * $potonganAlphaSetting;

        $potonganLainnya = PotonganGaji::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah');

        $totalSemuaPotongan = $totalPotonganAlpha + $potonganLainnya;

        // Gaji
        $gajiPokok = $karyawan->jabatan->gaji_pokok;
        $tunjanganTransport = $karyawan->jabatan->tunjangan_transport;
        $uangMakan = $karyawan->jabatan->uang_makan;

        $gajiBersih = ($gajiPokok + $tunjanganTransport + $uangMakan) - $totalSemuaPotongan;

        // Dijadikan object agar mudah dipakai di Blade
        $dataGaji = (object) [
            'gaji_pokok' => $gajiPokok,
            'tunjangan_transport' => $tunjanganTransport,
            'uang_makan' => $uangMakan,
            'potongan' => $totalSemuaPotongan,
            'gaji_bersih' => $gajiBersih,
        ];

        // =========================
        // 3. KIRIM DATA KE VIEW
        // =========================
        return view('pegawai.dashboard', compact(
            'karyawan',
            'dataGaji',
            'jumlahHadir',
            'jumlahSakit',
            'jumlahAlpha'
        ));
    }
}
