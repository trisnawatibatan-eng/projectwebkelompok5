@extends('layouts')

@section('title', 'Dashboard')
@section('header', 'Sehat Mandiri')

@section('content')
<div class="container text-center">
    <h2 class="mb-4">Selamat Datang di Website Sehat Mandiri</h2>

    <div class="row justify-content-center">
        <div class="col-md-4 mb-3">
            <div class="bg-info text-white p-4 rounded shadow">
                <h5>Pasien Hari Ini</h5>
                <h2>34</h2> <!-- angka statis -->
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="bg-success text-white p-4 rounded shadow">
                <h5>Kunjungan Bulan Ini</h5>
                <h2>245</h2> <!-- angka statis -->
            </div>
        </div>
    </div>
</div>
@endsection