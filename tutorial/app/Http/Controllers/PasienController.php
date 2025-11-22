<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasienController extends Controller
{
    private $sessionKey = 'data_pasien';

    /**
     * Tampilkan daftar pasien
     */
    public function index()
    {
        $data = session($this->sessionKey, []);
        return view('pasien.index', compact('data'));
    }

    /**
     * Form tambah pasien
     */
    public function create()
    {
        return view('pasien.baru');
    }

    /**
     * Simpan pasien baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|size:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'poli_tujuan' => 'required|string',
            'jenis_pembayaran' => 'required|string',
            'tanggal_kunjungan' => 'required|date',
        ]);

        $data = session($this->sessionKey, []);

        // Cek NIK unik
        foreach ($data as $item) {
            if ($item['nik'] === $request->nik) {
                return back()->withErrors(['nik' => 'NIK sudah terdaftar!'])->withInput();
            }
        }

        // Generate No. RM otomatis
        $lastId = count($data);
        $no_rm = 'RM' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        $data[] = [
            'no_rm' => $no_rm,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal lahir,
            'alamat' => $request->alamat,
            'poli_tujuan' => $request->poli_tujuan,
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
        ];

        session([$this->sessionKey => $data]);

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail pasien
     */
    public function show($id)
    {
        $data = session($this->sessionKey, []);

        if (!isset($data[$id])) {
            return redirect()->route('pasien.index')->with('error', 'Pasien tidak ditemukan!');
        }

        $pasien = $data[$id];
        return view('pasien.show', compact('pasien', 'id'));
    }

    /**
     * Form edit pasien
     */
    public function edit($id)
    {
        $data = session($this->sessionKey, []);

        if (!isset($data[$id])) {
            return redirect()->route('pasien.index')->with('error', 'Pasien tidak ditemukan!');
        }

        $pasien = $data[$id];
        return view('pasien.edit', compact('pasien', 'id'));
    }

    /**
     * Update pasien
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|size:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'poli_tujuan' => 'required|string',
            'jenis_pembayaran' => 'required|string',
            'tanggal_kunjungan' => 'required|date',
        ]);

        $data = session($this->sessionKey, []);

        if (!isset($data[$id])) {
            return redirect()->route('pasien.index')->with('error', 'Pasien tidak ditemukan!');
        }

        // Cek NIK unik (kecuali pasien ini)
        foreach ($data as $key => $item) {
            if ($key != $id && $item['nik'] === $request->nik) {
                return back()->withErrors(['nik' => 'NIK sudah terdaftar!'])->withInput();
            }
        }

        $data[$id] = [
            'no_rm' => $data[$id]['no_rm'], // No. RM tetap
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'poli_tujuan' => $request->poli_tujuan, // PERBAIKAN: sebelumnya ' ' (spasi)
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
        ];

        session([$this->sessionKey => $data]);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui!');
    }

    /**
     * Hapus pasien
     */
    public function destroy($id)
    {
        $data = session($this->sessionKey, []);

        if (isset($data[$id])) {
            unset($data[$id]);
            $data = array_values($data); // Reset index agar urut kembali
            session([$this->sessionKey => $data]);
        }

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil dihapus!');
    }
}