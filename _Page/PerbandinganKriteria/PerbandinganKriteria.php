<?php
// 1. Validasi Akses Super Aman
$akses_pengguna = isset($SessionAkses) ? $SessionAkses : (isset($_SESSION['Akses']) ? $_SESSION['Akses'] : '');
if ($akses_pengguna != "Kadiv" && $akses_pengguna != "Pimpinan") {
    echo '<div class="alert alert-danger m-3">Anda tidak memiliki akses ke halaman ini! Level Anda: ' . htmlspecialchars($akses_pengguna) . '</div>';
    exit;
}

// ---------------------------------------------------------
// AUTO-DETECT NAMA KOLOM DATABASE (ANTI ERROR)
// ---------------------------------------------------------
$id_col = 'id_kriteria'; 
$nama_col = 'nama_kriteria';
$cek_kolom = mysqli_query($Conn, "SELECT * FROM kriteria LIMIT 1");

if ($cek_kolom && mysqli_num_rows($cek_kolom) > 0) {
    $baris_cek = mysqli_fetch_assoc($cek_kolom);
    $semua_kolom = array_keys($baris_cek);
    
    $id_col = $semua_kolom[0]; 
    $nama_col = isset($semua_kolom[1]) ? $semua_kolom[1] : $semua_kolom[0];
    
    foreach($semua_kolom as $k) {
        if (stripos($k, 'nama') !== false) {
            $nama_col = $k;
        }
        if (stripos($k, 'id') !== false && stripos($k, 'kriteria') !== false) {
            $id_col = $k;
        }
    }
}

// ---------------------------------------------------------
// LOGIKA PERHITUNGAN ANP
// ---------------------------------------------------------
if (isset($_POST['hitung_anp'])) {
    $ids = [];
    $kriteria_query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY 1 ASC");
    if($kriteria_query) {
        while ($row = mysqli_fetch_assoc($kriteria_query)) {
            $ids[] = $row[$id_col];
        }
    }
    
    $matrix = [];
    $col_sum = [];
    $n = count($ids);
    $anp_post = isset($_POST['anp']) ? $_POST['anp'] : [];

    for ($i = 0; $i < $n; $i++) {
        $id1 = $ids[$i];
        $matrix[$id1] = [];
        for ($j = 0; $j < $n; $j++) {
            $id2 = $ids[$j];
            $matrix[$id1][$id2] = 1;
        }
    }

    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            if ($i < $j) {
                $id1 = $ids[$i];
                $id2 = $ids[$j];
                
                if (isset($anp_post[$id1][$id2])) {
                    // Nilai desimal dari background otomatis ditangkap di sini
                    $val = (float)$anp_post[$id1][$id2];
                    if ($val == 0) $val = 1; 
                    
                    $matrix[$id1][$id2] = $val;
                    $matrix[$id2][$id1] = 1 / $val; 
                }
            }
        }
    }

    for ($j = 0; $j < $n; $j++) {
        $id2 = $ids[$j];
        $col_sum[$id2] = 0;
        for ($i = 0; $i < $n; $i++) {
            $id1 = $ids[$i];
            $col_sum[$id2] += $matrix[$id1][$id2];
        }
    }

    for ($i = 0; $i < $n; $i++) {
        $id1 = $ids[$i];
        $row_sum_norm = 0;
        for ($j = 0; $j < $n; $j++) {
            $id2 = $ids[$j];
            $total_kolom = $col_sum[$id2] > 0 ? $col_sum[$id2] : 1;
            $normalized_val = $matrix[$id1][$id2] / $total_kolom;
            $row_sum_norm += $normalized_val;
        }
        $bobot = $row_sum_norm / ($n > 0 ? $n : 1); 
        
        // Update ke database
        mysqli_query($Conn, "UPDATE kriteria SET bobot_anp = '$bobot' WHERE $id_col = '$id1'");
    }
    echo '<div class="alert alert-success alert-dismissible fade show m-3" role="alert">Berhasil! Perhitungan Bobot ANP selesai dan otomatis tersimpan.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}

// ---------------------------------------------------------
// LOGIKA PERHITUNGAN SWARA
// ---------------------------------------------------------
if (isset($_POST['hitung_swara'])) {
    $swara_data = [];
    $peringkat_post = isset($_POST['peringkat']) ? $_POST['peringkat'] : [];
    $sj_post = isset($_POST['sj']) ? $_POST['sj'] : [];

    $kriteria_query = mysqli_query($Conn, "SELECT * FROM kriteria");
    if($kriteria_query) {
        while ($row = mysqli_fetch_assoc($kriteria_query)) {
            $id_k = $row[$id_col];
            $swara_data[] = [
                'id' => $id_k,
                'peringkat' => isset($peringkat_post[$id_k]) ? (int)$peringkat_post[$id_k] : 99,
                'sj' => isset($sj_post[$id_k]) ? (float)$sj_post[$id_k] : 0
            ];
        }
    }

    usort($swara_data, function($a, $b) {
        return $a['peringkat'] <=> $b['peringkat'];
    });

    $total_q = 0;
    for ($i = 0; $i < count($swara_data); $i++) {
        if ($i == 0) {
            $swara_data[$i]['kj'] = 1;
            $swara_data[$i]['qj'] = 1;
        } else {
            $sj = $swara_data[$i]['sj'];
            $swara_data[$i]['kj'] = $sj + 1;
            
            $nilai_kj = $swara_data[$i]['kj'] != 0 ? $swara_data[$i]['kj'] : 1; 
            $qj_sebelumnya = $swara_data[$i - 1]['qj'];
            
            $swara_data[$i]['qj'] = $qj_sebelumnya / $nilai_kj;
        }
        $total_q += $swara_data[$i]['qj'];
    }

    if ($total_q > 0) {
        foreach ($swara_data as $data) {
            $wj = $data['qj'] / $total_q;
            $id_k = $data['id'];
            mysqli_query($Conn, "UPDATE kriteria SET bobot_swara = '$wj' WHERE $id_col = '$id_k'");
        }
        echo '<div class="alert alert-success alert-dismissible fade show m-3" role="alert">Berhasil! Perhitungan Bobot SWARA selesai dan otomatis tersimpan.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

// Ambil Data Kriteria dengan Kolom Dinamis
$kriteriaArr = [];
$query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY 1 ASC");
if ($query) {
    while ($r = mysqli_fetch_assoc($query)) {
        $kriteriaArr[] = [
            'id' => $r[$id_col],
            'nama' => $r[$nama_col]
        ];
    }
}
$jmlKriteria = count($kriteriaArr);
?>

<div class="pagetitle">
    <h1>Perbandingan Kriteria</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Perbandingan Kriteria</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body pt-3">
            <ul class="nav nav-tabs nav-tabs-bordered">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-anp">Metode ANP</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-swara">Metode SWARA</button>
                </li>
            </ul>

            <div class="tab-content pt-3">
                
                <div class="tab-pane fade show active" id="tab-anp">
                    <div class="alert alert-info">
                        <strong>Panduan ANP:</strong> Bandingkan mana kriteria yang lebih penting (Kriteria Kiri atau Kanan) menggunakan skala angka 1 hingga 9.
                    </div>
                    <?php if($jmlKriteria > 1) { ?>
                    <form method="POST" action="index.php?Page=PerbandinganKriteria">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>Kriteria Kiri</th>
                                        <th>Tingkat Kepentingan & Pihak yang Lebih Penting</th>
                                        <th>Kriteria Kanan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < $jmlKriteria - 1; $i++) {
                                        for ($j = $i + 1; $j < $jmlKriteria; $j++) {
                                            $k1 = $kriteriaArr[$i];
                                            $k2 = $kriteriaArr[$j];
                                    ?>
                                    <tr>
                                        <td class="text-end align-middle fw-bold"><?= htmlspecialchars($k1['nama']) ?></td>
                                        <td>
                                            <select name="anp[<?= htmlspecialchars($k1['id']) ?>][<?= htmlspecialchars($k2['id']) ?>]" class="form-select text-center fw-bold" required>
                                                <option value="9">9 - Mutlak Lebih Penting (Kiri)</option>
                                                <option value="8">8 - Mendekati Mutlak Lebih Penting (Kiri)</option>
                                                <option value="7">7 - Sangat Jelas Lebih Penting (Kiri)</option>
                                                <option value="6">6 - Mendekati Sangat Jelas Lebih Penting (Kiri)</option>
                                                <option value="5">5 - Jelas Lebih Penting (Kiri)</option>
                                                <option value="4">4 - Mendekati Jelas Lebih Penting (Kiri)</option>
                                                <option value="3">3 - Sedikit Lebih Penting (Kiri)</option>
                                                <option value="2">2 - Mendekati Sedikit Lebih Penting (Kiri)</option>
                                                
                                                <option value="1" selected>1 - Keduanya SAMA PENTING</option>
                                                
                                                <option value="0.5">2 - Mendekati Sedikit Lebih Penting (Kanan)</option>
                                                <option value="0.3333">3 - Sedikit Lebih Penting (Kanan)</option>
                                                <option value="0.25">4 - Mendekati Jelas Lebih Penting (Kanan)</option>
                                                <option value="0.2">5 - Jelas Lebih Penting (Kanan)</option>
                                                <option value="0.1666">6 - Mendekati Sangat Jelas Lebih Penting (Kanan)</option>
                                                <option value="0.1428">7 - Sangat Jelas Lebih Penting (Kanan)</option>
                                                <option value="0.125">8 - Mendekati Mutlak Lebih Penting (Kanan)</option>
                                                <option value="0.1111">9 - Mutlak Lebih Penting (Kanan)</option>
                                            </select>
                                        </td>
                                        <td class="align-middle fw-bold"><?= htmlspecialchars($k2['nama']) ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" name="hitung_anp" class="btn btn-primary"><i class="bi bi-calculator"></i> Hitung & Simpan Bobot ANP</button>
                    </form>
                    <?php } else { echo '<div class="alert alert-warning">Data kriteria belum cukup untuk dibandingkan (Minimal 2 Kriteria). Silakan pastikan data kriteria di database tidak kosong.</div>'; } ?>
                </div>

                <div class="tab-pane fade" id="tab-swara">
                    <div class="alert alert-info">
                        <strong>Panduan SWARA:</strong> <br>
                        1. Tentukan <b>Peringkat</b> kriteria dari yang paling penting (Peringkat 1) hingga terakhir.<br>
                        2. Isi <b>Nilai Kepentingan Relatif (Sj)</b>. Untuk Peringkat 1 biarkan 0, selanjutnya isi desimal (contoh: 0.1).
                    </div>
                    <?php if($jmlKriteria > 0) { ?>
                    <form method="POST" action="index.php?Page=PerbandinganKriteria">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-success text-center">
                                    <tr>
                                        <th>Nama Kriteria</th>
                                        <th>Peringkat (1,2,3...)</th>
                                        <th>Nilai Kepentingan Relatif (Sj)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kriteriaArr as $k) { ?>
                                    <tr>
                                        <td class="align-middle fw-bold"><?= htmlspecialchars($k['nama']) ?></td>
                                        <td>
                                            <input type="number" name="peringkat[<?= htmlspecialchars($k['id']) ?>]" class="form-control" placeholder="Contoh: 1" required min="1" max="<?= $jmlKriteria ?>">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="sj[<?= htmlspecialchars($k['id']) ?>]" class="form-control" value="0" required>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" name="hitung_swara" class="btn btn-success"><i class="bi bi-calculator"></i> Hitung & Simpan Bobot SWARA</button>
                    </form>
                    <?php } else { echo '<div class="alert alert-warning">Data kriteria kosong. Silakan pastikan data kriteria di database tidak kosong.</div>'; } ?>
                </div>

            </div>
        </div>
    </div>
</section>