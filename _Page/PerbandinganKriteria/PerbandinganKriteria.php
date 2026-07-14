<?php
// 1. Validasi Akses Super Aman
$akses_pengguna = isset($SessionAkses) ? $SessionAkses : (isset($_SESSION['Akses']) ? $_SESSION['Akses'] : '');
if ($akses_pengguna != "Kadiv" && $akses_pengguna != "Pimpinan") {
    echo '<div class="alert alert-danger m-3">Anda tidak memiliki akses ke halaman ini! Level Anda: ' . htmlspecialchars($akses_pengguna) . '</div>';
    exit;
}

// ---------------------------------------------------------
// AUTO-DETECT KOLOM ID DATABASE
// ---------------------------------------------------------
$id_col = 'id_kriteria'; 
$cek_kolom = mysqli_query($Conn, "SELECT * FROM kriteria LIMIT 1");

if ($cek_kolom && mysqli_num_rows($cek_kolom) > 0) {
    $baris_cek = mysqli_fetch_assoc($cek_kolom);
    $semua_kolom = array_keys($baris_cek);
    $id_col = $semua_kolom[0]; 
    
    foreach($semua_kolom as $k) {
        if (strtolower($k) == 'id_kriteria') {
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

// ---------------------------------------------------------
// AMBIL DATA KRITERIA & DETEKSI NAMA KOLOM CERDAS
// ---------------------------------------------------------
$kriteriaArr = [];
$query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY 1 ASC");
if ($query) {
    while ($r = mysqli_fetch_assoc($query)) {
        
        // Cari Nama Kriteria
        $nama_tampil = '';
        if (isset($r['nama_kriteria'])) {
            $nama_tampil = $r['nama_kriteria'];
        } elseif (isset($r['kriteria'])) {
            $nama_tampil = $r['kriteria'];
        } elseif (isset($r['nama'])) {
            $nama_tampil = $r['nama'];
        } else {
            // Fallback jika nama kolom aneh
            $semua_kol = array_keys($r);
            $nama_tampil = isset($semua_kol[2]) ? $r[$semua_kol[2]] : (isset($semua_kol[1]) ? $r[$semua_kol[1]] : 'Kriteria Unknown');
        }

        // Cari Kode Kriteria (C1, C2) untuk digabungkan
        $kode = '';
        if (isset($r['kode_kriteria'])) {
            $kode = $r['kode_kriteria'] . ' - ';
        } elseif (isset($r['kode'])) {
            $kode = $r['kode'] . ' - ';
        }

        $kriteriaArr[] = [
            'id' => $r[$id_col],
            'nama' => $kode . $nama_tampil // Hasil: "C1 - Harga" atau "Harga"
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title m-0 p-0">Penilaian Matriks ANP</h5>
                        <button type="button" class="btn btn-outline-info btn-sm rounded-circle" data-bs-toggle="modal" data-bs-target="#ModalPanduanANP" title="Klik untuk melihat panduan">
                            <i class="bi bi-question-lg fs-5"></i>
                        </button>
                    </div>

                    <?php if($jmlKriteria > 1) { ?>
                    <form method="POST" action="index.php?Page=PerbandinganKriteria">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-primary text-center align-middle">
                                    <tr>
                                        <th style="width: 30%;">Kriteria Kiri</th>
                                        <th style="width: 40%;">Pilih Tingkat Kepentingan</th>
                                        <th style="width: 30%;">Kriteria Kanan</th>
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
                                        <td class="text-end align-middle fw-bold text-primary fs-6"><?= htmlspecialchars($k1['nama']) ?></td>
                                        <td>
                                            <select name="anp[<?= htmlspecialchars($k1['id']) ?>][<?= htmlspecialchars($k2['id']) ?>]" class="form-select text-center fw-bold shadow-sm" required>
                                                <option value="9">9 - Mutlak Lebih Penting (KIRI)</option>
                                                <option value="8">8 - Mendekati Mutlak Lebih Penting (KIRI)</option>
                                                <option value="7">7 - Sangat Jelas Lebih Penting (KIRI)</option>
                                                <option value="6">6 - Mendekati Sangat Jelas Lebih Penting (KIRI)</option>
                                                <option value="5">5 - Jelas Lebih Penting (KIRI)</option>
                                                <option value="4">4 - Mendekati Jelas Lebih Penting (KIRI)</option>
                                                <option value="3">3 - Sedikit Lebih Penting (KIRI)</option>
                                                <option value="2">2 - Mendekati Sedikit Lebih Penting (KIRI)</option>
                                                
                                                <option value="1" selected>1 - Keduanya SAMA PENTING</option>
                                                
                                                <option value="0.5">2 - Mendekati Sedikit Lebih Penting (KANAN)</option>
                                                <option value="0.3333">3 - Sedikit Lebih Penting (KANAN)</option>
                                                <option value="0.25">4 - Mendekati Jelas Lebih Penting (KANAN)</option>
                                                <option value="0.2">5 - Jelas Lebih Penting (KANAN)</option>
                                                <option value="0.1666">6 - Mendekati Sangat Jelas Lebih Penting (KANAN)</option>
                                                <option value="0.1428">7 - Sangat Jelas Lebih Penting (KANAN)</option>
                                                <option value="0.125">8 - Mendekati Mutlak Lebih Penting (KANAN)</option>
                                                <option value="0.1111">9 - Mutlak Lebih Penting (KANAN)</option>
                                            </select>
                                        </td>
                                        <td class="align-middle fw-bold text-success fs-6"><?= htmlspecialchars($k2['nama']) ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" name="hitung_anp" class="btn btn-primary w-100 mt-2"><i class="bi bi-save"></i> Simpan Penilaian ANP</button>
                    </form>
                    <?php } else { echo '<div class="alert alert-warning">Data kriteria belum cukup untuk dibandingkan (Minimal 2 Kriteria). Silakan pastikan data kriteria di database tidak kosong.</div>'; } ?>
                </div>

                <div class="tab-pane fade" id="tab-swara">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title m-0 p-0">Penilaian Peringkat SWARA</h5>
                        <button type="button" class="btn btn-outline-success btn-sm rounded-circle" data-bs-toggle="modal" data-bs-target="#ModalPanduanSWARA" title="Klik untuk melihat panduan">
                            <i class="bi bi-question-lg fs-5"></i>
                        </button>
                    </div>

                    <?php if($jmlKriteria > 0) { ?>
                    <form method="POST" action="index.php?Page=PerbandinganKriteria">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-success text-center align-middle">
                                    <tr>
                                        <th>Nama Kriteria yang Dinilai</th>
                                        <th style="width: 25%;">Peringkat Kriteria</th>
                                        <th style="width: 30%;">Jarak Kepentingan (Sj)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kriteriaArr as $k) { ?>
                                    <tr>
                                        <td class="align-middle fw-bold text-dark fs-6"><?= htmlspecialchars($k['nama']) ?></td>
                                        <td>
                                            <input type="number" name="peringkat[<?= htmlspecialchars($k['id']) ?>]" class="form-control text-center fw-bold" placeholder="Contoh: 1, 2, 3..." required min="1" max="<?= $jmlKriteria ?>">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="sj[<?= htmlspecialchars($k['id']) ?>]" class="form-control text-center" value="0" required>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" name="hitung_swara" class="btn btn-success w-100 mt-2"><i class="bi bi-save"></i> Simpan Penilaian SWARA</button>
                    </form>
                    <?php } else { echo '<div class="alert alert-warning">Data kriteria kosong. Silakan pastikan data kriteria di database tidak kosong.</div>'; } ?>
                </div>

            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="ModalPanduanANP" tabindex="-1" aria-labelledby="PanduanANPLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="PanduanANPLabel"><i class="bi bi-info-circle me-2"></i>Cara Mengisi Penilaian ANP</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-dark">
        <p>Pada metode ANP, Anda diminta untuk membandingkan <strong>dua kriteria secara berhadapan</strong> (Kiri melawan Kanan).</p>
        <ol>
            <li class="mb-2">Baca kriteria di sebelah <strong>KIRI</strong> (warna biru) dan kriteria di sebelah <strong>KANAN</strong> (warna hijau).</li>
            <li class="mb-2">Tentukan mana yang menurut Anda lebih penting untuk diutamakan.</li>
            <li class="mb-2">Klik kotak pilihan di tengah, lalu pilih skala angkanya:
                <ul class="mt-1">
                    <li>Jika sama penting, pilih <b>Angka 1</b>.</li>
                    <li>Jika sisi KIRI lebih penting, pilih angka yang berlabel <b>(KIRI)</b>. Semakin besar angkanya (hingga 9), berarti semakin mutlak.</li>
                    <li>Sebaliknya, jika sisi KANAN lebih penting, pilih angka yang berlabel <b>(KANAN)</b>.</li>
                </ul>
            </li>
            <li>Klik tombol <b>Simpan</b> jika semua kriteria sudah dibandingkan.</li>
        </ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Saya Mengerti</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="ModalPanduanSWARA" tabindex="-1" aria-labelledby="PanduanSWARALabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="PanduanSWARALabel"><i class="bi bi-info-circle me-2"></i>Cara Mengisi Penilaian SWARA</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-dark">
        <p>Metode SWARA sangat sederhana. Anda hanya perlu mengurutkan kriteria dari yang paling penting sampai yang paling tidak penting.</p>
        <ol>
            <li class="mb-2"><strong>Kolom Peringkat:</strong> Isi dengan angka 1, 2, 3, dan seterusnya.<br>
            <i>(Angka 1 untuk kriteria yang paling utama, angka 2 untuk juara kedua, dst).</i></li>
            <li class="mb-2"><strong>Kolom Jarak Kepentingan (Sj):</strong> 
                <ul class="mt-1">
                    <li>Untuk kriteria yang mendapat Peringkat 1, <b>biarkan nilainya 0</b>.</li>
                    <li>Untuk peringkat di bawahnya (Peringkat 2, 3, dst), isi dengan taksiran jarak kepentingan dibanding peringkat di atasnya. Gunakan titik untuk desimal (Contoh: <b>0.1</b> atau <b>0.05</b>).</li>
                </ul>
            </li>
            <li>Pastikan tidak ada angka peringkat yang ganda, lalu klik <b>Simpan</b>.</li>
        </ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Saya Mengerti</button>
      </div>
    </div>
  </div>
</div>