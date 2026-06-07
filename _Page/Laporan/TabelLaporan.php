<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    
    // Tangkap metode pembobotan jika ada (Opsional untuk laporan)
    $metode_pembobotan = isset($_SESSION['metode_terakhir']) ? $_SESSION['metode_terakhir'] : 'Belum Dipilih';

    if(!empty($_POST['id_periode_penilaian'])){
        $id_periode_penilaian=$_POST['id_periode_penilaian'];
        //Buka detail periode penilaian
        $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
        $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
        
        $status = isset($DataPeriodePenilaian['status']) ? $DataPeriodePenilaian['status'] : '';
?>
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
                <table class="table table-hover table-bordered align-items-center mb-0">
                    <thead class="">
                        <tr>
                            <th class="text-center"><b>No</b></th>
                            <th class="text-center"><b>Nama UMKM</b></th>
                            <?php
                                if($status=="Proses"){
                                    $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
                                    while ($data = mysqli_fetch_array($query)) {
                                        $bobot = ($metode_pembobotan == 'ANP') ? ($data['bobot_anp'] ?? 0) : ($data['bobot_swara'] ?? 0);
                                        $kode = $data['kode_kriteria'] ?? '-';
                                        echo '<th class="text-center"><b>'.$kode.'</b><br>('.$bobot.')</th>';
                                    }
                                }else{
                                    $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                    while ($data = mysqli_fetch_array($query)) {
                                        $id_kriteria= $data['id_kriteria'];
                                        $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                        $DataKriteria = mysqli_fetch_array($QryKriteria);
                                        
                                        $bobot = ($metode_pembobotan == 'ANP') ? ($DataKriteria['bobot_anp'] ?? 0) : ($DataKriteria['bobot_swara'] ?? 0);
                                        $kode = $DataKriteria['kode_kriteria'] ?? '-';
                                        echo '<th class="text-center"><b>'.$kode.'</b><br>('.$bobot.')</th>';
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
                                $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT id_umkm FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_umkm ASC");
                            }
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
                                    echo '<td align="right">'.round($SqrtNormalisasi, 6).'</td>';
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
                2. Normaliasai Terbobot (Xij/SQRT (∑i-1))*Bobot
            </b>
        </div>
    </div>
    <div class="row mt-2"> 
        <div class="col-md-12 mt-3">
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
                                        
                                        $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                        $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                        
                                        if(!empty($DataNormalisasiTerbobot['normalisasi_terbobot'])){
                                            echo '<td align="right">';
                                            echo '<span class="text-success">'.$DataNormalisasiTerbobot['normalisasi_terbobot'].'</span><br>';
                                            echo '</td>';
                                        } else {
                                            echo '<td align="right">0</td>';
                                        }
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
            <b class="card-title">
                3. Metrik Solusi Ideal Positif & Negatif
            </b>
        </div>
    </div>
    <div class="row mt-2"> 
        <div class="col-md-12 mt-3">
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
                                    $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Positif'")or die(mysqli_error($Conn));
                                    $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                    $solusi_ideal = $DataSolusiIdeal['solusi_ideal'] ?? 0;
                                    echo '<td align="right">'.$solusi_ideal.'</td>';
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
                                    $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Negatif'")or die(mysqli_error($Conn));
                                    $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                    $solusi_ideal = $DataSolusiIdeal['solusi_ideal'] ?? 0;
                                    echo '<td align="right">'.$solusi_ideal.'</td>';
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
                <table class="table table-hover table-bordered align-items-center mb-0">
                    <thead class="">
                        <tr>
                            <th class="text-center"><b>No</b></th>
                            <th class="text-center"><b>Nama UMKM</b></th>
                            <th class="text-center"><b>Positif (D+)</b></th>
                            <th class="text-center"><b>Negatif (D-)</b></th>
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
            <b class="card-title">
                5. Ranking UMKM
            </b>
        </div>
    </div>
    <div class="row mt-2"> 
        <div class="col-md-12 mt-3">
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
<?php 
    }else{
        echo '      <div class="row">';
        echo '          <div class="col col-md-12 text-center">';
        echo '              <small class="modal-title my-3">Pilih Periode Penilaian Terlebih Dulu.</small>';
        echo '          </div>';
        echo '      </div>';
    }
?>