<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    
    // Tangkap metode pembobotan dari session (hasil run terakhir)
    $metode_pembobotan = isset($_SESSION['metode_terakhir']) ? $_SESSION['metode_terakhir'] : 'ANP';

    if(empty($_POST['id_periode_penilaian'])){
        echo '<div class="row"><div class="col col-md-12 text-center"><small class="modal-title my-3">Pilih Periode Penilaian Terlebih Dulu.</small></div></div>';
        exit;
    }

    $id_periode_penilaian = $_POST['id_periode_penilaian'];
    
    //Buka detail periode penilaian
    $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
    $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
    $status = isset($DataPeriodePenilaian['status']) ? $DataPeriodePenilaian['status'] : '';
?>

<div class="row">
    <div class="col-md-12 mt-3">
        <b class="card-title">1. Normalisasi (Xij<sup>2</sup>)</b>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-3">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-items-center mb-0">
                <thead>
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
                            $nama_umkm = $DataDetailAkses['nama_umkm'] ?? '<span class="text-danger">Terhapus</span>';
                            $nama_pemilik = $DataDetailAkses['nama_pemilik'] ?? '-';
                    ?>
                        <tr>
                            <td class="text-center text-xs"><?php echo $no; ?></td>
                            <td class="text-left" align="left">
                                <b><?php echo $nama_umkm; ?></b><br>
                                <small>Pemilik: <?php echo $nama_pemilik; ?></small>
                            </td>
                            <?php
                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                while ($data = mysqli_fetch_array($query)) {
                                    $id_kriteria= $data['id_kriteria'];
                                    $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                    $DataNilai = mysqli_fetch_array($QryNilai);
                                    $nilai = floatval($DataNilai['nilai'] ?? 0);
                                    echo '<td align="right">'.($nilai * $nilai).'</td>';
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
                                $QryNormalisasi = mysqli_query($Conn,"SELECT * FROM normalisasi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                $DataNormalisasi = mysqli_fetch_array($QryNormalisasi);
                                echo '<td align="right">'.($DataNormalisasi['normalisasi'] ?? 0).'</td>';
                            }
                        ?>
                    </tr>
                    <tr>
                        <td class="text-center text-xs" colspan="2"><b>SQRT (∑i-1)</b></td>
                        <?php
                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                            while ($data = mysqli_fetch_array($query)) {
                                $id_kriteria= $data['id_kriteria'];
                                $QryNormalisasi = mysqli_query($Conn,"SELECT * FROM normalisasi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                $DataNormalisasi = mysqli_fetch_array($QryNormalisasi);
                                echo '<td align="right">'.($DataNormalisasi['sqrt_normalisasi'] ?? 0).'</td>';
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
        <b class="card-title">2. Normalisasi Terbobot</b>
    </div>
</div>
<div class="row mt-2"> 
    <div class="col-md-12 mt-3">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-items-center mb-0">
                <thead>
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
                            $nama_umkm = $DataDetailAkses['nama_umkm'] ?? '-';
                            $nama_pemilik = $DataDetailAkses['nama_pemilik'] ?? '-';
                    ?>
                        <tr>
                            <td class="text-center text-xs"><?php echo $no; ?></td>
                            <td class="text-left" align="left">
                                <b><?php echo $nama_umkm; ?></b><br>
                                <small>Pemilik: <?php echo $nama_pemilik; ?></small>
                            </td>
                            <?php
                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                while ($data = mysqli_fetch_array($query)) {
                                    $id_kriteria= $data['id_kriteria'];
                                    $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                    $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                    $nilai_terbobot = $DataNormalisasiTerbobot['normalisasi_terbobot'] ?? 0;
                                    echo '<td align="right"><span class="text-success">'.$nilai_terbobot.'</span></td>';
                                }
                            ?>
                        </tr>
                    <?php $no++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-3">
        <b class="card-title">3. Metrik Solusi Ideal</b>
    </div>
</div>
<div class="row mt-2"> 
    <div class="col-md-12 mt-3">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-center"><b>#</b></th>
                        <?php
                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                            while ($data = mysqli_fetch_array($query)) {
                                $id_kriteria= $data['id_kriteria'];
                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                $kode = $DataKriteria['kode_kriteria'] ?? '-';
                                echo '<th class="text-center"><b>'.$kode.'</b></th>';
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left text-xs"><b>Positif (A+)</b></td>
                        <?php
                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                            while ($data = mysqli_fetch_array($query)) {
                                $id_kriteria= $data['id_kriteria'];
                                $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Positif'")or die(mysqli_error($Conn));
                                $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                echo '<td align="right">'.($DataSolusiIdeal['solusi_ideal'] ?? 0).'</td>';
                            }
                        ?>
                    </tr>
                    <tr>
                        <td class="text-left text-xs"><b>Negatif (A-)</b></td>
                        <?php
                            $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                            while ($data = mysqli_fetch_array($query)) {
                                $id_kriteria= $data['id_kriteria'];
                                $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Negatif'")or die(mysqli_error($Conn));
                                $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                echo '<td align="right">'.($DataSolusiIdeal['solusi_ideal'] ?? 0).'</td>';
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
        <b class="card-title">4. Jarak Solusi & Total Preferensi</b>
    </div>
</div>
<div class="row mt-2"> 
    <div class="col-md-12 mt-3">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-center"><b>No</b></th>
                        <th class="text-center"><b>Nama UMKM</b></th>
                        <th class="text-center"><b>Jarak Positif (D+)</b></th>
                        <th class="text-center"><b>Jarak Negatif (D-)</b></th>
                        <th class="text-center"><b>Preferensi (V)</b></th>
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
                            $nama_umkm = $DataDetailAkses['nama_umkm'] ?? '-';
                            $nama_pemilik = $DataDetailAkses['nama_pemilik'] ?? '-';
                    ?>
                        <tr>
                            <td class="text-center text-xs"><?php echo $no; ?></td>
                            <td class="text-left" align="left">
                                <b><?php echo $nama_umkm; ?></b><br>
                                <small>Pemilik: <?php echo $nama_pemilik; ?></small>
                            </td>
                            <?php
                                $QryPreferensi = mysqli_query($Conn,"SELECT * FROM preferensi WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                $DataPreferensi = mysqli_fetch_array($QryPreferensi);
                                
                                $positif = $DataPreferensi['positif'] ?? 0;
                                $negatif = $DataPreferensi['negatif'] ?? 0;
                                $preferensi = $DataPreferensi['preferensi'] ?? 0;
                                
                                echo '<td align="right">'.$positif.'</td>';
                                echo '<td align="right">'.$negatif.'</td>';
                                echo '<td align="right"><b>'.$preferensi.'</b></td>';
                            ?>
                        </tr>
                    <?php $no++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-3">
        <b class="card-title">5. Ranking UMKM</b>
    </div>
</div>
<div class="row mt-2 mb-4"> 
    <div class="col-md-12 mt-3">
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
                        $QryPreferensi = mysqli_query($Conn, "SELECT * FROM preferensi WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY preferensi DESC");
                        while ($DataPreferensi = mysqli_fetch_array($QryPreferensi)) {
                            $id_umkm= $DataPreferensi['id_umkm'] ?? 0;
                            $preferensi= $DataPreferensi['preferensi'] ?? 0;
                            
                            $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                            $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                            $nama_umkm = $DataDetailAkses['nama_umkm'] ?? '-';
                            $nama_pemilik = $DataDetailAkses['nama_pemilik'] ?? '-';
                    ?>
                        <tr>
                            <td class="text-center text-xs">
                                <b class="text-success h5"><?php echo $no; ?></b>
                            </td>
                            <td class="text-left" align="left"><?php echo $nama_umkm; ?></td>
                            <td class="text-left" align="left"><?php echo $nama_pemilik; ?></td>
                            <td class="text-center" align="center"><b><?php echo $preferensi; ?></b></td>
                        </tr>
                    <?php $no++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>