<?php

namespace App\Http\Controllers;

class PemeriksaanController extends Controller
{
    public function index()
    {
        return view('pemeriksaan.index');
    }

    public function soap()
    {
        return view('pemeriksaan.soap');
    }

    public function resume()
    {
        return view('pemeriksaan.resume');
    }
}
