<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistrasiController extends Controller
{
    public function index()
    {
        return view('registrasi.index');
    }

    public function baru()
    {
        return view('registrasi.baru');
    }

    public function lama(Request $request)
    {
   
        $pasien = session('pasien_ditemukan');
        return view('registrasi.lama', compact('pasien'));
    }

    public function cariPasien(Request $request)
    {
        $no_rm = $request->input('no_rm');
        $dataPasien = session('data_pasien', []);

        // Cari pasien berdasarkan No RM
        $pasien = collect($dataPasien)->firstWhere('no_rm', $no_rm);

        if ($pasien) {
      
            session(['pasien_ditemukan' => $pasien]);
            return redirect()->route('registrasi.lama')->with('success', 'Data pasien ditemukan.');
        } else {
       
            session()->forget('pasien_ditemukan');
            return redirect()->route('registrasi.lama')->withErrors(['not_found' => 'Nomor RM tidak ditemukan.']);
        }
    }

    public function simpan(Request $request)
    {
        $dataSementara = session()->get('data_pasien', []);
        $no_rm = 'RM' . str_pad(count($dataSementara) + 1, 4, '0', STR_PAD_LEFT);

        $pasienBaru = [
            'no_rm' => $no_rm,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'poli_tujuan' => $request->poli_tujuan,
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
        ];

        $dataSementara[] = $pasienBaru;
        session(['data_pasien' => $dataSementara]);

        return redirect()->route('pasien.index');
    }

    public function simpanPasienLama(Request $request)
    {
        // Ambil data pasien lama dari session
        $dataPasien = session('data_pasien', []);
        $pasien = session('pasien_ditemukan');

        if (!$pasien) {
            return redirect()->back()->withErrors(['not_found' => 'Tidak ada data pasien untuk disimpan.']);
        }

        $kunjunganBaru = [
            'no_rm' => $pasien['no_rm'],
            'nik' => $pasien['nik'] ?? '',
            'nama' => $pasien['nama'],
            'jenis_kelamin' => $pasien['jenis_kelamin'],
            'tanggal_lahir' => $pasien['tanggal_lahir'],
            'alamat' => $pasien['alamat'],
            'poli_tujuan' => $request->poli_tujuan,
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
        ];

       
        $dataPasien[] = $kunjunganBaru;
        session(['data_pasien' => $dataPasien]);

      
        session()->forget('pasien_ditemukan');

        return redirect()->route('pasien.index')->with('success', 'Kunjungan pasien lama berhasil disimpan ke Master Pasien.');
    }
}
