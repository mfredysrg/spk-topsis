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
    
    // Menangkap metode yang dikirim dari Javascript
    $metode_pembobotan = $_POST['metode_pembobotan'];
    
    // Simpan ke Session untuk keperluan cetak laporan
    $_SESSION['metode_terakhir'] = $metode_pembobotan;

    if(!empty($_POST['id_periode_penilaian'])){
        $id_periode_penilaian=$_POST['id_periode_penilaian'];
        //Buka detail periode penilaian
        $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
        $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
        
        // [REVISI] Pengecekan Null Safety untuk DataPeriodePenilaian
        if(empty($DataPeriodePenilaian)){
             echo '<div class="alert alert-danger">Mohon maaf, Data Periode Penilaian tidak ditemukan di database.</div>';
             exit;
        }

        // Jika data ada, barulah statusnya diambil
        $status = $DataPeriodePenilaian['status'];
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
                                        if($status=="Proses"){
                                            $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                // [REVISI] Ambil bobot berdasarkan metode
                                                $bobot = ($metode_pembobotan == 'ANP') ? $data['bobot_anp'] : $data['bobot_swara'];
                                                echo '<th class="text-center"><b>'.$data['kode_kriteria'].'</b><br>('.$bobot.')</th>';
                                            }
                                        }else{
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                                
                                                // [REVISI] Ambil bobot berdasarkan metode
                                                $bobot = ($metode_pembobotan == 'ANP') ? $DataKriteria['bobot_anp'] : $DataKriteria['bobot_swara'];
                                                echo '<th class="text-center"><b>'.$DataKriteria['kode_kriteria'].'</b><br>('.$bobot.')</th>';
                                            }
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    if($status=="Proses"){
                                        $QryUMKM = mysqli_query($Conn, "SELECT * FROM umkm ORDER BY id_umkm ASC");
                                    }else{
                                        $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai ORDER BY id_umkm ASC");
                                    }
                                    while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                        $id_umkm= $DataUMKM['id_umkm'];
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama_umkm = $DataDetailAkses['nama_umkm'];
                                        $nama_pemilik = $DataDetailAkses['nama_pemilik'];
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
                                            if($status=="Proses"){
                                                $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                                while ($data = mysqli_fetch_array($query)) {
                                                    $id_kriteria= $data['id_kriteria'];
                                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                    $DataNilai = mysqli_fetch_array($QryNilai);
                                                    $nilai = empty($DataNilai['nilai']) ? 0 : $DataNilai['nilai'];
                                                    $xij2=$nilai*$nilai;
                                                    echo '<td align="right">'.number_format($xij2,0,',','.').'</td>';
                                                }
                                            }else{
                                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                                while ($data = mysqli_fetch_array($query)) {
                                                    $id_kriteria= $data['id_kriteria'];
                                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                    $DataNilai = mysqli_fetch_array($QryNilai);
                                                    $nilai = empty($DataNilai['nilai']) ? 0 : $DataNilai['nilai'];
                                                    $xij2=$nilai*$nilai;
                                                    echo '<td align="right">'.number_format($xij2,0,',','.').'</td>';
                                                }
                                            }
                                        ?>
                                    </tr>
                                <?php $no++; } ?>
                                <tr>
                                    <td class="text-center text-xs" colspan="2"><b>Jumlah (∑i-1)</b></td>
                                    <?php
                                        if($status=="Proses"){
                                            $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                        } else {
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        }
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $JumlahNormalisasi=0;
                                            if($status=="Proses"){
                                                $QryUMKM2 = mysqli_query($Conn, "SELECT * FROM umkm ORDER BY id_umkm ASC");
                                            }else{
                                                $QryUMKM2 = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai ORDER BY id_umkm ASC");
                                            }
                                            while ($DataUMKM2 = mysqli_fetch_array($QryUMKM2)) {
                                                $id_umkm_val= $DataUMKM2['id_umkm'];
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm_val' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNilai = mysqli_fetch_array($QryNilai);
                                                $nilai = empty($DataNilai['nilai']) ? 0 : $DataNilai['nilai'];
                                                $JumlahNormalisasi += ($nilai*$nilai);
                                            }
                                            echo '<td align="right">'.number_format($JumlahNormalisasi,0,',','.').'</td>';
                                        }
                                    ?>
                                </tr>
                                <tr>
                                    <td class="text-center text-xs" colspan="2"><b>SQRT (∑i-1)</b></td>
                                    <?php
                                        if($status=="Proses"){
                                            $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                        }else{
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        }
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $JumlahNormalisasi=0;
                                            if($status=="Proses"){
                                                $QryUMKM2 = mysqli_query($Conn, "SELECT * FROM umkm ORDER BY id_umkm ASC");
                                            }else{
                                                $QryUMKM2 = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai ORDER BY id_umkm ASC");
                                            }
                                            while ($DataUMKM2 = mysqli_fetch_array($QryUMKM2)) {
                                                $id_umkm_val= $DataUMKM2['id_umkm'];
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm_val' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNilai = mysqli_fetch_array($QryNilai);
                                                $nilai = empty($DataNilai['nilai']) ? 0 : $DataNilai['nilai'];
                                                $JumlahNormalisasi += ($nilai*$nilai);
                                            }
                                            $SqrtNormalisasi=sqrt($JumlahNormalisasi);
                                            
                                            $QryNormalisasi = mysqli_query($Conn,"SELECT * FROM normalisasi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataNormalisasi = mysqli_fetch_array($QryNormalisasi);
                                            if(empty($DataNormalisasi['id_normalisasi'])){
                                                $entry="INSERT INTO normalisasi (id_periode_penilaian, id_kriteria, normalisasi, sqrt_normalisasi) VALUES ('$id_periode_penilaian', '$id_kriteria', '$JumlahNormalisasi', '$SqrtNormalisasi')";
                                                mysqli_query($Conn, $entry);
                                            }else{
                                                $id_normalisasi =$DataNormalisasi['id_normalisasi'];
                                                mysqli_query($Conn,"UPDATE normalisasi SET normalisasi='$JumlahNormalisasi', sqrt_normalisasi='$SqrtNormalisasi' WHERE id_normalisasi='$id_normalisasi'") or die(mysqli_error($Conn)); 
                                            }
                                            echo '<td align="right">'.number_format($SqrtNormalisasi,2,',','.').'</td>';
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
                                        if($status=="Proses"){
                                            $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $bobot = ($metode_pembobotan == 'ANP') ? $data['bobot_anp'] : $data['bobot_swara'];
                                                echo '<th class="text-center"><b>'.$data['kode_kriteria'].'</b><br>('.$bobot.')</th>';
                                            }
                                        }else{
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                                $bobot = ($metode_pembobotan == 'ANP') ? $DataKriteria['bobot_anp'] : $DataKriteria['bobot_swara'];
                                                echo '<th class="text-center"><b>'.$DataKriteria['kode_kriteria'].'</b><br>('.$bobot.')</th>';
                                            }
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    if($status=="Proses"){
                                        $QryUMKM = mysqli_query($Conn, "SELECT * FROM umkm ORDER BY id_umkm ASC");
                                    }else{
                                        $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai ORDER BY id_umkm ASC");
                                    }
                                    while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                        $id_umkm= $DataUMKM['id_umkm'];
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama_umkm = $DataDetailAkses['nama_umkm'];
                                        $nama_pemilik = $DataDetailAkses['nama_pemilik'];
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
                                            if($status=="Proses"){
                                                $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                            }else{
                                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            }
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                                
                                                $bobot = ($metode_pembobotan == 'ANP') ? $DataKriteria['bobot_anp'] : $DataKriteria['bobot_swara'];
                                                
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNilai = mysqli_fetch_array($QryNilai);
                                                $nilai = empty($DataNilai['nilai']) ? 0 : $DataNilai['nilai'];
                                                
                                                $QryNormalisasi = mysqli_query($Conn,"SELECT * FROM normalisasi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNormalisasi = mysqli_fetch_array($QryNormalisasi);
                                                $sqrt_normalisasi = empty($DataNormalisasi['sqrt_normalisasi']) ? 0 : $DataNormalisasi['sqrt_normalisasi'];

                                                // Mencegah Division by Zero
                                                if($sqrt_normalisasi == 0){
                                                    $NilaiNormalisasi = 0;
                                                } else {
                                                    $NilaiNormalisasi = $nilai / $sqrt_normalisasi;
                                                }

                                                $NormalisasiTerbobot=$NilaiNormalisasi*$bobot;
                                                $PembulatanNormalisasiTerbobot =round($NormalisasiTerbobot,4);
                                                
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
                                        if($status=="Proses"){
                                            $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                        }else{
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        }
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataKriteria = mysqli_fetch_array($QryKriteria);
                                            $bobot = ($metode_pembobotan == 'ANP') ? $DataKriteria['bobot_anp'] : $DataKriteria['bobot_swara'];
                                            echo '<th class="text-center"><b>'.$DataKriteria['kode_kriteria'].'</b><br>('.$bobot.')</th>';
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
                                        if($status=="Proses"){
                                            $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                        }else{
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        }
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataKriteria = mysqli_fetch_array($QryKriteria);
                                            $atribut= $DataKriteria['atribut'];
                                            
                                            if($atribut=="Benefit"){
                                                $QryMaks=mysqli_query($Conn, "SELECT max(normalisasi_terbobot) as normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $NilaiMaks=mysqli_fetch_array($QryMaks);
                                                $MinMaks= empty($NilaiMaks['normalisasi_terbobot']) ? 0 : $NilaiMaks['normalisasi_terbobot'];
                                            }else{
                                                $QryMin=mysqli_query($Conn, "SELECT min(normalisasi_terbobot) as normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $NilaiMin=mysqli_fetch_array($QryMin);
                                                $MinMaks= empty($NilaiMin['normalisasi_terbobot']) ? 0 : $NilaiMin['normalisasi_terbobot'];
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
                                            echo '<td align="right">'.$MinMaks.'</td>';
                                        }
                                    ?>
                                </tr>
                                <tr>
                                    <td class="text-left text-xs">
                                        <b>Negatif (A-)</b><br>
                                    </td>
                                    <?php
                                        if($status=="Proses"){
                                            $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                        }else{
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        }
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataKriteria = mysqli_fetch_array($QryKriteria);
                                            $atribut= $DataKriteria['atribut'];
                                            
                                            if($atribut=="Cost"){
                                                $QryMaks=mysqli_query($Conn, "SELECT max(normalisasi_terbobot) as normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $NilaiMaks=mysqli_fetch_array($QryMaks);
                                                $MinMaks= empty($NilaiMaks['normalisasi_terbobot']) ? 0 : $NilaiMaks['normalisasi_terbobot'];
                                            }else{
                                                $QryMin=mysqli_query($Conn, "SELECT min(normalisasi_terbobot) as normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $NilaiMin=mysqli_fetch_array($QryMin);
                                                $MinMaks= empty($NilaiMin['normalisasi_terbobot']) ? 0 : $NilaiMin['normalisasi_terbobot'];
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
                                            echo '<td align="right">'.$MinMaks.'</td>';
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
                                    if($status=="Proses"){
                                        $QryUMKM = mysqli_query($Conn, "SELECT * FROM umkm ORDER BY id_umkm ASC");
                                    }else{
                                        $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai ORDER BY id_umkm ASC");
                                    }
                                    while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                        $id_umkm= $DataUMKM['id_umkm'];
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama_umkm = $DataDetailAkses['nama_umkm'];
                                        $nama_pemilik = $DataDetailAkses['nama_pemilik'];
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
                                            if($status=="Proses"){
                                                $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                            }else{
                                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            }
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                                $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                                $normalisasi_terbobot= empty($DataNormalisasiTerbobot['normalisasi_terbobot']) ? 0 : $DataNormalisasiTerbobot['normalisasi_terbobot'];
                                                
                                                $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Positif'")or die(mysqli_error($Conn));
                                                $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                                $solusi_ideal= empty($DataSolusiIdeal['solusi_ideal']) ? 0 : $DataSolusiIdeal['solusi_ideal'];
                                                
                                                $NormalisasiSolusi=$normalisasi_terbobot-$solusi_ideal;
                                                $NormalisasiSolusiKuadrat=$NormalisasiSolusi*$NormalisasiSolusi;
                                                $JumlahPreferensiPositif += $NormalisasiSolusiKuadrat;
                                            }
                                            $AkarJumlahPreferensiPositif=sqrt($JumlahPreferensiPositif);
                                            $AkarJumlahPreferensiPositifBulat=round($AkarJumlahPreferensiPositif,4);
                                            echo '<td align="right">'.$AkarJumlahPreferensiPositifBulat.'</td>';
                                        ?>
                                        <?php
                                            $JumlahPreferensiNegatif=0;
                                            if($status=="Proses"){
                                                $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                            }else{
                                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            }
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                                $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                                $normalisasi_terbobot= empty($DataNormalisasiTerbobot['normalisasi_terbobot']) ? 0 : $DataNormalisasiTerbobot['normalisasi_terbobot'];
                                                
                                                $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Negatif'")or die(mysqli_error($Conn));
                                                $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                                $solusi_ideal= empty($DataSolusiIdeal['solusi_ideal']) ? 0 : $DataSolusiIdeal['solusi_ideal'];
                                                
                                                $NormalisasiSolusi=$normalisasi_terbobot-$solusi_ideal;
                                                $NormalisasiSolusiKuadrat=$NormalisasiSolusi*$NormalisasiSolusi;
                                                $JumlahPreferensiNegatif += $NormalisasiSolusiKuadrat;
                                            }
                                            $AkarJumlahPreferensiNegatif=sqrt($JumlahPreferensiNegatif);
                                            $AkarJumlahPreferensiNegatifBulat=round($AkarJumlahPreferensiNegatif,4);
                                            echo '<td align="right">'.$AkarJumlahPreferensiNegatifBulat.'</td>';

                                            $AkumulasiPositifNegatif=$AkarJumlahPreferensiNegatif+$AkarJumlahPreferensiPositif;

                                            // Mencegah Division by Zero pada Preferensi
                                            if($AkumulasiPositifNegatif == 0){
                                                $Preferensi = 0;
                                            } else {
                                                $Preferensi = $AkarJumlahPreferensiNegatif / $AkumulasiPositifNegatif;
                                            }

                                            $PreferensiBulat=round($Preferensi,4);
                                            
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
                                        $id_umkm= $DataPreferensi['id_umkm'];
                                        $preferensi= $DataPreferensi['preferensi'];
                                        
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama_umkm = $DataDetailAkses['nama_umkm'];
                                        $nama_pemilik = $DataDetailAkses['nama_pemilik'];
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
    }else{
        echo '<div class="alert alert-danger">Mohon maaf, ID Periode Penilaian tidak ditemukan.</div>';
    }
?>