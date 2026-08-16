<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";

    // -------------------------------------------------------------------------
    // INTEGRASI METODE PEMBOBOTAN (ANP / SWARA)
    // -------------------------------------------------------------------------
    if(empty($_POST['metode_pembobotan'])){
        echo '<div class="alert alert-danger">Pilih metode pembobotan terlebih dahulu!</div>';
        exit;
    }
    
    $metode_pembobotan = $_POST['metode_pembobotan'];
    $_SESSION['metode_terakhir'] = $metode_pembobotan;

    if(!empty($_POST['id_periode_penilaian'])){
        $id_periode_penilaian = $_POST['id_periode_penilaian'];
        
        $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
        $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
        
        if(empty($DataPeriodePenilaian)){
             echo '<div class="alert alert-danger">Mohon maaf, Data Periode Penilaian tidak ditemukan di database.</div>';
             exit;
        }

        $status = $DataPeriodePenilaian['status'] ?? '';

        // =========================================================================================
        // ENGINE PERHITUNGAN TOPSIS PRESISI EXCEL (DI MEMORI)
        // =========================================================================================
        
        // 1. Ambil Data Kriteria (Urut C1, C2, C3, dst.)
        $kriteria_arr = [];
        $query_k = mysqli_query($Conn, "SELECT DISTINCT k.*, LENGTH(k.kode_kriteria) AS len_kode FROM nilai n JOIN kriteria k ON n.id_kriteria = k.id_kriteria WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY len_kode ASC, k.kode_kriteria ASC");
        while ($r = mysqli_fetch_array($query_k)) {
            $kriteria_arr[] = $r;
        }

        // 2. Ambil Data UMKM / Alternatif
        $umkm_arr = [];
        $query_u = mysqli_query($Conn, "SELECT DISTINCT u.id_umkm, u.nama_umkm, u.nama_pemilik FROM nilai n JOIN umkm u ON n.id_umkm = u.id_umkm WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY u.id_umkm ASC");
        while ($r = mysqli_fetch_array($query_u)) {
            $umkm_arr[] = $r;
        }

        // 3. Ambil Matriks Asli (X)
        $matriks_x = [];
        $query_n = mysqli_query($Conn, "SELECT id_umkm, id_kriteria, nilai FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian'");
        while ($r = mysqli_fetch_array($query_n)) {
            $matriks_x[$r['id_umkm']][$r['id_kriteria']] = floatval($r['nilai']);
        }

        // 4. Kalkulasi Pembagi (SQRT), R (Ternormalisasi), dan Y (Terbobot)
        $pembagi = [];
        $matriks_r = [];
        $matriks_y = [];
        
        foreach ($kriteria_arr as $k) {
            $id_k = $k['id_kriteria'];
            $bobot = ($metode_pembobotan == 'ANP') ? floatval($k['bobot_anp'] ?? 0) : floatval($k['bobot_swara'] ?? 0);
            
            // Hitung Pembagi = SQRT( SUM( Xij^2 ) )
            $sum_sq = 0;
            foreach ($umkm_arr as $u) {
                $id_u = $u['id_umkm'];
                $val = $matriks_x[$id_u][$id_k] ?? 0;
                $sum_sq += pow($val, 2);
            }
            $pembagi_val = sqrt($sum_sq);
            $pembagi[$id_k] = $pembagi_val;

            // Hitung Matriks R & Y
            foreach ($umkm_arr as $u) {
                $id_u = $u['id_umkm'];
                $val = $matriks_x[$id_u][$id_k] ?? 0;
                
                $r_val = ($pembagi_val == 0) ? 0 : ($val / $pembagi_val);
                $matriks_r[$id_u][$id_k] = $r_val;
                $matriks_y[$id_u][$id_k] = $r_val * $bobot;
            }
        }

        // 5. Menentukan Solusi Ideal Positif (A+) & Negatif (A-)
        $ideal_positif = [];
        $ideal_negatif = [];
        foreach ($kriteria_arr as $k) {
            $id_k = $k['id_kriteria'];
            $atribut = strtolower(trim($k['atribut'] ?? ($k['sifat'] ?? 'benefit')));
            
            $col_y = [];
            foreach ($umkm_arr as $u) {
                $col_y[] = $matriks_y[$u['id_umkm']][$id_k];
            }
            
            if ($atribut == 'cost' || $atribut == 'biaya') {
                $ideal_positif[$id_k] = min($col_y);
                $ideal_negatif[$id_k] = max($col_y);
            } else {
                $ideal_positif[$id_k] = max($col_y);
                $ideal_negatif[$id_k] = min($col_y);
            }
        }

        // 6. Hitung Jarak Solusi Ideal (D+ & D-) serta Preferensi (V)
        $d_plus = [];
        $d_min = [];
        $preferensi_v = [];
        $ranking_umkm = [];

        foreach ($umkm_arr as $u) {
            $id_u = $u['id_umkm'];
            $sum_plus = 0;
            $sum_min = 0;
            
            foreach ($kriteria_arr as $k) {
                $id_k = $k['id_kriteria'];
                $y = $matriks_y[$id_u][$id_k];
                
                $sum_plus += pow(($y - $ideal_positif[$id_k]), 2);
                $sum_min += pow(($y - $ideal_negatif[$id_k]), 2);
            }
            
            $dp = sqrt($sum_plus);
            $dm = sqrt($sum_min);
            $d_plus[$id_u] = $dp;
            $d_min[$id_u] = $dm;
            
            $v = (($dp + $dm) == 0) ? 0 : ($dm / ($dp + $dm));
            $preferensi_v[$id_u] = $v;

            $ranking_umkm[] = [
                'id_umkm' => $id_u,
                'nama_umkm' => $u['nama_umkm'],
                'nama_pemilik' => $u['nama_pemilik'],
                'nilai_v' => $v
            ];
        }

        // Sort Rangking dari Preferensi Terbesar ke Terkecil
        usort($ranking_umkm, function($a, $b) {
            return $b['nilai_v'] <=> $a['nilai_v'];
        });
        // =========================================================================================
?>
    <div class="alert alert-info mb-3">
        <b><i class="bi bi-info-circle"></i> Informasi:</b> Anda sedang melihat hasil seleksi <b>Penerima Bantuan Sosial UMKM</b> menggunakan <b>TOPSIS</b> dengan bobot kriteria dari metode <b><?php echo $metode_pembobotan; ?></b>.
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">1. Matriks Ternormalisasi ('R)</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center"><b>No</b></th>
                            <th class="text-center"><b>Nama UMKM</b></th>
                            <?php
                                foreach ($kriteria_arr as $k) {
                                    $id_kriteria = $k['id_kriteria'];
                                    $bobot = ($metode_pembobotan == 'ANP') ? floatval($k['bobot_anp'] ?? 0) : floatval($k['bobot_swara'] ?? 0);
                                    $kode = $k['kode_kriteria'] ?? '-';
                                    echo '<th class="text-center"><b>'.$kode.'</b><br>('.$bobot.')</th>';

                                    // SIMPAN KE DB (ROUND 4 AGAR BEBAS ERROR DATA TOO LONG)
                                    $p_val = $pembagi[$id_kriteria];
                                    $sq_val = pow($p_val, 2);
                                    $sq_val_db = round($sq_val, 4);
                                    $p_val_db = round($p_val, 4);

                                    $CekDB = mysqli_query($Conn,"SELECT id_normalisasi FROM normalisasi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'");
                                    if(mysqli_num_rows($CekDB) == 0){
                                        mysqli_query($Conn, "INSERT INTO normalisasi (id_periode_penilaian, id_kriteria, normalisasi, sqrt_normalisasi) VALUES ('$id_periode_penilaian', '$id_kriteria', '$sq_val_db', '$p_val_db')");
                                    } else {
                                        mysqli_query($Conn, "UPDATE normalisasi SET normalisasi='$sq_val_db', sqrt_normalisasi='$p_val_db' WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'"); 
                                    }
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            foreach ($umkm_arr as $u) {
                                $id_umkm = $u['id_umkm'];
                        ?>
                                <tr>
                                    <td class="text-center text-xs"><?php echo $no++; ?></td>
                                    <td class="text-left" align="left">
                                        <b><?php echo $u['nama_umkm']; ?></b><br>
                                        <small>Pemilik: <?php echo $u['nama_pemilik']; ?></small>
                                    </td>
                                    <?php
                                        foreach ($kriteria_arr as $k) {
                                            $id_kriteria = $k['id_kriteria'];
                                            $rij = $matriks_r[$id_umkm][$id_kriteria];
                                            echo '<td align="right">'.number_format($rij, 4, '.', '').'</td>';
                                        }
                                    ?>
                                </tr>
                        <?php } ?>
                        <tr class="bg-light">
                            <td class="text-center text-xs" colspan="2"><b>Nilai Pembagi (SQRT)</b></td>
                            <?php
                                foreach ($kriteria_arr as $k) {
                                    $id_kriteria = $k['id_kriteria'];
                                    $p_val = $pembagi[$id_kriteria];
                                    echo '<td align="right"><b>'.number_format($p_val, 4, '.', '').'</b></td>';
                                }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">2. Normaliasai Terbobot (Xij/SQRT (∑i-1))*Bobot</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center"><b>No</b></th>
                            <th class="text-center"><b>Nama UMKM</b></th>
                            <?php
                                foreach ($kriteria_arr as $k) {
                                    $bobot = ($metode_pembobotan == 'ANP') ? floatval($k['bobot_anp'] ?? 0) : floatval($k['bobot_swara'] ?? 0);
                                    $kode = $k['kode_kriteria'] ?? '-';
                                    echo '<th class="text-center"><b>'.$kode.'</b><br>('.$bobot.')</th>';
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            foreach ($umkm_arr as $u) {
                                $id_umkm = $u['id_umkm'];
                        ?>
                                <tr>
                                    <td class="text-center text-xs"><?php echo $no++; ?></td>
                                    <td class="text-left" align="left">
                                        <b><?php echo $u['nama_umkm']; ?></b><br>
                                        <small>Pemilik: <?php echo $u['nama_pemilik']; ?></small>
                                    </td>
                                    <?php
                                        foreach ($kriteria_arr as $k) {
                                            $id_kriteria = $k['id_kriteria'];
                                            $y_val = $matriks_y[$id_umkm][$id_kriteria];
                                            
                                            // SIMPAN KE DB (ROUND 4)
                                            $y_val_db = round($y_val, 4);

                                            $CekDB = mysqli_query($Conn,"SELECT id_normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_umkm='$id_umkm'");
                                            if(mysqli_num_rows($CekDB) == 0){
                                                mysqli_query($Conn, "INSERT INTO normalisasi_terbobot (id_periode_penilaian, id_kriteria, id_umkm, normalisasi_terbobot) VALUES ('$id_periode_penilaian', '$id_kriteria', '$id_umkm', '$y_val_db')");
                                            } else {
                                                mysqli_query($Conn, "UPDATE normalisasi_terbobot SET normalisasi_terbobot='$y_val_db' WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_umkm='$id_umkm'");
                                            }

                                            echo '<td align="right"><span class="text-success">'.number_format($y_val, 4, '.', '').'</span></td>';
                                        }
                                    ?>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">3. Metrik Solusi Ideal Positif & Negatif</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center"><b>#</b></th>
                            <?php
                                foreach ($kriteria_arr as $k) {
                                    $bobot = ($metode_pembobotan == 'ANP') ? floatval($k['bobot_anp'] ?? 0) : floatval($k['bobot_swara'] ?? 0);
                                    $kode = $k['kode_kriteria'] ?? '-';
                                    echo '<th class="text-center"><b>'.$kode.'</b><br>('.$bobot.')</th>';
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left text-xs"><b>Positif (A+)</b></td>
                            <?php
                                foreach ($kriteria_arr as $k) {
                                    $id_kriteria = $k['id_kriteria'];
                                    $val_plus = $ideal_positif[$id_kriteria];
                                    $val_plus_db = round($val_plus, 4);

                                    $CekDB = mysqli_query($Conn,"SELECT id_solusi_ideal FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Positif'");
                                    if(mysqli_num_rows($CekDB) == 0){
                                        mysqli_query($Conn, "INSERT INTO solusi_ideal (id_periode_penilaian, id_kriteria, positif_negatif, solusi_ideal) VALUES ('$id_periode_penilaian', '$id_kriteria', 'Positif', '$val_plus_db')");
                                    } else {
                                        mysqli_query($Conn, "UPDATE solusi_ideal SET solusi_ideal='$val_plus_db' WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Positif'");
                                    }

                                    echo '<td align="right">'.number_format($val_plus, 4, '.', '').'</td>';
                                }
                            ?>
                        </tr>
                        <tr>
                            <td class="text-left text-xs"><b>Negatif (A-)</b></td>
                            <?php
                                foreach ($kriteria_arr as $k) {
                                    $id_kriteria = $k['id_kriteria'];
                                    $val_min = $ideal_negatif[$id_kriteria];
                                    $val_min_db = round($val_min, 4);

                                    $CekDB = mysqli_query($Conn,"SELECT id_solusi_ideal FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Negatif'");
                                    if(mysqli_num_rows($CekDB) == 0){
                                        mysqli_query($Conn, "INSERT INTO solusi_ideal (id_periode_penilaian, id_kriteria, positif_negatif, solusi_ideal) VALUES ('$id_periode_penilaian', '$id_kriteria', 'Negatif', '$val_min_db')");
                                    } else {
                                        mysqli_query($Conn, "UPDATE solusi_ideal SET solusi_ideal='$val_min_db' WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Negatif'");
                                    }

                                    echo '<td align="right">'.number_format($val_min, 4, '.', '').'</td>';
                                }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">4. Jarak Solusi & Nilai Preferensi</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center"><b>No</b></th>
                            <th class="text-center"><b>Nama UMKM</b></th>
                            <th class="text-center"><b>Jarak Positif (D+)</b></th>
                            <th class="text-center"><b>Jarak Negatif (D-)</b></th>
                            <th class="text-center"><b>Nilai Preferensi (V)</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            foreach ($umkm_arr as $u) {
                                $id_umkm = $u['id_umkm'];
                                $dp_val = $d_plus[$id_umkm];
                                $dm_val = $d_min[$id_umkm];
                                $v_val  = $preferensi_v[$id_umkm];
                                
                                // DIBULATKAN KE 4 DESIMAL SAAT SIMPAN DB AGAR AMAN
                                $dp_val_db = round($dp_val, 4);
                                $dm_val_db = round($dm_val, 4);
                                $v_val_db  = round($v_val, 4);

                                $CekDB = mysqli_query($Conn,"SELECT id_preferensi FROM preferensi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm'");
                                if(mysqli_num_rows($CekDB) == 0){
                                    mysqli_query($Conn, "INSERT INTO preferensi (id_periode_penilaian, id_umkm, positif, negatif, preferensi) VALUES ('$id_periode_penilaian', '$id_umkm', '$dp_val_db', '$dm_val_db', '$v_val_db')");
                                } else {
                                    mysqli_query($Conn, "UPDATE preferensi SET positif='$dp_val_db', negatif='$dm_val_db', preferensi='$v_val_db' WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm'");
                                }
                        ?>
                                <tr>
                                    <td class="text-center text-xs"><?php echo $no++; ?></td>
                                    <td class="text-left" align="left">
                                        <b><?php echo $u['nama_umkm']; ?></b><br>
                                        <small>Pemilik: <?php echo $u['nama_pemilik']; ?></small>
                                    </td>
                                    <td align="right"><?php echo number_format($dp_val, 4, '.', ''); ?></td>
                                    <td align="right"><?php echo number_format($dm_val, 4, '.', ''); ?></td>
                                    <td align="right"><b><?php echo number_format($v_val, 4, '.', ''); ?></b></td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">5. Ranking Bantuan UMKM</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center bg-primary text-white"><b>Rank</b></th>
                            <th class="text-center bg-primary text-white"><b>Nama UMKM</b></th>
                            <th class="text-center bg-primary text-white"><b>Nama Pemilik</b></th>
                            <th class="text-center bg-primary text-white"><b>Nilai Preferensi (V)</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            foreach ($ranking_umkm as $h) {
                        ?>
                                <tr>
                                    <td class="text-center text-xs">
                                        <b class="text-success h5"><?php echo $no++; ?></b>
                                    </td>
                                    <td class="text-left" align="left"><?php echo $h['nama_umkm']; ?></td>
                                    <td class="text-left" align="left"><?php echo $h['nama_pemilik']; ?></td>
                                    <td class="text-center" align="center"><b><?php echo number_format($h['nilai_v'], 4, '.', ''); ?></b></td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-primary btn-md w-100" data-bs-toggle="modal" data-bs-target="#ModalUpdateStatusPenilaian" data-id="<?php echo $id_periode_penilaian; ?>">
                <i class="bi bi-pencil-square"></i> Update Sesi Penilaian
            </button> 
        </div>
    </div>

<?php 
    // =========================================================================
    // FITUR PERBANDINGAN NILAI AKHIR (ANP VS SWARA)
    // =========================================================================
    $cmp_metode = $metode_pembobotan;

    echo '<div class="card shadow-sm border-0 mb-5">';
    echo '  <div class="card-header bg-dark"><b class="card-title" style="color: #ffffff !important;"><i class="bi bi-bar-chart-line-fill"></i> Kesimpulan & Perbandingan Keputusan Akhir (ANP vs SWARA)</b></div>';
    echo '  <div class="card-body">';
    echo '      <div class="table-responsive">';
    echo '          <table class="table table-bordered table-hover align-middle text-center">';
    echo '              <thead class="table-light">';
    echo '                  <tr>';
    echo '                      <th>Peringkat</th>';
    echo '                      <th>Nama UMKM</th>';
    echo '                      <th class="'.($cmp_metode == 'ANP' ? 'bg-primary text-white' : '').'">Nilai Akhir (ANP)</th>';
    echo '                      <th class="'.($cmp_metode == 'SWARA' || $cmp_metode == 'TOPSIS' ? 'bg-primary text-white' : '').'">Nilai Akhir (SWARA)</th>';
    echo '                      <th>Status Keputusan</th>';
    echo '                      <th>Aksi</th>';
    echo '                  </tr>';
    echo '              </thead>';
    echo '              <tbody>';

    $y_anp = []; $y_swara = [];
    foreach ($kriteria_arr as $k) {
        $id_k = $k['id_kriteria'];
        $bobot_anp = floatval($k['bobot_anp'] ?? 0);
        $bobot_swara = floatval($k['bobot_topsis'] ?? ($k['bobot_swara'] ?? 0));
        
        foreach ($umkm_arr as $u) {
            $id_u = $u['id_umkm'];
            $r_val = $matriks_r[$id_u][$id_k]; 
            
            $y_anp[$id_u][$id_k] = $r_val * $bobot_anp;
            $y_swara[$id_u][$id_k] = $r_val * $bobot_swara;
        }
    }

    $aplus_anp = []; $amin_anp = [];
    $aplus_swara = []; $amin_swara = [];
    
    foreach ($kriteria_arr as $k) {
        $id_k = $k['id_kriteria'];
        $atribut = strtolower(trim($k['atribut'] ?? ($k['sifat'] ?? 'benefit')));
        
        $v_arr_anp = [];
        $v_arr_swara = [];
        foreach ($umkm_arr as $u) {
            $v_arr_anp[] = $y_anp[$u['id_umkm']][$id_k];
            $v_arr_swara[] = $y_swara[$u['id_umkm']][$id_k];
        }
        
        if ($atribut == 'cost' || $atribut == 'biaya') {
            $aplus_anp[$id_k] = min($v_arr_anp); $amin_anp[$id_k] = max($v_arr_anp);
            $aplus_swara[$id_k] = min($v_arr_swara); $amin_swara[$id_k] = max($v_arr_swara);
        } else {
            $aplus_anp[$id_k] = max($v_arr_anp); $amin_anp[$id_k] = min($v_arr_anp);
            $aplus_swara[$id_k] = max($v_arr_swara); $amin_swara[$id_k] = min($v_arr_swara);
        }
    }

    $cmp_hasil = [];
    foreach ($umkm_arr as $u) {
        $id_u = $u['id_umkm'];
        $dplus_anp = 0; $dmin_anp = 0;
        $dplus_swara = 0; $dmin_swara = 0;
        
        foreach ($kriteria_arr as $k) {
            $id_k = $k['id_kriteria'];
            
            $dplus_anp += pow(($y_anp[$id_u][$id_k] - $aplus_anp[$id_k]), 2);
            $dmin_anp += pow(($y_anp[$id_u][$id_k] - $amin_anp[$id_k]), 2);
            
            $dplus_swara += pow(($y_swara[$id_u][$id_k] - $aplus_swara[$id_k]), 2);
            $dmin_swara += pow(($y_swara[$id_u][$id_k] - $amin_swara[$id_k]), 2);
        }
        
        // V ANP
        $akar_dp_anp = sqrt($dplus_anp);
        $akar_dm_anp = sqrt($dmin_anp);
        $v_anp = (($akar_dp_anp + $akar_dm_anp) == 0) ? 0 : ($akar_dm_anp / ($akar_dp_anp + $akar_dm_anp));
        
        // V SWARA
        $akar_dp_swara = sqrt($dplus_swara);
        $akar_dm_swara = sqrt($dmin_swara);
        $v_swara = (($akar_dp_swara + $akar_dm_swara) == 0) ? 0 : ($akar_dm_swara / ($akar_dp_swara + $akar_dm_swara));
        
        $cmp_hasil[] = [
            'id_umkm' => $id_u,
            'nama_umkm' => $u['nama_umkm'],
            'v_anp' => $v_anp,
            'v_swara' => $v_swara,
            'v_selected' => ($cmp_metode == 'ANP') ? $v_anp : $v_swara
        ];
    }

    usort($cmp_hasil, function($a, $b) {
        if (abs($a['v_selected'] - $b['v_selected']) < 0.0000001) return 0;
        return ($a['v_selected'] < $b['v_selected']) ? 1 : -1;
    });

    $no_rank = 1;
    foreach ($cmp_hasil as $h) {
        $nilai_selected = $h['v_selected'];
        
        if ($nilai_selected >= 0.5) {
            $status_badge = '<span class="badge bg-success">Prioritas Utama</span>';
            $status_code = 'Lolos';
        } else {
            $status_badge = '<span class="badge bg-warning text-dark">Bukan Prioritas</span>';
            $status_code = 'Gagal';
        }

        echo '<tr>';
        echo '  <td><b>'.$no_rank.'</b></td>';
        echo '  <td class="text-start">'.$h['nama_umkm'].'</td>';
        
        $anp_bold = ($cmp_metode == 'ANP') ? 'fw-bold text-primary fs-6' : '';
        $swara_bold = ($cmp_metode == 'SWARA' || $cmp_metode == 'TOPSIS') ? 'fw-bold text-primary fs-6' : '';
        
        echo '  <td class="'.$anp_bold.'">'.number_format($h['v_anp'], 4, '.', '').'</td>';
        echo '  <td class="'.$swara_bold.'">'.number_format($h['v_swara'], 4, '.', '').'</td>';
        echo '  <td>'.$status_badge.'</td>';
        
        $nama_escaped = htmlspecialchars($h['nama_umkm'], ENT_QUOTES, 'UTF-8');
        echo '  <td>
                    <button type="button" class="btn btn-sm btn-info btn-rounded" title="Lihat Penjelasan Keputusan" onclick="window.tampilkanDetailHasil(\''.$nama_escaped.'\', \''.$cmp_metode.'\', \''.number_format($nilai_selected, 4, '.', '').'\', \''.$status_code.'\')">
                        <i class="bi bi-search"></i>
                    </button>
                </td>';
        echo '</tr>';
        $no_rank++;
    }

    echo '              </tbody>';
    echo '          </table>';
    echo '      </div>';
    echo '  </div>';
    echo '</div>';
?>

<div class="modal fade" id="ModalSelengkapnya" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-info-circle"></i> Detail Keputusan UMKM</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="IsiModalSelengkapnya">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Tutup Keterangan</button>
      </div>
    </div>
  </div>
</div>

<script>
window.tampilkanDetailHasil = function(nama, metode, nilai, statusCode) {
    var statusTeks = '';
    
    if (statusCode === 'Lolos') {
        statusTeks = 'Berdasarkan ambang batas (Threshold) mutlak <b>0.5000</b>, UMKM ini memiliki nilai akhir yang <b class="text-success">Memenuhi Syarat</b>. Oleh karena itu, UMKM direkomendasikan masuk kategori <br><br><span class="text-success fs-4 fw-bold">PRIORITAS UTAMA</span>.';
    } else {
        statusTeks = 'Berdasarkan ambang batas (Threshold) mutlak <b>0.5000</b>, UMKM ini memiliki nilai akhir yang <b class="text-danger">Belum Memenuhi Syarat</b>. Oleh karena itu, UMKM dialokasikan ke kategori <br><br><span class="text-warning text-dark fs-4 fw-bold">BUKAN PRIORITAS</span>.';
    }

    var htmlContent = `
        <div class="text-start" style="font-size: 15px; line-height: 1.6;">
            <p class="mb-2"><b>Nama UMKM:</b> ${nama}</p>
            <p class="mb-2"><b>Metode Bobot Acuan:</b> ${metode}</p>
            <p class="mb-3"><b>Nilai Preferensi Akhir (V):</b> <span class="text-primary fs-3 fw-bold">${nilai}</span></p>
            <hr>
            <p class="mb-2 text-decoration-underline"><b>Keterangan Ambang Batas (Threshold):</b></p>
            <p class="text-muted" style="text-align: justify;">
                Sistem pendukung keputusan ini menetapkan ambang batas mutlak bernilai <b>0.5000</b>. 
                UMKM yang berhasil memperoleh nilai akhir (V) &ge; 0.5000 akan ditetapkan sebagai Prioritas Utama untuk menerima bantuan.
            </p>
            <div class="alert alert-secondary border-0 mt-3 p-3 text-center shadow-sm">
                ${statusTeks}
            </div>
        </div>
    `;
    
    $('#IsiModalSelengkapnya').html(htmlContent);
    $('#ModalSelengkapnya').modal('show');
}
</script>

<?php 
    } else {
        echo '<div class="alert alert-danger">Mohon maaf, ID Periode Penilaian tidak ditemukan.</div>';
    }
?>