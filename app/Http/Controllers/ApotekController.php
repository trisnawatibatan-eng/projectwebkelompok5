<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApotekController extends Controller
{
    public function index()
    {
        return view('apotek.index', [
            'title' => 'Apotek - Daftar Transaksi'
        ]);
    }
}
