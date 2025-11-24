@extends('layout')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">❤️ Dashboard Klinik</h3>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm p-4 text-center border-0 rounded-4" style="background-color: #0bf132f6; color:white;">
                <h5>Total Pasien</h5>
                <h2>{{ $total }}</h2>
                <i class="bi bi-people-fill fs-3"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 text-center border-0 rounded-4" style="background-color: #174bf5c9; color:white;">
                <h5>Laki-laki</h5>
                <h2>{{ $laki }}</h2>
                <i class="bi bi-gender-male fs-3"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 text-center border-0 rounded-4" style="background-color: #f705f7ff; color:white;">
                <h5>Perempuan</h5>
                <h2>{{ $perempuan }}</h2>
                <i class="bi bi-gender-female fs-3"></i>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-5 border-0 rounded-4 p-4 text-center">
        <h5 class="mb-3">🧮 Statistik Jenis Kelamin</h5>
        <div style="max-width: 400px; margin: 0 auto;">  <!-- Ukuran grafik dibatasi -->
            <canvas id="genderChart" width="300" height="300"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('genderChart');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [{{ $laki }}, {{ $perempuan }}],
                backgroundColor: ['#1302fca4', '#e60eb7ff'],
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                title: {
                    display: true,
                    text: 'Perbandingan Jenis Kelamin Pasien',
                    font: { size: 14 }
                }
            }
        }
    });
</script>
@endsection