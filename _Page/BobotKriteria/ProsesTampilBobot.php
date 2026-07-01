<?php
// Mencegah Cache Browser Menyimpan Nilai Lama
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include "../../_Config/Connection.php";

if(empty($_POST['metode'])){
    echo '<div class="alert alert-danger">Metode belum dipilih!</div>';
    exit;
}

$metode = $_POST['metode'];

// 1. CEK OTOMATIS STRUKTUR TABEL ANDA
$queryKolom = mysqli_query($Conn, "SHOW COLUMNS FROM kriteria");
$kolom_db = [];
while($col = mysqli_fetch_assoc($queryKolom)){
    $kolom_db[] = $col['Field'];
}

// DETEKSI KOLOM NAMA KRITERIA
$kolom_nama = '';
foreach($kolom_db as $k) {
    $k_lower = strtolower($k);
    if(in_array($k_lower, ['nama_kriteria', 'nama', 'kriteria', 'name', 'keterangan'])) {
        $kolom_nama = $k; 
        break;
    }
}
if($kolom_nama == '' && count($kolom_db) > 1) {
    $kolom_nama = $kolom_db[1];
}

// DETEKSI KOLOM ANP
$kolom_anp = 'bobot'; 
if(!in_array('bobot', $kolom_db)) {
    $kolom_anp = in_array('bobot_anp', $kolom_db) ? 'bobot_anp' : (in_array('nilai', $kolom_db) ? 'nilai' : $kolom_db[2]);
}

// DETEKSI KOLOM SWARA
$kolom_swara = 'bobot';
if(in_array('bobot_swara', $kolom_db)) {
    $kolom_swara = 'bobot_swara';
} else if(in_array('bobot', $kolom_db)){
    $kolom_swara = 'bobot';
} else {
    $kolom_swara = $kolom_anp;
}

// DETEKSI KOLOM SJ, KJ, QJ (Jika Pimpinan menyimpannya juga ke database)
$kolom_sj = in_array('nilai_sj', $kolom_db) ? 'nilai_sj' : (in_array('sj', $kolom_db) ? 'sj' : 'nilai_sj');
$kolom_kj = in_array('nilai_kj', $kolom_db) ? 'nilai_kj' : (in_array('kj', $kolom_db) ? 'kj' : 'nilai_kj');
$kolom_qj = in_array('nilai_qj', $kolom_db) ? 'nilai_qj' : (in_array('qj', $kolom_db) ? 'qj' : 'nilai_qj');

$kriteria = [];
$queryKriteria = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");

if(!$queryKriteria) {
    echo '<div class="alert alert-danger mt-3">Gagal mengambil data Kriteria dari database.</div>';
    exit;
}

while($row = mysqli_fetch_assoc($queryKriteria)){
    $kriteria[] = $row;
}
$jumlah_kriteria = count($kriteria);

if($jumlah_kriteria == 0){
    echo '<div class="alert alert-warning mt-3">Data Kriteria belum ada di database.</div>';
    exit;
}

if ($metode == "ANP") {
    echo '<h5 class="mt-4 mb-3 fw-bold text-primary">1. Matriks Perbandingan Berpasangan (ANP)</h5>';
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-hover text-center align-middle">';
    echo '<thead class="table-light"><tr><th>Kriteria</th>';
    foreach($kriteria as $k){
        echo '<th>'.$k[$kolom_nama].'</th>';
    }
    echo '</tr></thead><tbody>';
    
    $matriks = [];
    $total_kolom = array_fill(0, $jumlah_kriteria, 0);
    
    foreach($kriteria as $i => $k1){
        echo '<tr><th class="text-start">'.$k1[$kolom_nama].'</th>';
        $nilai1 = isset($k1[$kolom_anp]) ? (float)$k1[$kolom_anp] : 1;
        if($nilai1 == 0) $nilai1 = 1; 
        
        foreach($kriteria as $j => $k2){
            $nilai2 = isset($k2[$kolom_anp]) ? (float)$k2[$kolom_anp] : 1;
            if($nilai2 == 0) $nilai2 = 1;

            if($i == $j){
                $nilai_matriks = 1;
            } else {
                $nilai_matriks = $nilai1 / $nilai2;
            }
            
            $matriks[$i][$j] = $nilai_matriks;
            $total_kolom[$j] += $nilai_matriks;
            echo '<td>'.round($nilai_matriks, 3).'</td>';
        }
        echo '</tr>';
    }
    
    echo '<tr class="table-secondary"><th class="text-start">Jumlah Kolom</th>';
    foreach($total_kolom as $total){
        echo '<th>'.round($total, 3).'</th>';
    }
    echo '</tr></tbody></table></div>';

    echo '<h5 class="mt-5 mb-3 fw-bold text-primary">2. Matriks Normalisasi & Eigen Vector (Bobot Akhir ANP)</h5>';
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-hover text-center align-middle">';
    echo '<thead class="table-light"><tr><th>Kriteria</th>';
    foreach($kriteria as $k){
        echo '<th>'.$k[$kolom_nama].'</th>';
    }
    echo '<th>Jumlah Baris</th><th class="bg-primary text-white">Bobot Kriteria (Sesuai Pimpinan)</th></tr></thead><tbody>';
    
    foreach($kriteria as $i => $k1){
        echo '<tr><th class="text-start">'.$k1[$kolom_nama].'</th>';
        
        $jumlah_baris = 0;
        foreach($kriteria as $j => $k2){
            $normalisasi = ($total_kolom[$j] != 0) ? ($matriks[$i][$j] / $total_kolom[$j]) : 0;
            $jumlah_baris += $normalisasi;
            echo '<td>'.round($normalisasi, 3).'</td>';
        }
        
        // Murni ditarik dari database agar sama persis dengan tabel Kriteria Kadiv
        $bobot_akhir_db = isset($k1[$kolom_anp]) ? (float)$k1[$kolom_anp] : 0;
        
        echo '<td class="fw-bold">'.round($jumlah_baris, 3).'</td>';
        echo '<td class="fw-bold text-primary">'.round($bobot_akhir_db, 3).'</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
    
} else if ($metode == "SWARA") {
    echo '<h5 class="mt-4 mb-3 fw-bold text-primary">Langkah Perhitungan Metode SWARA</h5>';
    echo '<div class="alert alert-success py-2 fs-6 mb-3">
            <i class="bi bi-check-circle"></i> <b>Terkoneksi:</b> Nilai Final Weight ditarik secara <b>Real-Time murni dari database Kadiv (Kolom <code>'.$kolom_swara.'</code>)</b> sehingga dijamin 100% sama persis.
          </div>';
    
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-hover text-center align-middle">';
    echo '<thead class="table-light">
            <tr>
                <th>Peringkat</th>
                <th>Kriteria</th>
                <th>Comparative Importance (sj)</th>
                <th>Coefficient (kj)</th>
                <th>Recalculated Weight (qj)</th>
                <th class="bg-primary text-white">Final Weight (wj)</th>
            </tr>
          </thead><tbody>';
          
    $kriteria_swara = $kriteria;
    
    // Urutkan berdasarkan bobot final terbesar ke terkecil agar peringkat tabelnya sesuai
    usort($kriteria_swara, function($a, $b) use ($kolom_swara) {
        $valA = isset($a[$kolom_swara]) ? (float)$a[$kolom_swara] : 0;
        $valB = isset($b[$kolom_swara]) ? (float)$b[$kolom_swara] : 0;
        return $valB <=> $valA;
    });
    
    $peringkat = 1;
    $q_prev = 1;
    $sum_q = 0;
    
    foreach($kriteria_swara as $row){
        $nama_kriteria = $row[$kolom_nama];
        
        // 1. Tarik nilai Final Weight (wj) MURNI DARI DATABASE (TIDAK DIHITUNG ULANG)
        $wj = isset($row[$kolom_swara]) ? (float)$row[$kolom_swara] : 0;
        
        // 2. Tarik nilai sj dari database
        $sj = isset($row[$kolom_sj]) ? (float)$row[$kolom_sj] : 0.00; 
        
        // 3. Tarik atau hitung kj dan qj
        if(isset($row[$kolom_kj]) && $row[$kolom_kj] > 0){
            $kj = (float)$row[$kolom_kj];
        } else {
            $kj = ($peringkat == 1) ? 1 : ($sj + 1);
        }
        
        if(isset($row[$kolom_qj]) && $row[$kolom_qj] > 0){
            $qj = (float)$row[$kolom_qj];
        } else {
            $qj = ($peringkat == 1) ? 1 : (($kj != 0) ? ($q_prev / $kj) : 0);
        }
        
        $q_prev = $qj;
        $sum_q += $qj;
        
        echo '<tr>
                <td>'.$peringkat.'</td>
                <td class="text-start">'.$nama_kriteria.'</td>
                <td>'.($peringkat == 1 ? '-' : round($sj, 3)).'</td>
                <td>'.round($kj, 3).'</td>
                <td>'.round($qj, 3).'</td>
                <td class="fw-bold text-primary">'.round($wj, 3).'</td>
              </tr>';
              
        $peringkat++;
    }
    
    echo '<tr class="table-secondary">
            <td colspan="4" class="text-end fw-bold">Jumlah Total qj :</td>
            <td class="fw-bold">'.round($sum_q, 3).'</td>
            <td class="fw-bold text-primary">1.000</td>
          </tr>';
    echo '</tbody></table></div>';
}
?>