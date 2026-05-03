<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    if(!empty($_POST['id_periode_penilaian'])){
        $id_periode_penilaian=$_POST['id_periode_penilaian'];
        //Buka detail periode penilaian
        $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
        $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
        $status= $DataPeriodePenilaian['status'];
?>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">
                        1. Normaliasai (Xij<sup>2</sup>)
                    </b>
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
                                    <th class="text-center">
                                        <b>No</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Nama</b>
                                    </th>
                                    <?php
                                        if($status=="Proses"){
                                             //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $kode_kriteria= $data['kode_kriteria'];
                                                $kriteria= $data['kriteria'];
                                                $bobot= $data['bobot'];
                                                echo '<th class="text-center"><b>'.$kode_kriteria.'</b><br>('.$bobot.')</th>';
                                            }
                                        }else{
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                //Buka detail kriteria
                                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                                $kode_kriteria= $DataKriteria['kode_kriteria'];
                                                $kriteria = $DataKriteria['kriteria'];
                                                $atribut= $DataKriteria['atribut'];
                                                $bobot= $DataKriteria['bobot'];
                                                echo '<th class="text-center"><b>'.$kode_kriteria.'</b><br>('.$bobot.')</th>';
                                            }
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    //KONDISI PENGATURAN MASING FILTER
                                    if($status=="Proses"){
                                        $QryKaryawan = mysqli_query($Conn, "SELECT*FROM karyawan ORDER BY id_karyawan ASC");
                                    }else{
                                        $QryKaryawan = mysqli_query($Conn, "SELECT DISTINCT id_karyawan FROM nilai ORDER BY id_karyawan ASC");
                                    }
                                    while ($DataKaryawan = mysqli_fetch_array($QryKaryawan)) {
                                        $id_karyawan= $DataKaryawan['id_karyawan'];
                                        //Buka detail karyawan
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM karyawan WHERE id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama = $DataDetailAkses['nama'];
                                        $jabatan = $DataDetailAkses['jabatan'];
                                ?>
                                    <tr>
                                        <td class="text-center text-xs">
                                            <?php echo "$no" ?>
                                        </td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "<b>$nama</b><br>";
                                                echo "<small>$jabatan</small>";
                                            ?>
                                        </td>
                                        <?php
                                            if($status=="Proses"){
                                                //Arraykan kriteria
                                                $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                                while ($data = mysqli_fetch_array($query)) {
                                                    $id_kriteria= $data['id_kriteria'];
                                                    //Buka nilai
                                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_karyawan='$id_karyawan' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                    $DataNilai = mysqli_fetch_array($QryNilai);
                                                    if(empty($DataNilai['nilai'])){
                                                        $nilai =0;
                                                    }else{
                                                        $nilai =$DataNilai['nilai'];
                                                    }
                                                    $xij2=$nilai*$nilai;
                                                    $xij2Rupiah = "" . number_format($xij2,0,',','.');
                                                    echo '<td align="right">'.$xij2Rupiah.'</td>';
                                                }
                                            }else{
                                                //Arraykan kriteria
                                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                                while ($data = mysqli_fetch_array($query)) {
                                                    $id_kriteria= $data['id_kriteria'];
                                                    //Buka nilai
                                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_karyawan='$id_karyawan' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                    $DataNilai = mysqli_fetch_array($QryNilai);
                                                    if(empty($DataNilai['nilai'])){
                                                        $nilai =0;
                                                    }else{
                                                        $nilai =$DataNilai['nilai'];
                                                    }
                                                    $xij2=$nilai*$nilai;
                                                    $xij2Rupiah = "" . number_format($xij2,0,',','.');
                                                    echo '<td align="right">'.$xij2Rupiah.'</td>';
                                                }
                                            }
                                        ?>
                                    </tr>
                                <?php
                                    $no++; }
                                ?>
                                <tr>
                                    <td class="text-center text-xs" colspan="2">
                                        <b>Jumlah (∑i-1)</b>
                                    </td>
                                    <?php
                                        if($status=="Proses"){
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                //Arraykan Karyawan
                                                $JumlahNormalisasi=0;
                                                if($status=="Proses"){
                                                    $QryKaryawan = mysqli_query($Conn, "SELECT*FROM karyawan ORDER BY id_karyawan ASC");
                                                }else{
                                                    $QryKaryawan = mysqli_query($Conn, "SELECT DISTINCT id_karyawan FROM periode_penilaian ORDER BY id_karyawan ASC");
                                                }
                                                while ($DataKaryawan = mysqli_fetch_array($QryKaryawan)) {
                                                    $id_karyawan= $DataKaryawan['id_karyawan'];
                                                    //Buka nilai
                                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_karyawan='$id_karyawan' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                    $DataNilai = mysqli_fetch_array($QryNilai);
                                                    if(empty($DataNilai['nilai'])){
                                                        $nilai =0;
                                                    }else{
                                                        $nilai =$DataNilai['nilai'];
                                                    }
                                                    $xij2=$nilai*$nilai;
                                                    $JumlahNormalisasi=$xij2+$JumlahNormalisasi;
                                                }
                                                $JumlahNormalisasiRupiah = "" . number_format($JumlahNormalisasi,0,',','.');
                                                echo '<td align="right">'.$JumlahNormalisasiRupiah.'</td>';
                                            }
                                        }else{
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                //Arraykan Karyawan
                                                $JumlahNormalisasi=0;
                                                if($status=="Proses"){
                                                    $QryKaryawan = mysqli_query($Conn, "SELECT*FROM karyawan ORDER BY id_karyawan ASC");
                                                }else{
                                                    $QryKaryawan = mysqli_query($Conn, "SELECT DISTINCT id_karyawan FROM nilai ORDER BY id_karyawan ASC");
                                                }
                                                while ($DataKaryawan = mysqli_fetch_array($QryKaryawan)) {
                                                    $id_karyawan= $DataKaryawan['id_karyawan'];
                                                    //Buka nilai
                                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_karyawan='$id_karyawan' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                    $DataNilai = mysqli_fetch_array($QryNilai);
                                                    if(empty($DataNilai['nilai'])){
                                                        $nilai =0;
                                                    }else{
                                                        $nilai =$DataNilai['nilai'];
                                                    }
                                                    $xij2=$nilai*$nilai;
                                                    $JumlahNormalisasi=$xij2+$JumlahNormalisasi;
                                                }
                                                $JumlahNormalisasiRupiah = "" . number_format($JumlahNormalisasi,0,',','.');
                                                echo '<td align="right">'.$JumlahNormalisasiRupiah.'</td>';
                                            }
                                        }
                                    ?>
                                </tr>
                                <tr>
                                    <td class="text-center text-xs" colspan="2">
                                        <b>SQRT (∑i-1)</b>
                                    </td>
                                    <?php
                                        if($status=="Proses"){
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                        }else{
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        }
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            //Arraykan Karyawan
                                            $JumlahNormalisasi=0;
                                            if($status=="Proses"){
                                                $QryKaryawan = mysqli_query($Conn, "SELECT*FROM karyawan ORDER BY id_karyawan ASC");
                                            }else{
                                                $QryKaryawan = mysqli_query($Conn, "SELECT DISTINCT id_karyawan FROM nilai ORDER BY id_karyawan ASC");
                                            }
                                            while ($DataKaryawan = mysqli_fetch_array($QryKaryawan)) {
                                                $id_karyawan= $DataKaryawan['id_karyawan'];
                                                //Buka nilai
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_karyawan='$id_karyawan' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNilai = mysqli_fetch_array($QryNilai);
                                                if(empty($DataNilai['nilai'])){
                                                    $nilai =0;
                                                }else{
                                                    $nilai =$DataNilai['nilai'];
                                                }
                                                $xij2=$nilai*$nilai;
                                                $JumlahNormalisasi=$xij2+$JumlahNormalisasi;
                                                
                                            }
                                            $SqrtNormalisasi=sqrt($JumlahNormalisasi);
                                            $SqrtNormalisasiRupiah = "" . number_format($SqrtNormalisasi,0,',','.');
                                            //Cekapakah data normalisasi sudah ad?
                                            $QryNormalisasi = mysqli_query($Conn,"SELECT * FROM normalisasi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataNormalisasi = mysqli_fetch_array($QryNormalisasi);
                                            if(empty($DataNormalisasi['id_normalisasi'])){
                                                //Tambah data
                                                $entry="INSERT INTO normalisasi (
                                                    id_periode_penilaian,
                                                    id_kriteria,
                                                    normalisasi,
                                                    sqrt_normalisasi
                                                ) VALUES (
                                                    '$id_periode_penilaian',
                                                    '$id_kriteria',
                                                    '$JumlahNormalisasi',
                                                    '$SqrtNormalisasi'
                                                )";
                                                $Input=mysqli_query($Conn, $entry);
                                                if($Input){
                                                    echo '<td align="right">'.$SqrtNormalisasiRupiah.'</td>';
                                                }else{
                                                    echo '<td align="right" class="text-danger">Error</td>';
                                                }
                                            }else{
                                                $id_normalisasi =$DataNormalisasi['id_normalisasi'];
                                                //Update data
                                                $UpdateNormalisasi = mysqli_query($Conn,"UPDATE normalisasi SET 
                                                    normalisasi='$JumlahNormalisasi',
                                                    sqrt_normalisasi='$SqrtNormalisasi'
                                                WHERE id_normalisasi='$id_normalisasi'") or die(mysqli_error($Conn)); 
                                                if($UpdateNormalisasi){
                                                    echo '<td align="right">'.$SqrtNormalisasiRupiah.'</td>';
                                                }else{
                                                    echo '<td align="right" class="text-danger">Error</td>';
                                                }
                                                
                                            }
                                        }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">
                        2. Normaliasai Terbobot (Xij/SQRT (∑i-1))*Bobot
                    </b>
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
                                    <th class="text-center">
                                        <b>No</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Nama</b>
                                    </th>
                                    <?php
                                        if($status=="Proses"){
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $kode_kriteria= $data['kode_kriteria'];
                                                $kriteria= $data['kriteria'];
                                                $bobot= $data['bobot'];
                                                echo '<th class="text-center"><b>'.$kode_kriteria.'</b><br>('.$bobot.')</th>';
                                            }
                                        }else{
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                //Buka detail kriteria
                                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                                $kode_kriteria= $DataKriteria['kode_kriteria'];
                                                $kriteria = $DataKriteria['kriteria'];
                                                $atribut= $DataKriteria['atribut'];
                                                $bobot= $DataKriteria['bobot'];
                                                echo '<th class="text-center"><b>'.$kode_kriteria.'</b><br>('.$bobot.')</th>';
                                            }
                                        }
                                        
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    //KONDISI PENGATURAN MASING FILTER
                                    if($status=="Proses"){
                                        $QryKaryawan = mysqli_query($Conn, "SELECT*FROM karyawan ORDER BY id_karyawan ASC");
                                    }else{
                                        $QryKaryawan = mysqli_query($Conn, "SELECT DISTINCT id_karyawan FROM nilai ORDER BY id_karyawan ASC");
                                    }
                                    while ($DataKaryawan = mysqli_fetch_array($QryKaryawan)) {
                                        $id_karyawan= $DataKaryawan['id_karyawan'];
                                        //Buka detail karyawan
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM karyawan WHERE id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama = $DataDetailAkses['nama'];
                                        $jabatan = $DataDetailAkses['jabatan'];
                                ?>
                                    <tr>
                                        <td class="text-center text-xs">
                                            <?php echo "$no" ?>
                                        </td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "<b>$nama</b><br>";
                                                echo "<small>$jabatan</small>";
                                            ?>
                                        </td>
                                        <?php
                                            //Arraykan kriteria
                                            if($status=="Proses"){
                                                $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                            }else{
                                                //Arraykan kriteria
                                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            }
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                //Buka detail kriteria
                                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                                $bobot= $DataKriteria['bobot'];
                                                //Buka nilai
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_karyawan='$id_karyawan' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNilai = mysqli_fetch_array($QryNilai);
                                                if(empty($DataNilai['nilai'])){
                                                    $nilai =0;
                                                }else{
                                                    $nilai =$DataNilai['nilai'];
                                                }
                                                //Buka nilai normalisasai
                                                $QryNormalisasi = mysqli_query($Conn,"SELECT * FROM normalisasi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNormalisasi = mysqli_fetch_array($QryNormalisasi);
                                                if(empty($DataNormalisasi['sqrt_normalisasi'])){
                                                    $sqrt_normalisasi =0;
                                                }else{
                                                    $sqrt_normalisasi =$DataNormalisasi['sqrt_normalisasi'];
                                                }
                                                $NilaiNormalisasi=$nilai/$sqrt_normalisasi;
                                                $NormalisasiTerbobot=$NilaiNormalisasi*$bobot;
                                                $PembulatanNormalisasiTerbobot =round($NormalisasiTerbobot,2);
                                                //Buka nilai normalisasai_terbobot
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
                                                $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                                if(empty($DataNormalisasiTerbobot['id_normalisasi_terbobot'])){
                                                    //Tambah data
                                                    $entry="INSERT INTO normalisasi_terbobot (
                                                        id_periode_penilaian,
                                                        id_kriteria,
                                                        id_karyawan,
                                                        normalisasi_terbobot
                                                    ) VALUES (
                                                        '$id_periode_penilaian',
                                                        '$id_kriteria',
                                                        '$id_karyawan',
                                                        '$PembulatanNormalisasiTerbobot'
                                                    )";
                                                    $Input=mysqli_query($Conn, $entry);
                                                    if($Input){
                                                        echo '<td align="right">';
                                                        echo '<span class="text-success">'.$PembulatanNormalisasiTerbobot.'</span><br>';
                                                        echo '<small>Xij = '.$nilai.'</small><br>';
                                                        echo '<small>&#8730;(∑i-1) = '.$sqrt_normalisasi.'</small><br>';
                                                        echo '</td>';
                                                    }else{
                                                        echo '<td align="right" class="text-danger">Error</td>';
                                                    }
                                                }else{
                                                    $id_normalisasi_terbobot =$DataNormalisasiTerbobot['id_normalisasi_terbobot'];
                                                    //Update Data
                                                    $UpdateNormalisasiTerbobot = mysqli_query($Conn,"UPDATE normalisasi_terbobot SET 
                                                        normalisasi_terbobot='$PembulatanNormalisasiTerbobot'
                                                    WHERE id_normalisasi_terbobot='$id_normalisasi_terbobot'") or die(mysqli_error($Conn)); 
                                                    if($UpdateNormalisasiTerbobot){
                                                        echo '<td align="right">';
                                                        echo '<span class="text-success">'.$PembulatanNormalisasiTerbobot.'</span><br>';
                                                        echo '<small>Xij = '.$nilai.'</small><br>';
                                                        echo '<small>&#8730;(∑i-1) = '.$sqrt_normalisasi.'</small><br>';
                                                        echo '</td>';
                                                    }else{
                                                        echo '<small class="text-danger">Error</small>';
                                                    }
                                                }
                                                // echo '<td align="right">';
                                                // echo ''.$PembulatanNormalisasiTerbobot.'<br>';
                                                // echo '</td>';
                                            }
                                        ?>
                                    </tr>
                                <?php
                                    $no++; }
                                ?>
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
                    <b class="card-title">
                        3. Metrik Solusi Ideal
                    </b>
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
                                    <th class="text-center">
                                        <b>#</b>
                                    </th>
                                    <?php
                                        if($status=="Proses"){
                                             //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $kode_kriteria= $data['kode_kriteria'];
                                                $kriteria= $data['kriteria'];
                                                $bobot= $data['bobot'];
                                                echo '<th class="text-center"><b>'.$kode_kriteria.'</b><br>('.$bobot.')</th>';
                                            }
                                        }else{
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                //Buka detail kriteria
                                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                                $kode_kriteria= $DataKriteria['kode_kriteria'];
                                                $kriteria = $DataKriteria['kriteria'];
                                                $atribut= $DataKriteria['atribut'];
                                                $bobot= $DataKriteria['bobot'];
                                                echo '<th class="text-center"><b>'.$kode_kriteria.'</b><br>('.$bobot.')</th>';
                                            }
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left text-xs">
                                        <b>Positif</b><br>
                                        <small>(Max|benefit), (Min|cost)</small>
                                    </td>
                                    <?php
                                        if($status=="Proses"){
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                        }else{
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        }
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            //Buka detail kriteria
                                            $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataKriteria = mysqli_fetch_array($QryKriteria);
                                            $atribut= $DataKriteria['atribut'];
                                            $bobot= $DataKriteria['bobot'];
                                            //Menentukan min atau max melalui Benefit atau Cost
                                            if($atribut=="Benefit"){
                                                //Cari nilai maks
                                                $QryMaks=mysqli_query($Conn, "SELECT max(normalisasi_terbobot) as normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                while($NilaiMaks=mysqli_fetch_array($QryMaks)){
                                                    $MinMaks=$NilaiMaks['normalisasi_terbobot'];
                                                }
                                            }else{
                                                //Cari nilai mIN
                                                $QryMin=mysqli_query($Conn, "SELECT min(normalisasi_terbobot) as normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                while($NilaiMin=mysqli_fetch_array($QryMin)){
                                                    $MinMaks=$NilaiMin['normalisasi_terbobot'];
                                                }
                                            }
                                            //Buka nilai solusi_ideal
                                            $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Positif'")or die(mysqli_error($Conn));
                                            $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                            if(empty($DataSolusiIdeal['id_solusi_ideal'])){
                                                //Tambah data
                                                $entry="INSERT INTO solusi_ideal (
                                                    id_periode_penilaian,
                                                    id_kriteria,
                                                    positif_negatif,
                                                    solusi_ideal
                                                ) VALUES (
                                                    '$id_periode_penilaian',
                                                    '$id_kriteria',
                                                    'Positif',
                                                    '$MinMaks'
                                                )";
                                                $Input=mysqli_query($Conn, $entry);
                                                if($Input){
                                                    echo '<td align="right">';
                                                    echo ''.$MinMaks.'';
                                                    echo '</td>';
                                                }else{
                                                    echo '<td align="right" class="text-danger">Error</td>';
                                                }
                                            }else{
                                                $id_solusi_ideal=$DataSolusiIdeal['id_solusi_ideal'];
                                                //Update Data
                                                $UpdateSolusiIdeal = mysqli_query($Conn,"UPDATE solusi_ideal SET 
                                                    solusi_ideal='$MinMaks'
                                                WHERE id_solusi_ideal='$id_solusi_ideal'") or die(mysqli_error($Conn)); 
                                                if($UpdateSolusiIdeal){
                                                    echo '<td align="right">';
                                                    echo ''.$MinMaks.'';
                                                    echo '</td>';
                                                }else{
                                                    echo '<td align="right" class="text-danger">Error</td>';
                                                }
                                            }
                                            
                                        }
                                    ?>
                                </tr>
                                <tr>
                                    <td class="text-left text-xs">
                                        <b>Negatif</b><br>
                                        <small>(Min|benefit), (Max|cost)</small>
                                    </td>
                                    <?php
                                        if($status=="Proses"){
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                        }else{
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                        }
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];
                                            //Buka detail kriteria
                                            $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                            $DataKriteria = mysqli_fetch_array($QryKriteria);
                                            $atribut= $DataKriteria['atribut'];
                                            $bobot= $DataKriteria['bobot'];
                                            //Menentukan min atau max melalui Benefit atau Cost
                                            if($atribut=="Cost"){
                                                //Cari nilai maks
                                                $QryMaks=mysqli_query($Conn, "SELECT max(normalisasi_terbobot) as normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                while($NilaiMaks=mysqli_fetch_array($QryMaks)){
                                                    $MinMaks=$NilaiMaks['normalisasi_terbobot'];
                                                }
                                            }else{
                                                //Cari nilai mIN
                                                $QryMin=mysqli_query($Conn, "SELECT min(normalisasi_terbobot) as normalisasi_terbobot FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                while($NilaiMin=mysqli_fetch_array($QryMin)){
                                                    $MinMaks=$NilaiMin['normalisasi_terbobot'];
                                                }
                                            }
                                            //Buka nilai solusi_ideal
                                            $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Negatif'")or die(mysqli_error($Conn));
                                            $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                            if(empty($DataSolusiIdeal['id_solusi_ideal'])){
                                                //Tambah data
                                                $entry="INSERT INTO solusi_ideal (
                                                    id_periode_penilaian,
                                                    id_kriteria,
                                                    positif_negatif,
                                                    solusi_ideal
                                                ) VALUES (
                                                    '$id_periode_penilaian',
                                                    '$id_kriteria',
                                                    'Negatif',
                                                    '$MinMaks'
                                                )";
                                                $Input=mysqli_query($Conn, $entry);
                                                if($Input){
                                                    echo '<td align="right">';
                                                    echo ''.$MinMaks.'';
                                                    echo '</td>';
                                                }else{
                                                    echo '<td align="right" class="text-danger">Error</td>';
                                                }
                                            }else{
                                                $id_solusi_ideal=$DataSolusiIdeal['id_solusi_ideal'];
                                                //Update Data
                                                $UpdateSolusiIdeal = mysqli_query($Conn,"UPDATE solusi_ideal SET 
                                                    solusi_ideal='$MinMaks'
                                                WHERE id_solusi_ideal='$id_solusi_ideal'") or die(mysqli_error($Conn)); 
                                                if($UpdateSolusiIdeal){
                                                    echo '<td align="right">';
                                                    echo ''.$MinMaks.'';
                                                    echo '</td>';
                                                }else{
                                                    echo '<td align="right" class="text-danger">Error</td>';
                                                }
                                            }
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
                    <b class="card-title">
                        4. Total Preferensi
                    </b>
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
                                    <th class="text-center">
                                        <b>No</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Nama</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Positif</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Negatif</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Preferensi</b>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    //KONDISI PENGATURAN MASING FILTER
                                    if($status=="Proses"){
                                        $QryKaryawan = mysqli_query($Conn, "SELECT*FROM karyawan ORDER BY id_karyawan ASC");
                                    }else{
                                        $QryKaryawan = mysqli_query($Conn, "SELECT DISTINCT id_karyawan FROM nilai ORDER BY id_karyawan ASC");
                                    }
                                    while ($DataKaryawan = mysqli_fetch_array($QryKaryawan)) {
                                        $id_karyawan= $DataKaryawan['id_karyawan'];
                                        //Buka detail karyawan
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM karyawan WHERE id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama = $DataDetailAkses['nama'];
                                        $jabatan = $DataDetailAkses['jabatan'];
                                ?>
                                    <tr>
                                        <td class="text-center text-xs">
                                            <?php echo "$no" ?>
                                        </td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "<b>$nama</b><br>";
                                                echo "<small>$jabatan</small>";
                                            ?>
                                        </td>
                                        <?php
                                            $JumlahPreferensiPositif=0;
                                            if($status=="Proses"){
                                                //Arraykan kriteria
                                                $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                            }else{
                                                //Arraykan kriteria
                                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            }
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                //Buka detail kriteria
                                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                                $bobot= $DataKriteria['bobot'];
                                                //Buka nilai normalisasai_terbobot
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
                                                $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                                $id_normalisasi_terbobot=$DataNormalisasiTerbobot['id_normalisasi_terbobot'];
                                                $normalisasi_terbobot=$DataNormalisasiTerbobot['normalisasi_terbobot'];
                                                //Buka solusi ideal positif
                                                $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Positif'")or die(mysqli_error($Conn));
                                                $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                                if(empty($DataSolusiIdeal['solusi_ideal'])){
                                                    $solusi_ideal=0;
                                                }else{
                                                    $solusi_ideal=$DataSolusiIdeal['solusi_ideal'];
                                                }
                                                $NormalisasiSolusi=$normalisasi_terbobot-$solusi_ideal;
                                                $NormalisasiSolusiKuadrat=$NormalisasiSolusi*$NormalisasiSolusi;
                                                $JumlahPreferensiPositif=$JumlahPreferensiPositif+$NormalisasiSolusiKuadrat;
                                            }
                                            $AkarJumlahPreferensiPositif=sqrt($JumlahPreferensiPositif);
                                            $AkarJumlahPreferensiPositifBulat=round($AkarJumlahPreferensiPositif,2);
                                            echo '<td align="right">';
                                            echo ''.$AkarJumlahPreferensiPositifBulat.'';
                                            echo '</td>';
                                        ?>
                                        <?php
                                            $JumlahPreferensiNegatif=0;
                                            if($status=="Proses"){
                                                //Arraykan kriteria
                                                $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                            }else{
                                                //Arraykan kriteria
                                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            }
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                //Buka detail kriteria
                                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                                $bobot= $DataKriteria['bobot'];
                                                //Buka nilai normalisasai_terbobot
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
                                                $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                                $id_normalisasi_terbobot=$DataNormalisasiTerbobot['id_normalisasi_terbobot'];
                                                $normalisasi_terbobot=$DataNormalisasiTerbobot['normalisasi_terbobot'];
                                                //Buka solusi ideal positif
                                                $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Negatif'")or die(mysqli_error($Conn));
                                                $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                                if(empty($DataSolusiIdeal['solusi_ideal'])){
                                                    $solusi_ideal=0;
                                                }else{
                                                    $solusi_ideal=$DataSolusiIdeal['solusi_ideal'];
                                                }
                                                $NormalisasiSolusi=$normalisasi_terbobot-$solusi_ideal;
                                                $NormalisasiSolusiKuadrat=$NormalisasiSolusi*$NormalisasiSolusi;
                                                $JumlahPreferensiNegatif=$JumlahPreferensiNegatif+$NormalisasiSolusiKuadrat;
                                            }
                                            $AkarJumlahPreferensiNegatif=sqrt($JumlahPreferensiNegatif);
                                            $AkarJumlahPreferensiNegatifBulat=round($AkarJumlahPreferensiNegatif,2);
                                            echo '<td align="right">';
                                            echo ''.$AkarJumlahPreferensiNegatifBulat.'';
                                            echo '</td>';

                                            //Menambahkan positif dan negatif
                                            $AkumulasiPositifNegatif=$AkarJumlahPreferensiNegatif+$AkarJumlahPreferensiPositif;
                                            $Preferensi=$AkarJumlahPreferensiNegatif/$AkumulasiPositifNegatif;
                                            $PreferensiBulat=round($Preferensi,2);
                                            //cek apakah ada data preferensi
                                            $QryPreferensi = mysqli_query($Conn,"SELECT * FROM preferensi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
                                            $DataPreferensi = mysqli_fetch_array($QryPreferensi);
                                            if(empty($DataPreferensi['id_preferensi'])){
                                                //Tambah data
                                                $entry="INSERT INTO preferensi (
                                                    id_periode_penilaian,
                                                    id_karyawan,
                                                    positif,
                                                    negatif,
                                                    preferensi
                                                ) VALUES (
                                                    '$id_periode_penilaian',
                                                    '$id_karyawan',
                                                    '$AkarJumlahPreferensiPositifBulat',
                                                    '$AkarJumlahPreferensiNegatifBulat',
                                                    '$PreferensiBulat'
                                                )";
                                                $Input=mysqli_query($Conn, $entry);
                                                if($Input){
                                                    echo '<td align="right">';
                                                    echo ''.$PreferensiBulat.'';
                                                    echo '</td>';
                                                }else{
                                                    echo '<td align="right" class="text-danger">Error</td>';
                                                }
                                            }else{
                                                $id_preferensi=$DataPreferensi['id_preferensi'];
                                                $UpdatePreferensi = mysqli_query($Conn,"UPDATE preferensi SET 
                                                    preferensi='$PreferensiBulat'
                                                WHERE id_preferensi='$id_preferensi'") or die(mysqli_error($Conn)); 
                                                if($UpdatePreferensi){
                                                    echo '<td align="right">';
                                                    echo ''.$PreferensiBulat.'';
                                                    echo '</td>';
                                                }else{
                                                    echo '<td align="right" class="text-danger">Error</td>';
                                                }
                                            }
                                            
                                        ?>
                                    </tr>
                                <?php
                                    $no++; }
                                ?>
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
                    <b class="card-title">
                        5. Ranking Karyawan
                    </b>
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
                                    <th class="text-center">
                                        <b>Rank</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Nama</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Jabatan</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Preferensi</b>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    //KONDISI PENGATURAN MASING FILTER
                                    $QryPreferensi = mysqli_query($Conn, "SELECT*FROM preferensi WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY preferensi DESC");
                                    while ($DataPreferensi = mysqli_fetch_array($QryPreferensi)) {
                                        $id_karyawan= $DataPreferensi['id_karyawan'];
                                        $preferensi= $DataPreferensi['preferensi'];
                                        //Buka detail karyawan
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM karyawan WHERE id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
                                        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                        $nama = $DataDetailAkses['nama'];
                                        $jabatan = $DataDetailAkses['jabatan'];
                                ?>
                                    <tr>
                                        <td class="text-center text-xs">
                                            <?php echo "$no" ?>
                                        </td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "$nama";
                                            ?>
                                        </td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "$jabatan";
                                            ?>
                                        </td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "$preferensi";
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                    $no++; }
                                ?>
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
        $id_periode_penilaian="";
        echo '<div class="card">';
        echo '  <div class="card-body">';
        echo '      <div class="row">';
        echo '          <div class="col col-md-12 text-center">';
        echo '              <small class="modal-title my-3">Sorry, No access data selected.</small>';
        echo '          </div>';
        echo '      </div>';
        echo '  </div>';
        echo '</div>';
    }
?>