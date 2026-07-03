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

// ---------------------------------------------------------
// FITUR PINTAR 1: DETEKSI OTOMATIS KOLOM "NAMA KRITERIA"
// ---------------------------------------------------------
$kolom_nama = '';
foreach($kolom_db as $k) {
    $k_lower = strtolower($k);
    // Mencari kolom yang mengandung unsur kata nama atau kriteria
    if(in_array($k_lower, ['nama_kriteria', 'nama', 'kriteria', 'name', 'keterangan'])) {
        $kolom_nama = $k; 
        break;
    }
}
// Jika masih tidak ketemu nama yang pas, sistem otomatis mengambil kolom urutan ke-2 (biasanya ini pasti nama kriteria)
if($kolom_nama == '' && count($kolom_db) > 1) {
    $kolom_nama = $kolom_db[1];
}

// ---------------------------------------------------------
// FITUR PINTAR 2: DETEKSI OTOMATIS KOLOM "BOBOT/NILAI"
// ---------------------------------------------------------
// Kita prioritaskan kolom 'bobot' biasa karena form pakar biasanya update ke kolom ini
$kolom_anp = 'bobot'; 
if(!in_array('bobot', $kolom_db)) {
    $kolom_anp = in_array('bobot_anp', $kolom_db) ? 'bobot_anp' : (in_array('nilai', $kolom_db) ? 'nilai' : $kolom_db[2]);
}

$kolom_swara = 'bobot';
if(!in_array('bobot', $kolom_db)) {
    $kolom_swara = in_array('bobot_swara', $kolom_db) ? 'bobot_swara' : $kolom_anp;
}

$kolom_sj = in_array('nilai_sj', $kolom_db) ? 'nilai_sj' : $kolom_swara;

$kriteria = [];
$queryKriteria = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");

if(!$queryKriteria) {
    echo '<div class="alert alert-danger mt-3">Gagal mengambil data Kriteria dari database.</div>';
    exit;
}

// Gunakan fetch_assoc agar key pasti terbaca string
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
    
    // Tampilkan pesan biru agar Anda tahu persis sistem sedang membaca kolom apa
    echo '<div class="alert alert-info py-2 fs-6 mb-3">
            <i class="bi bi-info-circle"></i> <b>Info Debug:</b> Menampilkan nama dari kolom <code>'.$kolom_nama.'</code> dan mengambil nilai pakar dari kolom <code>'.$kolom_anp.'</code>.
          </div>';
    
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
            
            // PERBAIKAN: Gunakan number_format agar tampil pecahan persis 4 digit di belakang koma
            echo '<td>'.number_format($nilai_matriks, 4, '.', '').'</td>';
        }
        echo '</tr>';
    }
    
    echo '<tr class="table-secondary"><th class="text-start">Jumlah Kolom</th>';
    foreach($total_kolom as $total){
        echo '<th>'.number_format($total, 4, '.', '').'</th>';
    }
    echo '</tr></tbody></table></div>';

    echo '<h5 class="mt-5 mb-3 fw-bold text-primary">2. Matriks Normalisasi & Eigen Vector (Bobot Akhir ANP)</h5>';
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-hover text-center align-middle">';
    echo '<thead class="table-light"><tr><th>Kriteria</th>';
    foreach($kriteria as $k){
        echo '<th>'.$k[$kolom_nama].'</th>';
    }
    echo '<th>Jumlah Baris</th><th class="bg-primary text-white">Bobot Kriteria</th></tr></thead><tbody>';
    
    foreach($kriteria as $i => $k1){
        echo '<tr><th class="text-start">'.$k1[$kolom_nama].'</th>';
        
        $jumlah_baris = 0;
        foreach($kriteria as $j => $k2){
            $normalisasi = ($total_kolom[$j] != 0) ? ($matriks[$i][$j] / $total_kolom[$j]) : 0;
            $jumlah_baris += $normalisasi;
            
            // PERBAIKAN format angka
            echo '<td>'.number_format($normalisasi, 4, '.', '').'</td>';
        }
        $bobot_akhir = ($jumlah_kriteria != 0) ? ($jumlah_baris / $jumlah_kriteria) : 0;
        
        // PERBAIKAN format angka
        echo '<td class="fw-bold">'.number_format($jumlah_baris, 4, '.', '').'</td>';
        echo '<td class="fw-bold text-primary">'.number_format($bobot_akhir, 4, '.', '').'</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
    
} else if ($metode == "SWARA") {
    echo '<h5 class="mt-4 mb-3 fw-bold text-primary">Langkah Perhitungan Metode SWARA</h5>';
    
    // Tampilkan pesan biru
    echo '<div class="alert alert-info py-2 fs-6 mb-3">
            <i class="bi bi-info-circle"></i> <b>Info Debug:</b> Menampilkan nama dari kolom <code>'.$kolom_nama.'</code> dan membaca urutan dari kolom <code>'.$kolom_swara.'</code>.
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
    usort($kriteria_swara, function($a, $b) use ($kolom_swara) {
        $valA = isset($a[$kolom_swara]) ? (float)$a[$kolom_swara] : 0;
        $valB = isset($b[$kolom_swara]) ? (float)$b[$kolom_swara] : 0;
        return $valB <=> $valA;
    });
    
    $peringkat = 1;
    $q_prev = 1;
    $sum_q = 0;
    $data_swara = [];
    
    foreach($kriteria_swara as $row){
        $nama_kriteria = $row[$kolom_nama];
        $sj = isset($row[$kolom_sj]) ? (float)$row[$kolom_sj] : 0.00; 
        
        if($peringkat == 1) {
            $sj = 0; 
            $kj = 1;
            $qj = 1;
        } else {
            $kj = $sj + 1;
            $qj = ($kj != 0) ? ($q_prev / $kj) : 0;
        }
        
        $q_prev = $qj;
        $sum_q += $qj;
        
        $data_swara[] = [
            'nama' => $nama_kriteria,
            'sj' => $sj,
            'kj' => $kj,
            'qj' => $qj
        ];
        $peringkat++;
    }
    
    $no = 1;
    foreach($data_swara as $ds){
        $wj = ($sum_q != 0) ? ($ds['qj'] / $sum_q) : 0;
        
        // PERBAIKAN: Gunakan number_format untuk semua perhitungan SWARA
        echo '<tr>
                <td>'.$no++.'</td>
                <td class="text-start">'.$ds['nama'].'</td>
                <td>'.($ds['sj'] == 0 ? '-' : number_format($ds['sj'], 4, '.', '')).'</td>
                <td>'.number_format($ds['kj'], 4, '.', '').'</td>
                <td>'.number_format($ds['qj'], 4, '.', '').'</td>
                <td class="fw-bold text-primary">'.number_format($wj, 4, '.', '').'</td>
              </tr>';
    }
    echo '<tr class="table-secondary">
            <td colspan="4" class="text-end fw-bold">Jumlah Total qj :</td>
            <td class="fw-bold">'.number_format($sum_q, 4, '.', '').'</td>
            <td class="fw-bold text-primary">1.0000</td>
          </tr>';
    echo '</tbody></table></div>';
}
?>