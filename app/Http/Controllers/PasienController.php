<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class PasienController extends Controller
{
    /**
     * 🔹 Tampilkan semua pasien (Data Master)
     */
    public function index()
    {
        $pasien = Pasien::all();
        return view('data_master', [
            'title' => 'Data Master Pasien',
            'pasien' => $pasien,
            'keyword' => null
        ]);
    }

    /**
     * 🔹 Tampilkan form pendaftaran pasien baru
     */
    public function create()
    {
        return view('pendaftaran.pasien_baru', [
            'title' => 'Pendaftaran Pasien Baru'
        ]);
    }

    /**
     * 🔹 Simpan data pasien baru dengan No RM otomatis
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|unique:pasiens,nik',
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required'
        ]);

        // Generate No RM otomatis
        $lastPasien = Pasien::orderBy('id', 'desc')->first();
        $nextId = $lastPasien ? $lastPasien->id + 1 : 1;
        $no_rm = 'RM' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        $validated['no_rm'] = $no_rm;

        Pasien::create($validated);

        return redirect()->route('data.master')->with('success', '✅ Pasien baru berhasil didaftarkan dan masuk ke Data Master!');
    }

    /**
     * 🔹 Tampilkan form pencarian pasien lama
     */
    public function searchForm()
    {
        return view('pendaftaran.pasien_lama', [
            'title' => 'Pendaftaran Pasien Lama',
            'pasien' => null,
        ]);
    }

    /**
     * 🔹 Cari pasien berdasarkan No RM / NIK / Nama
     * ✔ Hasil pencarian langsung muncul tanpa redirect
     */
    public function searchByNoRM(Request $request)
    {
        $keyword = $request->keyword;

        $pasien = Pasien::where('no_rm', $keyword)
            ->orWhere('nik', $keyword)
            ->orWhere('nama', 'like', "%$keyword%")
            ->first();

        return view('pendaftaran.pasien_lama', [
            'title' => 'Pendaftaran Pasien Lama',
            'pasien' => $pasien,
            'info' => $pasien ? null : 'Pasien tidak ditemukan. Silakan daftar sebagai pasien baru.'
        ]);
    }

    /**
     * 🔹 Pencarian di Data Master (POST)
     */
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $pasien = Pasien::where('nik', 'like', "%$keyword%")
            ->orWhere('nama', 'like', "%$keyword%")
            ->orWhere('no_rm', 'like', "%$keyword%")
            ->get();

        return view('data_master', [
            'title' => 'Hasil Pencarian Pasien',
            'pasien' => $pasien,
            'keyword' => $keyword
        ]);
    }

    /**
     * 🔹 Tampilkan form edit pasien
     */
    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('edit_pasien', [
            'title' => 'Edit Data Pasien',
            'pasien' => $pasien
        ]);
    }

    /**
     * 🔹 Simpan perubahan data pasien
     */
    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        $validated = $request->validate([
            'nik' => 'required|unique:pasiens,nik,' . $id,
            'no_rm' => 'required|unique:pasiens,no_rm,' . $id,
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required'
        ]);

        $pasien->update($validated);

        return redirect()->route('data.master')->with('success', '✅ Data pasien berhasil diperbarui!');
    }

    /**
     * 🔹 Hapus data pasien
     */
    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();

        return redirect()->route('data.master')->with('success', '🗑️ Data pasien berhasil dihapus!');
    }
}
