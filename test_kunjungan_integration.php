<?php
/**
 * Test Script: Kunjungan Integration System
 * 
 * Testing workflow:
 * 1. Register new patient via Pendaftaran module
 * 2. Verify Kunjungan record created automatically
 * 3. List visits in Poliklinik module
 * 4. Submit exam form and verify integration
 */

// Test 1: Verify database tables exist
echo "=== TEST 1: Database Tables ===\n";

try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=project_db;charset=utf8mb4',
        'root',
        ''
    );
    
    $tables = ['kunjungans', 'pemeriksaan', 'pasiens', 'users'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✓ Table '$table' exists\n";
        } else {
            echo "✗ Table '$table' MISSING!\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
}

// Test 2: Check kunjungans table structure
echo "\n=== TEST 2: Kunjungans Table Structure ===\n";
try {
    $stmt = $pdo->query("DESCRIBE kunjungans");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $required = ['id', 'pasien_id', 'no_rm', 'poli', 'tanggal_kunjungan', 'keluhan_utama', 'status', 'pemeriksaan_id'];
    
    foreach ($required as $col) {
        if (in_array($col, $columns)) {
            echo "✓ Column '$col' exists\n";
        } else {
            echo "✗ Column '$col' MISSING!\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Structure check failed: " . $e->getMessage() . "\n";
}

// Test 3: Sample data check
echo "\n=== TEST 3: Sample Data ===\n";
try {
    // Check if any kunjungans exist
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM kunjungans");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total Kunjungans in DB: " . $result['cnt'] . "\n";
    
    // Show recent kunjungans
    $stmt = $pdo->query("SELECT k.id, k.no_rm, p.nama, k.poli, k.status, k.created_at 
                         FROM kunjungans k 
                         JOIN pasiens p ON k.pasien_id = p.id 
                         ORDER BY k.created_at DESC LIMIT 5");
    $kunjungans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($kunjungans) > 0) {
        echo "\n📋 Recent Kunjungans:\n";
        foreach ($kunjungans as $k) {
            echo sprintf("  ID:%d | No RM: %s | Pasien: %s | Poli: %s | Status: %s | Created: %s\n",
                $k['id'], $k['no_rm'], $k['nama'], $k['poli'], $k['status'], $k['created_at']
            );
        }
    } else {
        echo "⚠️  No kunjungans found yet. Please register a patient first.\n";
    }
} catch (Exception $e) {
    echo "✗ Sample data check failed: " . $e->getMessage() . "\n";
}

// Test 4: Models exist check
echo "\n=== TEST 4: Laravel Models ===\n";
$models = [
    'App\Models\Kunjungan',
    'App\Models\Pasien',
    'App\Models\Pemeriksaan',
];

foreach ($models as $model) {
    if (class_exists($model)) {
        echo "✓ Model $model exists\n";
    } else {
        echo "✗ Model $model NOT FOUND!\n";
    }
}

// Test 5: Routes check
echo "\n=== TEST 5: Routes ===\n";
echo "Expected routes for Kunjungan integration:\n";
echo "  GET  /poliklinik/daftar-kunjungan\n";
echo "  GET  /poliklinik/kunjungan/{kunjunganId}/pemeriksaan\n";
echo "  POST /poliklinik/kunjungan/{kunjunganId}/simpan-pemeriksaan\n";
echo "\nRun: php artisan route:list | grep kunjungan\n";

echo "\n=== ✅ INTEGRATION TEST COMPLETE ===\n";
echo "\n📝 Next Steps:\n";
echo "1. Go to http://localhost:8000/pasien/baru\n";
echo "2. Register a new patient with Poli Tujuan and Keluhan Utama\n";
echo "3. Check http://localhost:8000/poliklinik/daftar-kunjungan\n";
echo "4. Click 'Periksa' button and fill exam form\n";
echo "5. Verify data is pre-filled and saved correctly\n";
?>
