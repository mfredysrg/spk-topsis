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
        $id_periode_penilaian=$_POST['id_periode_penilaian'];
        
        $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
        $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
        
        if(empty($DataPeriodePenilaian)){
             echo '<div class="alert alert-danger">Mohon maaf, Data Periode Penilaian tidak ditemukan di database.</div>';
             exit;
        }

        $status = $DataPeriodePenilaian['status'] ?? '';
?>
    <div class="alert alert-info mb-3">
        <b><i class="bi bi-info-circle"></i> Informasi:</b> Anda sedang melihat hasil seleksi <b>Penerima Bantuan Sosial UMKM</b> menggunakan <b>TOPSIS</b> dengan bobot kriteria dari metode <b><?php echo $metode_pembobotan; ?></b>.
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">1. Matriks Keputusan & Normalisasi (Xij<sup>2</sup>)</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-2"> 
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0">
                            <thead class="">
                                <tr>
                                    <th class="text-center"><b>No</b></th>
                                    <th class="text-center"><b>Nama UMKM</b></th>
                                    <?php
                                        $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataKriteria = mysqli_fetch_array($QryKriteria);
                                            
                                            $bobot = ($metode_pembobotan == 'ANP') ? ($DataKriteria['bobot_anp'] ?? 0) : ($DataKriteria['bobot_swara'] ?? 0);
                                            $kode = $DataKriteria['kode_kriteria'] ?? '-';
                                            echo '<th class="text-center"><b>'.$kode.'</b><br>('.$bobot.')</th>';
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_umkm ASC");
                                    while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                        $id_umkm= $DataUMKM['id_umkm'] ?? 0;
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama_umkm = $DataDetailAkses['nama_umkm'] ?? '<span class="text-danger">UMKM Terhapus</span>';
                                        $nama_pemilik = $DataDetailAkses['nama_pemilik'] ?? '-';
                                ?>
                                    <tr>
                                        <td class="text-center text-xs"><?php echo "$no" ?></td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "<b>$nama_umkm</b><br>";
                                                echo "<small>Pemilik: $nama_pemilik</small>";
                                            ?>
                                        </td>
                                        <?php
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNilai = mysqli_fetch_array($QryNilai);
                                                $nilai = floatval($DataNilai['nilai'] ?? 0);
                                                $xij2 = $nilai * $nilai;
                                                echo '<td align="right">'.$xij2.'</td>';
                                            }
                                        ?>
                                    </tr>
                                <?php $no++; } ?>
                                <tr>
                                    <td class="text-center text-xs" colspan="2"><b>Jumlah (∑i-1)</b></td>
                                    <?php
                                        $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $JumlahNormalisasi=0;
                                            $QryUMKM2 = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_umkm ASC");
                                            while ($DataUMKM2 = mysqli_fetch_array($QryUMKM2)) {
                                                $id_umkm_val= $DataUMKM2['id_umkm'];
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm_val' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNilai = mysqli_fetch_array($QryNilai);
                                                $nilai = floatval($DataNilai['nilai'] ?? 0);
                                                $JumlahNormalisasi += ($nilai*$nilai);
                                            }
                                            echo '<td align="right">'.$JumlahNormalisasi.'</td>';
                                        }
                                    ?>
                                </tr>
                                <tr>
                                    <td class="text-center text-xs" colspan="2"><b>SQRT (∑i-1)</b></td>
                                    <?php
                                        $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $JumlahNormalisasi=0;
                                            $QryUMKM2 = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_umkm ASC");
                                            while ($DataUMKM2 = mysqli_fetch_array($QryUMKM2)) {
                                                $id_umkm_val= $DataUMKM2['id_umkm'];
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm_val' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNilai = mysqli_fetch_array($QryNilai);
                                                $nilai = floatval($DataNilai['nilai'] ?? 0);
                                                $JumlahNormalisasi += ($nilai*$nilai);
                                            }
                                            $SqrtNormalisasi = floatval(sqrt($JumlahNormalisasi));
                                            
                                            $QryNormalisasi = mysqli_query($Conn,"SELECT * FROM normalisasi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataNormalisasi = mysqli_fetch_array($QryNormalisasi);
                                            if(empty($DataNormalisasi['id_normalisasi'])){
                                                $entry="INSERT INTO normalisasi (id_periode_penilaian, id_kriteria, normalisasi, sqrt_normalisasi) VALUES ('$id_periode_penilaian', '$id_kriteria', '$JumlahNormalisasi', '$SqrtNormalisasi')";
                                                mysqli_query($Conn, $entry);
                                            }else{
                                                $id_normalisasi =$DataNormalisasi['id_normalisasi'];
                                                mysqli_query($Conn,"UPDATE normalisasi SET normalisasi='$JumlahNormalisasi', sqrt_normalisasi='$SqrtNormalisasi' WHERE id_normalisasi='$id_normalisasi'") or die(mysqli_error($Conn)); 
                                            }
                                            echo '<td align="right">'.round($SqrtNormalisasi, 6).'</td>';
                                        }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">2. Normaliasai Terbobot (Xij/SQRT (∑i-1))*Bobot</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-2"> 
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0">
                            <thead class="">
                                <tr>
                                    <th class="text-center"><b>No</b></th>
                                    <th class="text-center"><b>Nama UMKM</b></th>
                                    <?php
                                        $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataKriteria = mysqli_fetch_array($QryKriteria);
                                            $bobot = ($metode_pembobotan == 'ANP') ? ($DataKriteria['bobot_anp']??0) : ($DataKriteria['bobot_swara']??0);
                                            $kode = $DataKriteria['kode_kriteria'] ?? '-';
                                            echo '<th class="text-center"><b>'.$kode.'</b><br>('.$bobot.')</th>';
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_umkm ASC");
                                    while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                        $id_umkm= $DataUMKM['id_umkm'] ?? 0;
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama_umkm = $DataDetailAkses['nama_umkm'] ?? '<span class="text-danger">UMKM Terhapus</span>';
                                        $nama_pemilik = $DataDetailAkses['nama_pemilik'] ?? '-';
                                ?>
                                    <tr>
                                        <td class="text-center text-xs"><?php echo "$no" ?></td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "<b>$nama_umkm</b><br>";
                                                echo "<small>Pemilik: $nama_pemilik</small>";
                                            ?>
                                        </td>
                                        <?php
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                                
                                                $bobot = floatval(($metode_pembobotan == 'ANP') ? ($DataKriteria['bobot_anp']??0) : ($DataKriteria['bobot_swara']??0));
                                                
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNilai = mysqli_fetch_array($QryNilai);
                                                $nilai = floatval($DataNilai['nilai'] ?? 0);
                                                
                                                $QryNormalisasi = mysqli_query($Conn,"SELECT * FROM normalisasi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNormalisasi = mysqli_fetch_array($QryNormalisasi);
                                                $sqrt_normalisasi = floatval($DataNormalisasi['sqrt_normalisasi'] ?? 0);

                                                if($sqrt_normalisasi == 0){
                                                    $NilaiNormalisasi = 0;
                                                } else {
                                                    $NilaiNormalisasi = $nilai / $sqrt_normalisasi;
                                                }

                                                $NormalisasiTerbobot = floatval($NilaiNormalisasi * $bobot);
                                                $PembulatanNormalisasiTerbobot = round($NormalisasiTerbobot, 6);
                                                
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                                $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                                if(empty($DataNormalisasiTerbobot['id_normalisasi_terbobot'])){
                                                    $entry="INSERT INTO normalisasi_terbobot (id_periode_penilaian, id_kriteria, id_umkm, normalisasi_terbobot) VALUES ('$id_periode_penilaian', '$id_kriteria', '$id_umkm', '$PembulatanNormalisasiTerbobot')";
                                                    mysqli_query($Conn, $entry);
                                                }else{
                                                    $id_normalisasi_terbobot =$DataNormalisasiTerbobot['id_normalisasi_terbobot'];
                                                    mysqli_query($Conn,"UPDATE normalisasi_terbobot SET normalisasi_terbobot='$PembulatanNormalisasiTerbobot' WHERE id_normalisasi_terbobot='$id_normalisasi_terbobot'") or die(mysqli_error($Conn)); 
                                                }
                                                echo '<td align="right">';
                                                echo '<span class="text-success">'.$PembulatanNormalisasiTerbobot.'</span><br>';
                                                echo '</td>';
                                            }
                                        ?>
                                    </tr>
                                <?php $no++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">3. Metrik Solusi Ideal Positif & Negatif</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-2"> 
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0">
                            <thead class="">
                                <tr>
                                    <th class="text-center"><b>#</b></th>
                                    <?php
                                        $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataKriteria = mysqli_fetch_array($QryKriteria);
                                            $bobot = ($metode_pembobotan == 'ANP') ? ($DataKriteria['bobot_anp']??0) : ($DataKriteria['bobot_swara']??0);
                                            $kode = $DataKriteria['kode_kriteria'] ?? '-';
                                            echo '<th class="text-center"><b>'.$kode.'</b><br>('.$bobot.')</th>';
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left text-xs">
                                        <b>Positif (A+)</b><br>
                                    </td>
                                    <?php
                                        $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataKriteria = mysqli_fetch_array($QryKriteria);
                                            $atribut = $DataKriteria['atribut'] ?? 'Benefit'; 
                                            
                                            // Trik PHP Array Filter untuk Mencegah Nilai 0 (Kosong)
                                            $V_array = [];
                                            $QryV = mysqli_query($Conn, "SELECT normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND normalisasi_terbobot > 0");
                                            while($rowV = mysqli_fetch_array($QryV)){
                                                $V_array[] = floatval($rowV['normalisasi_terbobot']);
                                            }
                                            $max_v = !empty($V_array) ? max($V_array) : 0;
                                            $min_v = !empty($V_array) ? min($V_array) : 0;

                                            // Logika Asli TOPSIS (Cost/Benefit)
                                            if($atribut == "Benefit"){
                                                $MinMaks = $max_v;
                                            } else {
                                                $MinMaks = $min_v;
                                            }
                                            
                                            $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Positif'")or die(mysqli_error($Conn));
                                            $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                            if(empty($DataSolusiIdeal['id_solusi_ideal'])){
                                                $entry="INSERT INTO solusi_ideal (id_periode_penilaian, id_kriteria, positif_negatif, solusi_ideal) VALUES ('$id_periode_penilaian', '$id_kriteria', 'Positif', '$MinMaks')";
                                                mysqli_query($Conn, $entry);
                                            }else{
                                                $id_solusi_ideal=$DataSolusiIdeal['id_solusi_ideal'];
                                                mysqli_query($Conn,"UPDATE solusi_ideal SET solusi_ideal='$MinMaks' WHERE id_solusi_ideal='$id_solusi_ideal'") or die(mysqli_error($Conn)); 
                                            }
                                            echo '<td align="right">'.round($MinMaks, 6).'</td>';
                                        }
                                    ?>
                                </tr>
                                <tr>
                                    <td class="text-left text-xs">
                                        <b>Negatif (A-)</b><br>
                                    </td>
                                    <?php
                                        $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataKriteria = mysqli_fetch_array($QryKriteria);
                                            $atribut = $DataKriteria['atribut'] ?? 'Benefit'; 
                                            
                                            // Trik PHP Array Filter untuk Mencegah Nilai 0 (Kosong)
                                            $V_array = [];
                                            $QryV = mysqli_query($Conn, "SELECT normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND normalisasi_terbobot > 0");
                                            while($rowV = mysqli_fetch_array($QryV)){
                                                $V_array[] = floatval($rowV['normalisasi_terbobot']);
                                            }
                                            $max_v = !empty($V_array) ? max($V_array) : 0;
                                            $min_v = !empty($V_array) ? min($V_array) : 0;

                                            // Logika Asli TOPSIS (Cost/Benefit)
                                            if($atribut == "Cost"){
                                                $MinMaks = $max_v;
                                            } else {
                                                $MinMaks = $min_v;
                                            }
                                            
                                            $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Negatif'")or die(mysqli_error($Conn));
                                            $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                            if(empty($DataSolusiIdeal['id_solusi_ideal'])){
                                                $entry="INSERT INTO solusi_ideal (id_periode_penilaian, id_kriteria, positif_negatif, solusi_ideal) VALUES ('$id_periode_penilaian', '$id_kriteria', 'Negatif', '$MinMaks')";
                                                mysqli_query($Conn, $entry);
                                            }else{
                                                $id_solusi_ideal=$DataSolusiIdeal['id_solusi_ideal'];
                                                mysqli_query($Conn,"UPDATE solusi_ideal SET solusi_ideal='$MinMaks' WHERE id_solusi_ideal='$id_solusi_ideal'") or die(mysqli_error($Conn)); 
                                            }
                                            echo '<td align="right">'.round($MinMaks, 6).'</td>';
                                        }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">4. Jarak Solusi & Nilai Preferensi</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-2"> 
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0">
                            <thead class="">
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
                                    $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_umkm ASC");
                                    while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                        $id_umkm= $DataUMKM['id_umkm'] ?? 0;
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama_umkm = $DataDetailAkses['nama_umkm'] ?? '<span class="text-danger">UMKM Terhapus</span>';
                                        $nama_pemilik = $DataDetailAkses['nama_pemilik'] ?? '-';
                                ?>
                                    <tr>
                                        <td class="text-center text-xs"><?php echo "$no" ?></td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "<b>$nama_umkm</b><br>";
                                                echo "<small>Pemilik: $nama_pemilik</small>";
                                            ?>
                                        </td>
                                        <?php
                                            $JumlahPreferensiPositif=0;
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                                $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                                $normalisasi_terbobot= floatval($DataNormalisasiTerbobot['normalisasi_terbobot'] ?? 0);
                                                
                                                $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Positif'")or die(mysqli_error($Conn));
                                                $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                                $solusi_ideal= floatval($DataSolusiIdeal['solusi_ideal'] ?? 0);
                                                
                                                $NormalisasiSolusi=$normalisasi_terbobot-$solusi_ideal;
                                                $NormalisasiSolusiKuadrat=$NormalisasiSolusi*$NormalisasiSolusi;
                                                $JumlahPreferensiPositif += $NormalisasiSolusiKuadrat;
                                            }
                                            $AkarJumlahPreferensiPositif = floatval(sqrt($JumlahPreferensiPositif));
                                            $AkarJumlahPreferensiPositifBulat=round($AkarJumlahPreferensiPositif,6);
                                            echo '<td align="right">'.$AkarJumlahPreferensiPositifBulat.'</td>';
                                        ?>
                                        <?php
                                            $JumlahPreferensiNegatif=0;
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                                $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                                $normalisasi_terbobot= floatval($DataNormalisasiTerbobot['normalisasi_terbobot'] ?? 0);
                                                
                                                $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Negatif'")or die(mysqli_error($Conn));
                                                $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                                $solusi_ideal= floatval($DataSolusiIdeal['solusi_ideal'] ?? 0);
                                                
                                                $NormalisasiSolusi=$normalisasi_terbobot-$solusi_ideal;
                                                $NormalisasiSolusiKuadrat=$NormalisasiSolusi*$NormalisasiSolusi;
                                                $JumlahPreferensiNegatif += $NormalisasiSolusiKuadrat;
                                            }
                                            $AkarJumlahPreferensiNegatif= floatval(sqrt($JumlahPreferensiNegatif));
                                            $AkarJumlahPreferensiNegatifBulat=round($AkarJumlahPreferensiNegatif,6);
                                            echo '<td align="right">'.$AkarJumlahPreferensiNegatifBulat.'</td>';

                                            $AkumulasiPositifNegatif = floatval($AkarJumlahPreferensiNegatif+$AkarJumlahPreferensiPositif);

                                            if($AkumulasiPositifNegatif == 0){
                                                $Preferensi = 0;
                                            } else {
                                                $Preferensi = floatval($AkarJumlahPreferensiNegatif / $AkumulasiPositifNegatif);
                                            }

                                            $PreferensiBulat = round($Preferensi,6);
                                            
                                            $QryPreferensi = mysqli_query($Conn,"SELECT * FROM preferensi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                            $DataPreferensi = mysqli_fetch_array($QryPreferensi);
                                            if(empty($DataPreferensi['id_preferensi'])){
                                                $entry="INSERT INTO preferensi (id_periode_penilaian, id_umkm, positif, negatif, preferensi) VALUES ('$id_periode_penilaian', '$id_umkm', '$AkarJumlahPreferensiPositifBulat', '$AkarJumlahPreferensiNegatifBulat', '$PreferensiBulat')";
                                                mysqli_query($Conn, $entry);
                                            }else{
                                                $id_preferensi=$DataPreferensi['id_preferensi'];
                                                mysqli_query($Conn,"UPDATE preferensi SET preferensi='$PreferensiBulat' WHERE id_preferensi='$id_preferensi'") or die(mysqli_error($Conn)); 
                                            }
                                            echo '<td align="right"><b>'.$PreferensiBulat.'</b></td>';
                                        ?>
                                    </tr>
                                <?php $no++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
            <div class="row mt-2"> 
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0">
                            <thead class="">
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
                                    $QryPreferensi = mysqli_query($Conn, "SELECT * FROM preferensi WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY preferensi DESC");
                                    while ($DataPreferensi = mysqli_fetch_array($QryPreferensi)) {
                                        $id_umkm= $DataPreferensi['id_umkm'] ?? 0;
                                        $preferensi= $DataPreferensi['preferensi'] ?? 0;
                                        
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama_umkm = $DataDetailAkses['nama_umkm'] ?? '<span class="text-danger">UMKM Terhapus</span>';
                                        $nama_pemilik = $DataDetailAkses['nama_pemilik'] ?? '-';
                                ?>
                                    <tr>
                                        <td class="text-center text-xs">
                                            <b class="text-success h5"><?php echo "$no" ?></b>
                                        </td>
                                        <td class="text-left" align="left"><?php echo "$nama_umkm"; ?></td>
                                        <td class="text-left" align="left"><?php echo "$nama_pemilik"; ?></td>
                                        <td class="text-center" align="center"><b><?php echo "$preferensi"; ?></b></td>
                                    </tr>
                                <?php $no++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-primary btn-md w-100" data-bs-toggle="modal" data-bs-target="#ModalUpdateStatusPenilaian" data-id="<?php echo "$id_periode_penilaian"; ?>">
                <i class="bi bi-pencil-square"></i> Update Sesi Penilaian
            </button> 
        </div>
    </div>

<?php 
    // =========================================================================
    // FITUR TAMBAHAN: TABEL PERBANDINGAN NILAI AKHIR (ANP VS SWARA)
    // =========================================================================
    
    $cmp_id_periode = $id_periode_penilaian;
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

    // 1. Ambil data kriteria
    $cmp_kriteria = [];
    $QryKrit = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
    while ($k = mysqli_fetch_array($QryKrit)) {
        $cmp_kriteria[] = $k;
    }

    // 2. Ambil data UMKM (Memastikan semua UMKM diambil dari daftar input tabel Penilaian)
    $cmp_umkm = [];
    $QryUmkm = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai WHERE id_periode_penilaian='$cmp_id_periode' ORDER BY id_umkm ASC");
    while ($u = mysqli_fetch_array($QryUmkm)) {
        $id_u = $u['id_umkm'];
        $detail_u = mysqli_fetch_array(mysqli_query($Conn, "SELECT nama_umkm FROM umkm WHERE id_umkm='$id_u'"));
        $cmp_umkm[$id_u] = [
            'id_umkm' => $id_u,
            'nama_umkm' => isset($detail_u['nama_umkm']) ? $detail_u['nama_umkm'] : '<span class="text-danger">UMKM Terhapus</span>',
            'nilai' => []
        ];
    }
    
    // Inisialisasi nilai 0 agar tidak terjadi error missing key / undefinied
    foreach ($cmp_umkm as $id_u => $u) {
        foreach ($cmp_kriteria as $k) {
            $cmp_umkm[$id_u]['nilai'][$k['id_kriteria']] = 0;
        }
    }

    // 3. Matriks Pembagi (Akar Sum Kuadrat)
    $cmp_pembagi = []; 
    foreach ($cmp_kriteria as $k) {
        $id_k = $k['id_kriteria'];
        $sum_kuadrat = 0;
        foreach ($cmp_umkm as $id_u => $u) {
            $QryNilai = mysqli_query($Conn, "SELECT nilai FROM nilai WHERE id_periode_penilaian='$cmp_id_periode' AND id_umkm='$id_u' AND id_kriteria='$id_k'");
            $DataNilai = mysqli_fetch_array($QryNilai);
            if ($DataNilai) {
                $x = floatval($DataNilai['nilai']);
                $cmp_umkm[$id_u]['nilai'][$id_k] = $x;
                $sum_kuadrat += ($x * $x);
            }
        }
        $cmp_pembagi[$id_k] = floatval(sqrt($sum_kuadrat));
    }

    // 4. Normalisasi Terbobot (Identik Dengan Rumus Pembulatan PHP Asli)
    $y_anp = []; $y_swara = [];
    foreach ($cmp_kriteria as $k) {
        $id_k = $k['id_kriteria'];
        $bobot_anp = floatval($k['bobot_anp'] ?? 0);
        $bobot_swara = floatval($k['bobot_topsis'] ?? ($k['bobot_swara'] ?? 0));
        
        foreach ($cmp_umkm as $id_u => $u) {
            $x = $cmp_umkm[$id_u]['nilai'][$id_k];
            $pembagi = $cmp_pembagi[$id_k];
            
            $norm = ($pembagi == 0) ? 0 : ($x / $pembagi);
            
            // Kunci Nilai Normalisasi Terbobot agar identik 100% sama dgn tabel Dapur Perhitungan
            $y_anp[$id_u][$id_k] = round($norm * $bobot_anp, 6);
            $y_swara[$id_u][$id_k] = round($norm * $bobot_swara, 6);
        }
    }

    // 5. Solusi Ideal Positif & Negatif (A+ dan A-)
    $aplus_anp = []; $amin_anp = [];
    $aplus_swara = []; $amin_swara = [];
    
    foreach ($cmp_kriteria as $k) {
        $id_k = $k['id_kriteria'];
        $atribut = $k['atribut'] ?? ($k['sifat'] ?? 'Benefit');
        
        $v_arr_anp = [];
        $v_arr_swara = [];
        
        foreach ($cmp_umkm as $id_u => $u) {
            if ($y_anp[$id_u][$id_k] > 0) { $v_arr_anp[] = $y_anp[$id_u][$id_k]; }
            if ($y_swara[$id_u][$id_k] > 0) { $v_arr_swara[] = $y_swara[$id_u][$id_k]; }
        }
        
        $max_anp = !empty($v_arr_anp) ? max($v_arr_anp) : 0;
        $min_anp = !empty($v_arr_anp) ? min($v_arr_anp) : 0;
        
        $max_swara = !empty($v_arr_swara) ? max($v_arr_swara) : 0;
        $min_swara = !empty($v_arr_swara) ? min($v_arr_swara) : 0;

        if (strtolower($atribut) == 'cost' || strtolower($atribut) == 'biaya') {
            $aplus_anp[$id_k] = $min_anp; $amin_anp[$id_k] = $max_anp;
            $aplus_swara[$id_k] = $min_swara; $amin_swara[$id_k] = $max_swara;
        } else {
            $aplus_anp[$id_k] = $max_anp; $amin_anp[$id_k] = $min_anp;
            $aplus_swara[$id_k] = $max_swara; $amin_swara[$id_k] = $min_swara;
        }
    }

    // 6. Jarak Solusi Ideal & Preferensi (V)
    $cmp_hasil = [];
    foreach ($cmp_umkm as $id_u => $u) {
        $dplus_anp = 0; $dmin_anp = 0;
        $dplus_swara = 0; $dmin_swara = 0;
        
        foreach ($cmp_kriteria as $k) {
            $id_k = $k['id_kriteria'];
            
            // ANP
            $diff_plus_anp = $y_anp[$id_u][$id_k] - $aplus_anp[$id_k];
            $dplus_anp += ($diff_plus_anp * $diff_plus_anp);
            $diff_min_anp = $y_anp[$id_u][$id_k] - $amin_anp[$id_k];
            $dmin_anp += ($diff_min_anp * $diff_min_anp);
            
            // SWARA
            $diff_plus_swara = $y_swara[$id_u][$id_k] - $aplus_swara[$id_k];
            $dplus_swara += ($diff_plus_swara * $diff_plus_swara);
            $diff_min_swara = $y_swara[$id_u][$id_k] - $amin_swara[$id_k];
            $dmin_swara += ($diff_min_swara * $diff_min_swara);
        }
        
        // Kalkulasi Preferensi (V) ANP
        $akar_dplus_anp = floatval(sqrt($dplus_anp));
        $akar_dmin_anp = floatval(sqrt($dmin_anp));
        $akum_anp = floatval($akar_dmin_anp + $akar_dplus_anp);
        $v_anp = ($akum_anp == 0) ? 0 : floatval($akar_dmin_anp / $akum_anp);
        $v_anp_final = round($v_anp, 6);
        
        // Kalkulasi Preferensi (V) SWARA
        $akar_dplus_swara = floatval(sqrt($dplus_swara));
        $akar_dmin_swara = floatval(sqrt($dmin_swara));
        $akum_swara = floatval($akar_dmin_swara + $akar_dplus_swara);
        $v_swara = ($akum_swara == 0) ? 0 : floatval($akar_dmin_swara / $akum_swara);
        $v_swara_final = round($v_swara, 6);
        
        // Simpan Hasil ke dalam array
        $cmp_hasil[] = [
            'id_umkm' => $id_u,
            'nama_umkm' => $u['nama_umkm'],
            'v_anp' => $v_anp_final,
            'v_swara' => $v_swara_final,
            'v_selected' => ($cmp_metode == 'ANP') ? $v_anp_final : $v_swara_final
        ];
    }

    // 7. Pengurutan (Sorting) Kebal Error Untuk Tipe Angka Float
    usort($cmp_hasil, function($a, $b) {
        if (abs($a['v_selected'] - $b['v_selected']) < 0.0000001) return 0;
        return ($a['v_selected'] < $b['v_selected']) ? 1 : -1;
    });

    // 8. Render Tabel
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
        
        echo '  <td>
                    <button type="button" class="btn btn-sm btn-info btn-rounded" title="Lihat Penjelasan Keputusan" onclick="window.tampilkanDetailHasil(\''.$h['nama_umkm'].'\', \''.$cmp_metode.'\', \''.number_format($nilai_selected, 4, '.', '').'\', \''.$status_code.'\')">
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
    }else{
        echo '<div class="alert alert-danger">Mohon maaf, ID Periode Penilaian tidak ditemukan.</div>';
    }
?>