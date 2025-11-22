@extends('layouts')

@section('header', 'Pemeriksaan Pasien')
@section('content')
<div class="container text-center mt-5">
    <h3 class="mb-4">Pilih Jenis Pemeriksaan</h3>
    <div class="row justify-content-center">
        <div class="col-md-4 mb-3">
            <a href="/pemeriksaan/soap" class="btn btn-info w-100">🩺 Form SOAP</a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="/pemeriksaan/resume" class="btn btn-warning w-100">📋 Form Resume</a>
        </div>
    </div>
</div>
@endsection