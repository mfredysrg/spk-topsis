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
    if(in_array($k_lower, ['nama_kriteria', 'nama', 'kriteria', 'name', 'keterangan'])) {
        $kolom_nama = $k; 
        break;
    }
}
if($kolom_nama == '' && count($kolom_db) > 1) {
    $kolom_nama = $kolom_db[1];
}

// ---------------------------------------------------------
// FITUR PINTAR 2: DETEKSI OTOMATIS KOLOM "BOBOT/NILAI"
// ---------------------------------------------------------
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
            
            echo '<td>'.number_format($nilai_matriks, 4, '.', '').'</td>';
        }
        echo '</tr>';
    }
    
    echo '<tr class="table-secondary"><th class="text-start">Jumlah Kolom</th>';
    foreach($total_kolom as $total){
        echo '<th>'.number_format($total, 4, '.', '').'</th>';
    }
    echo '</tr></tbody></table></div>';

    // ---------------------------------------------------------
    // 2. MATRIKS NORMALISASI & EIGEN VECTOR
    // ---------------------------------------------------------
    echo '<h5 class="mt-5 mb-3 fw-bold text-primary">2. Matriks Normalisasi & Eigen Vector (Bobot Akhir)</h5>';
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-hover text-center align-middle">';
    echo '<thead class="table-light"><tr><th>Kriteria</th>';
    foreach($kriteria as $k){
        echo '<th>'.$k[$kolom_nama].'</th>';
    }
    echo '<th>Jumlah Baris</th><th class="bg-primary text-white">Eigen Vector (Bobot)</th></tr></thead><tbody>';
    
    $eigen_vector_arr = []; // Array untuk menyimpan Eigen Vector untuk Iterasi nanti

    foreach($kriteria as $i => $k1){
        echo '<tr><th class="text-start">'.$k1[$kolom_nama].'</th>';
        
        $jumlah_baris = 0;
        foreach($kriteria as $j => $k2){
            $normalisasi = ($total_kolom[$j] != 0) ? ($matriks[$i][$j] / $total_kolom[$j]) : 0;
            $jumlah_baris += $normalisasi;
            
            echo '<td>'.number_format($normalisasi, 4, '.', '').'</td>';
        }
        $bobot_akhir = ($jumlah_kriteria != 0) ? ($jumlah_baris / $jumlah_kriteria) : 0;
        
        // Simpan ke array untuk iterasi
        $eigen_vector_arr[$i] = $bobot_akhir;

        // =========================================================================
        // SINKRONISASI DATABASE OTOMATIS UNTUK ANP
        // =========================================================================
        $id_kriteria_anp = $k1['id_kriteria'];
        $bobot_anp_db = number_format($bobot_akhir, 4, '.', ''); 
        mysqli_query($Conn, "UPDATE kriteria SET bobot_anp = '$bobot_anp_db' WHERE id_kriteria = '$id_kriteria_anp'");
        // =========================================================================

        echo '<td class="fw-bold">'.number_format($jumlah_baris, 4, '.', '').'</td>';
        echo '<td class="fw-bold text-primary">'.number_format($bobot_akhir, 4, '.', '').'</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';

    // ---------------------------------------------------------
    // 3. TAHAP ITERASI DAN RASIO KONSISTENSI (PENAMBAHAN BARU)
    // ---------------------------------------------------------
    echo '<h5 class="mt-5 mb-3 fw-bold text-primary">3. Matriks Perkalian (Iterasi) & Rasio Konsistensi</h5>';
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-hover text-center align-middle">';
    echo '<thead class="table-light">
            <tr>
                <th>Kriteria</th>
                <th>Weighted Sum Vector (WSV)</th>
                <th>Eigen Vector (EV)</th>
                <th class="bg-warning text-dark">WSV / EV (&lambda;)</th>
            </tr>
          </thead><tbody>';
    
    $lambda_max_total = 0;
    
    foreach($kriteria as $i => $k1){
        $wsv = 0;
        // Mengalikan matriks awal dengan eigen vector
        foreach($kriteria as $j => $k2){
            $wsv += $matriks[$i][$j] * $eigen_vector_arr[$j];
        }
        
        // Membagi WSV dengan Eigen Vector untuk mendapatkan nilai Lambda
        $lambda_i = ($eigen_vector_arr[$i] != 0) ? ($wsv / $eigen_vector_arr[$i]) : 0;
        $lambda_max_total += $lambda_i;
        
        echo '<tr>';
        echo '<th class="text-start">'.$k1[$kolom_nama].'</th>';
        echo '<td>'.number_format($wsv, 4, '.', '').'</td>';
        echo '<td>'.number_format($eigen_vector_arr[$i], 4, '.', '').'</td>';
        echo '<td class="fw-bold">'.number_format($lambda_i, 4, '.', '').'</td>';
        echo '</tr>';
    }
    
    // RUMUS KONSISTENSI
    $lambda_max = ($jumlah_kriteria != 0) ? ($lambda_max_total / $jumlah_kriteria) : 0;
    $ci = ($jumlah_kriteria > 1) ? (($lambda_max - $jumlah_kriteria) / ($jumlah_kriteria - 1)) : 0;
    
    // Tabel Random Index (RI) standar Saaty
    $ri_array = [1 => 0.00, 2 => 0.00, 3 => 0.58, 4 => 0.90, 5 => 1.12, 6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49, 11 => 1.51, 12 => 1.53, 13 => 1.56, 14 => 1.57, 15 => 1.59];
    $ri = isset($ri_array[$jumlah_kriteria]) ? $ri_array[$jumlah_kriteria] : 1.59; // Max 1.59 jika lebih dari 15
    
    $cr = ($ri != 0) ? ($ci / $ri) : 0;
    $status_konsistensi = ($cr <= 0.1) ? '<span class="badge bg-success py-2 px-3 ms-2 fs-6">KONSISTEN</span>' : '<span class="badge bg-danger py-2 px-3 ms-2 fs-6">TIDAK KONSISTEN</span>';

    echo '</tbody></table></div>';
    
    // Tampilkan Kesimpulan Konsistensi
    echo '<div class="alert alert-secondary mt-3 shadow-sm border-0">';
    echo '<h6 class="fw-bold mb-3 border-bottom pb-2">Kesimpulan Uji Konsistensi:</h6>';
    echo '<ul class="list-unstyled mb-0 fs-6">';
    echo '<li class="mb-2"><i class="bi bi-calculator"></i> <strong>&lambda; Max (Lambda Max):</strong> '.number_format($lambda_max, 4, '.', '').'</li>';
    echo '<li class="mb-2"><i class="bi bi-calculator"></i> <strong>Consistency Index (CI):</strong> '.number_format($ci, 4, '.', '').'</li>';
    echo '<li class="mb-2"><i class="bi bi-calculator"></i> <strong>Random Index (RI) untuk n='.$jumlah_kriteria.':</strong> '.number_format($ri, 4, '.', '').'</li>';
    echo '<li class="mt-3 pt-2 border-top"><i class="bi bi-check-circle-fill text-success"></i> <strong>Consistency Ratio (CR = CI / RI):</strong> <span class="fw-bold fs-5">'.number_format($cr, 4, '.', '').'</span> '.$status_konsistensi.'</li>';
    echo '</ul>';
    echo '<p class="mt-3 mb-0 text-muted small"><i>* Syarat mutlak: Nilai CR harus &le; 0.1 (10%) agar penilaian matriks perbandingan pakar dianggap <b>Konsisten</b> dan dapat dipertanggungjawabkan secara ilmiah.</i></p>';
    echo '</div>';
    
} else if ($metode == "SWARA") {
    echo '<h5 class="mt-4 mb-3 fw-bold text-primary">Langkah Perhitungan Metode SWARA</h5>';
    
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
        $id_kriteria = $row['id_kriteria']; 
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
            'id_kriteria' => $id_kriteria, 
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
        
        // =========================================================================
        // SINKRONISASI DATABASE OTOMATIS UNTUK SWARA (KUNCI 4 DIGIT DESIMAL)
        // =========================================================================
        $id_kriteria_swara = $ds['id_kriteria'];
        $wj_db = number_format($wj, 4, '.', ''); 
        mysqli_query($Conn, "UPDATE kriteria SET bobot_swara = '$wj_db' WHERE id_kriteria = '$id_kriteria_swara'");
        // =========================================================================

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