<?php
    //Koneksi
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include '../../vendor/autoload.php';
    
    // [REVISI] Mengambil metode pembobotan yang terakhir kali digunakan dari Session
    $metode_pembobotan = isset($_SESSION['metode_terakhir']) ? $_SESSION['metode_terakhir'] : 'ANP';

    //Tangkap id_akses
    if(empty($_POST['id_periode_penilaian'])){
        echo ' Periode Penilaian Tidak Boleh Kosong';
    }else{
        if(empty($_POST['FormatCetak'])){
            echo 'Format Cetak Tidak Boleh Kosong';
        }else{
            $id_periode_penilaian=$_POST['id_periode_penilaian'];
            $FormatCetak=$_POST['FormatCetak'];
            //Buka detail periode penilaian
            $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
            $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
            $status= $DataPeriodePenilaian['status'];
            if($FormatCetak=="PDF"){
                $mpdf = new \Mpdf\Mpdf();
                $nama_dokumen= "Laporan-$id_periode_penilaian";
                // $mpdf=new mPDF('utf-8', array($panjang_x,$lebar_y)); 
                $html='<style>@page *{margin-top: 0px;}</style>'; 
                //Beginning Buffer to save PHP variables and HTML tags
                ob_start();
            }
?>
    <html>
        <head>
            <title>Laporan Penilaian</title>
            <style type="text/css">
                @page {
                    margin-top: 1cm;
                    margin-bottom: 1cm;
                    margin-left: 1cm;
                    margin-right: 1cm;
                }
                body {
                    background-color: #FFF;
                    font-family: arial;
                }
                table{
                    border-collapse: collapse;
                    margin-top:10px;
                }
                table.kostum tr td {
                    border:none;
                    color:#333;
                    border-spacing: 0px;
                    padding: 2px;
                    border-collapse: collapse;
                    font-size:12px;
                }
                table.data tr td {
                    border: 1px solid #666;
                    color:#333;
                    border-spacing: 0px;
                    padding: 10px;
                    border-collapse: collapse;
                }
                .tabel_garis_bawah {
                    border-bottom: 1px solid #666;
                }
                table.TableForm tr td{
                    padding: 10px;
                }
                table.table-bordered tr td {
                    border: 1px solid #666;
                    color:#333;
                    border-spacing: 0px;
                    padding: 10px;
                    border-collapse: collapse;
                }
                table.table-bordered tr th {
                    border: 1px solid #666;
                    color:#333;
                    border-spacing: 0px;
                    padding: 10px;
                    border-collapse: collapse;
                }
                .mt-3{
                    margin-top: 30px;
                }
            </style>
        </head>
        <body>
            <table width="100%">
                <tr>
                    <td align="center">
                        <img src="../../assets/img/<?php echo $logo;?>" alt="Logo" width="100px">
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <?php
                            echo '<h3><b>'.$title_page.'</b></h3>';
                            
                            // [REVISI] Menampilkan Keterangan Metode di Laporan
                            echo '<h4>Metode Pembobotan: <b>'.$metode_pembobotan.'</b> | Metode Perankingan: <b>TOPSIS</b></h4><br>';
                            
                            echo '<i>'.$alamat_bisnis.'</i><br>';
                            echo '<i>Telepon '.$telepon_bisnis.'</i>';
                        ?>
                    </td>
                </tr>
            </table>
            <br>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <b class="card-title">
                        1. Normaliasai (Xij<sup>2</sup>)
                    </b>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0" width="100%">
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
                                                
                                                // [REVISI] Ambil bobot berdasarkan metode
                                                $bobot = ($metode_pembobotan == 'ANP') ? $data['bobot_anp'] : $data['bobot_swara'];
                                                
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
                                                
                                                // [REVISI] Ambil bobot berdasarkan metode
                                                $bobot = ($metode_pembobotan == 'ANP') ? $DataKriteria['bobot_anp'] : $DataKriteria['bobot_swara'];
                                                
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
                                        $QryUMKM = mysqli_query($Conn, "SELECT*FROM umkm ORDER BY id_UMKM ASC");
                                    }else{
                                        $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_UMKM FROM nilai ORDER BY id_UMKM ASC");
                                    }
                                    while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                        $id_UMKM= $DataUMKM['id_UMKM'];
                                        //Buka detail umkm
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_UMKM='$id_UMKM'")or die(mysqli_error($Conn));
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
                                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_UMKM='$id_UMKM' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
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
                                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_UMKM='$id_UMKM' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
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
                                        <b>Jumlah (&#8721;i-1)</b>
                                    </td>
                                    <?php
                                        if($status=="Proses"){
                                            //Arraykan kriteria
                                            $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                //Arraykan Umkm
                                                $JumlahNormalisasi=0;
                                                if($status=="Proses"){
                                                    $QryUMKM = mysqli_query($Conn, "SELECT*FROM umkm ORDER BY id_UMKM ASC");
                                                }else{
                                                    $QryUMKM = mysqli_query($Conn,"SELECT DISTINCT id_UMKM FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_UMKM ASC");
                                                }
                                                while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                                    $id_UMKM= $DataUMKM['id_UMKM'];
                                                    //Buka nilai
                                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_UMKM='$id_UMKM' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
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
                                                //Arraykan Umkm
                                                $JumlahNormalisasi=0;
                                                if($status=="Proses"){
                                                    $QryUMKM = mysqli_query($Conn, "SELECT*FROM umkm ORDER BY id_UMKM ASC");
                                                }else{
                                                    $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_UMKM FROM nilai ORDER BY id_UMKM ASC");
                                                }
                                                while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                                    $id_UMKM= $DataUMKM['id_UMKM'];
                                                    //Buka nilai
                                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_UMKM='$id_UMKM' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
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
                                        <b>SQRT (&#8721;i-1)</b>
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
                                            //Arraykan Umkm
                                            $JumlahNormalisasi=0;
                                            if($status=="Proses"){
                                                $QryUMKM = mysqli_query($Conn, "SELECT*FROM umkm ORDER BY id_UMKM ASC");
                                            }else{
                                                $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_UMKM FROM nilai ORDER BY id_UMKM ASC");
                                            }
                                            while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                                $id_UMKM= $DataUMKM['id_UMKM'];
                                                //Buka nilai
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_UMKM='$id_UMKM' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
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
            <div class="row">
                <div class="col-md-12 mt-3">
                    <b class="card-title">
                        2. Normaliasai Terbobot (Xij/SQRT (&#8721;i-1))*Bobot
                    </b>
                </div>
            </div>
            <div class="row mt-2"> 
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0" width="100%">
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
                                                
                                                // [REVISI] Ambil bobot berdasarkan metode
                                                $bobot = ($metode_pembobotan == 'ANP') ? $data['bobot_anp'] : $data['bobot_swara'];
                                                
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
                                                
                                                // [REVISI] Ambil bobot berdasarkan metode
                                                $bobot = ($metode_pembobotan == 'ANP') ? $DataKriteria['bobot_anp'] : $DataKriteria['bobot_swara'];
                                                
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
                                        $QryUMKM = mysqli_query($Conn, "SELECT*FROM umkm ORDER BY id_UMKM ASC");
                                    }else{
                                        $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_UMKM FROM nilai ORDER BY id_UMKM ASC");
                                    }
                                    while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                        $id_UMKM= $DataUMKM['id_UMKM'];
                                        //Buka detail umkm
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_UMKM='$id_UMKM'")or die(mysqli_error($Conn));
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
                                                
                                                // [REVISI] Ambil bobot berdasarkan metode
                                                $bobot = ($metode_pembobotan == 'ANP') ? $DataKriteria['bobot_anp'] : $DataKriteria['bobot_swara'];
                                                
                                                //Buka nilai
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_UMKM='$id_UMKM' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
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
                                                    $sqrt_normalisasi = 0;
                                                }else{
                                                    $sqrt_normalisasi = $DataNormalisasi['sqrt_normalisasi'];
                                                }

                                                if($sqrt_normalisasi > 0){
                                                    $NilaiNormalisasi = $nilai / $sqrt_normalisasi;
                                                }else{
                                                    $NilaiNormalisasi = 0;
                                                }
                                                $NormalisasiTerbobot=$NilaiNormalisasi*$bobot;
                                                $PembulatanNormalisasiTerbobot =round($NormalisasiTerbobot,2);
                                                //Buka nilai normalisasai_terbobot
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_UMKM='$id_UMKM'")or die(mysqli_error($Conn));
                                                $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                                if(empty($DataNormalisasiTerbobot['id_normalisasi_terbobot'])){
                                                    //Tambah data
                                                    $entry="INSERT INTO normalisasi_terbobot (
                                                        id_periode_penilaian,
                                                        id_kriteria,
                                                        id_UMKM,
                                                        normalisasi_terbobot
                                                    ) VALUES (
                                                        '$id_periode_penilaian',
                                                        '$id_kriteria',
                                                        '$id_UMKM',
                                                        '$PembulatanNormalisasiTerbobot'
                                                    )";
                                                    $Input=mysqli_query($Conn, $entry);
                                                    if($Input){
                                                        echo '<td align="right">';
                                                        echo '<span class="text-success">'.$PembulatanNormalisasiTerbobot.'</span><br>';
                                                        echo '<small>Xij = '.$nilai.'</small><br>';
                                                        echo '<small>SQRT = '.$sqrt_normalisasi.'</small><br>';
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
                                                        echo '<small>SQRT = '.$sqrt_normalisasi.'</small><br>';
                                                        echo '</td>';
                                                    }else{
                                                        echo '<small class="text-danger">Error</small>';
                                                    }
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
            <div class="row">
                <div class="col-md-12 mt-3">
                    <b class="card-title">
                        3. Metrik Solusi Ideal
                    </b>
                </div>
            </div>
            <div class="row mt-2"> 
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0" width="100%">
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
                                                
                                                // [REVISI] Ambil bobot berdasarkan metode
                                                $bobot = ($metode_pembobotan == 'ANP') ? $data['bobot_anp'] : $data['bobot_swara'];
                                                
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
                                                
                                                // [REVISI] Ambil bobot berdasarkan metode
                                                $bobot = ($metode_pembobotan == 'ANP') ? $DataKriteria['bobot_anp'] : $DataKriteria['bobot_swara'];
                                                
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
                                            
                                            // Menentukan min atau max melalui Benefit atau Cost
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
            <div class="row">
                <div class="col-md-12 mt-3">
                    <b class="card-title">
                        4. Total Preferensi
                    </b>
                </div>
            </div>
            <div class="row mt-2"> 
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0" width="100%">
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
                                        $QryUMKM = mysqli_query($Conn, "SELECT*FROM umkm ORDER BY id_UMKM ASC");
                                    }else{
                                        $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_UMKM FROM nilai ORDER BY id_UMKM ASC");
                                    }
                                    while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                        $id_UMKM= $DataUMKM['id_UMKM'];
                                        //Buka detail umkm
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_UMKM='$id_UMKM'")or die(mysqli_error($Conn));
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
                                                
                                                //Buka nilai normalisasai_terbobot
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_UMKM='$id_UMKM'")or die(mysqli_error($Conn));
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
                                                
                                                //Buka nilai normalisasai_terbobot
                                                $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_UMKM='$id_UMKM'")or die(mysqli_error($Conn));
                                                $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                                $id_normalisasi_terbobot=$DataNormalisasiTerbobot['id_normalisasi_terbobot'];
                                                $normalisasi_terbobot=$DataNormalisasiTerbobot['normalisasi_terbobot'];
                                                //Buka solusi ideal negatif
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
                                            $AkumulasiPositifNegatif =
                                            $AkarJumlahPreferensiNegatif +
                                            $AkarJumlahPreferensiPositif;

                                        if($AkumulasiPositifNegatif > 0){
                                            $Preferensi =
                                                $AkarJumlahPreferensiNegatif /
                                                $AkumulasiPositifNegatif;
                                        }else{
                                            $Preferensi = 0;
                                        }
                                            $PreferensiBulat=round($Preferensi,2);
                                            //cek apakah ada data preferensi
                                            $QryPreferensi = mysqli_query($Conn,"SELECT * FROM preferensi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_UMKM='$id_UMKM'")or die(mysqli_error($Conn));
                                            $DataPreferensi = mysqli_fetch_array($QryPreferensi);
                                            if(empty($DataPreferensi['id_preferensi'])){
                                                //Tambah data
                                                $entry="INSERT INTO preferensi (
                                                    id_periode_penilaian,
                                                    id_UMKM,
                                                    positif,
                                                    negatif,
                                                    preferensi
                                                ) VALUES (
                                                    '$id_periode_penilaian',
                                                    '$id_UMKM',
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
            <div class="row">
                <div class="col-md-12 mt-3">
                    <b class="card-title">
                        5. Ranking Umkm
                    </b>
                </div>
            </div>
            <div class="row mt-2"> 
                <div class="col-md-12 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0" width="100%">
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
                                        $id_UMKM= $DataPreferensi['id_UMKM'];
                                        $preferensi= $DataPreferensi['preferensi'];
                                        //Buka detail umkm
                                        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_UMKM='$id_UMKM'")or die(mysqli_error($Conn));
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
        </body>
    </html>
<?php 
        }
    }
    if($FormatCetak=="PDF"){
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output($nama_dokumen.".pdf" ,'I');
        exit;
    }
?>

<?php
// Pastikan session dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../_Config/Connection.php";

// Tangkap ID Periode Penilaian dari AJAX
if(empty($_POST['id_periode_penilaian'])){
    echo '<div class="alert alert-danger text-center">Silakan pilih Periode Penilaian terlebih dahulu pada filter di atas.</div>';
    exit;
}

$id_periode_penilaian = $_POST['id_periode_penilaian'];

// =========================================================================================
// ENGINE PERHITUNGAN TOPSIS PRESISI EXCEL (ANP vs SWARA) UNTUK LAPORAN
// =========================================================================================

// 1. Ambil Data Kriteria
$kriteria_arr = [];
$query_k = mysqli_query($Conn, "SELECT DISTINCT k.*, LENGTH(k.kode_kriteria) AS len_kode FROM nilai n JOIN kriteria k ON n.id_kriteria = k.id_kriteria WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY len_kode ASC, k.kode_kriteria ASC");
while ($r = mysqli_fetch_array($query_k)) {
    $kriteria_arr[] = $r;
}

// 2. Ambil Data UMKM
$umkm_arr = [];
$query_u = mysqli_query($Conn, "SELECT DISTINCT u.id_umkm, u.nama_umkm FROM nilai n JOIN umkm u ON n.id_umkm = u.id_umkm WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY u.id_umkm ASC");
while ($r = mysqli_fetch_array($query_u)) {
    $umkm_arr[] = $r;
}

// 3. Ambil Matriks Asli (X)
$matriks_x = [];
$query_n = mysqli_query($Conn, "SELECT id_umkm, id_kriteria, nilai FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian'");
while ($r = mysqli_fetch_array($query_n)) {
    $matriks_x[$r['id_umkm']][$r['id_kriteria']] = floatval($r['nilai']);
}

// 4. Hitung Pembagi (SQRT)
$pembagi = [];
foreach ($kriteria_arr as $k) {
    $id_k = $k['id_kriteria'];
    $sum_sq = 0;
    foreach ($umkm_arr as $u) {
        $val = $matriks_x[$u['id_umkm']][$id_k] ?? 0;
        $sum_sq += pow($val, 2);
    }
    $pembagi[$id_k] = sqrt($sum_sq);
}

// 5. Hitung Normalisasi Terbobot (Y) untuk ANP dan SWARA
$y_anp = []; $y_swara = [];
foreach ($kriteria_arr as $k) {
    $id_k = $k['id_kriteria'];
    $b_anp = floatval($k['bobot_anp'] ?? 0);
    $b_swara = floatval($k['bobot_swara'] ?? 0);
    
    foreach ($umkm_arr as $u) {
        $id_u = $u['id_umkm'];
        $val = $matriks_x[$id_u][$id_k] ?? 0;
        $r_val = ($pembagi[$id_k] == 0) ? 0 : ($val / $pembagi[$id_k]);
        
        $y_anp[$id_u][$id_k] = $r_val * $b_anp;
        $y_swara[$id_u][$id_k] = $r_val * $b_swara;
    }
}

// 6. Solusi Ideal (A+ dan A-)
$aplus_anp = []; $amin_anp = [];
$aplus_swara = []; $amin_swara = [];
foreach ($kriteria_arr as $k) {
    $id_k = $k['id_kriteria'];
    $atribut = strtolower(trim($k['atribut'] ?? ($k['sifat'] ?? 'benefit')));
    
    $col_anp = []; $col_swara = [];
    foreach ($umkm_arr as $u) {
        $col_anp[] = $y_anp[$u['id_umkm']][$id_k];
        $col_swara[] = $y_swara[$u['id_umkm']][$id_k];
    }
    
    if ($atribut == 'cost' || $atribut == 'biaya') {
        $aplus_anp[$id_k] = min($col_anp); $amin_anp[$id_k] = max($col_anp);
        $aplus_swara[$id_k] = min($col_swara); $amin_swara[$id_k] = max($col_swara);
    } else {
        $aplus_anp[$id_k] = max($col_anp); $amin_anp[$id_k] = min($col_anp);
        $aplus_swara[$id_k] = max($col_swara); $amin_swara[$id_k] = min($col_swara);
    }
}

// 7. Hitung Jarak & Preferensi Akhir (V)
$hasil_laporan = [];
foreach ($umkm_arr as $u) {
    $id_u = $u['id_umkm'];
    $dp_anp = 0; $dm_anp = 0;
    $dp_swara = 0; $dm_swara = 0;
    
    foreach ($kriteria_arr as $k) {
        $id_k = $k['id_kriteria'];
        $dp_anp += pow(($y_anp[$id_u][$id_k] - $aplus_anp[$id_k]), 2);
        $dm_anp += pow(($y_anp[$id_u][$id_k] - $amin_anp[$id_k]), 2);
        
        $dp_swara += pow(($y_swara[$id_u][$id_k] - $aplus_swara[$id_k]), 2);
        $dm_swara += pow(($y_swara[$id_u][$id_k] - $amin_swara[$id_k]), 2);
    }
    
    // V ANP
    $akar_dp_anp = sqrt($dp_anp); $akar_dm_anp = sqrt($dm_anp);
    $v_anp = (($akar_dp_anp + $akar_dm_anp) == 0) ? 0 : ($akar_dm_anp / ($akar_dp_anp + $akar_dm_anp));
    
    // V SWARA
    $akar_dp_swara = sqrt($dp_swara); $akar_dm_swara = sqrt($dm_swara);
    $v_swara = (($akar_dp_swara + $akar_dm_swara) == 0) ? 0 : ($akar_dm_swara / ($akar_dp_swara + $akar_dm_swara));
    
    // Tentukan acuan ranking berdasarkan metode yang terakhir dipilih user
    $metode_aktif = $_SESSION['metode_terakhir'] ?? 'ANP';
    $v_patokan = (strtoupper(trim($metode_aktif)) == 'SWARA') ? $v_swara : $v_anp;

    $hasil_laporan[] = [
        'nama_umkm' => $u['nama_umkm'],
        'v_anp' => $v_anp,
        'v_swara' => $v_swara,
        'v_patokan' => $v_patokan
    ];
}

// 8. Urutkan Ranking (Terbesar ke Terkecil)
usort($hasil_laporan, function($a, $b) {
    if (abs($a['v_patokan'] - $b['v_patokan']) < 0.0000001) return 0;
    return ($a['v_patokan'] < $b['v_patokan']) ? 1 : -1;
});
?>

<div class="table-responsive">
    <table class="table table-hover table-bordered align-items-center mb-0" id="DataTabelLaporan">
        <thead class="bg-dark text-white">
            <tr>
                <th class="text-center"><b>Rank</b></th>
                <th class="text-center"><b>Nama UMKM</b></th>
                <th class="text-center bg-primary"><b>Nilai Akhir (ANP)</b></th>
                <th class="text-center bg-info text-dark"><b>Nilai Akhir (SWARA)</b></th>
                <th class="text-center"><b>Status Keputusan</b></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $no = 1;
                foreach ($hasil_laporan as $h) {
                    if ($h['v_patokan'] >= 0.5) {
                        $status_badge = '<span class="badge bg-success">Prioritas Utama</span>';
                    } else {
                        $status_badge = '<span class="badge bg-warning text-dark">Bukan Prioritas</span>';
                    }
            ?>
                    <tr>
                        <td class="text-center fw-bold h5 text-success"><?php echo $no++; ?></td>
                        <td class="text-start fw-bold"><?php echo htmlspecialchars($h['nama_umkm']); ?></td>
                        <td class="text-center text-primary fw-bold fs-6"><?php echo number_format($h['v_anp'], 4, '.', ''); ?></td>
                        <td class="text-center text-info fw-bold fs-6"><?php echo number_format($h['v_swara'], 4, '.', ''); ?></td>
                        <td class="text-center"><?php echo $status_badge; ?></td>
                    </tr>
            <?php 
                } 
            ?>
        </tbody>
    </table>
</div>