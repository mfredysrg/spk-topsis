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

<div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">1. Matriks Ternormalisasi ('R)</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-2"> 
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-items-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center"><b>No</b></th>
                                    <th class="text-center"><b>Nama UMKM</b></th>
                                    <?php
                                        // Hitung SQRT Pembagi untuk setiap kriteria
                                        $pembagi_kriteria = [];
                                        // Mencegah Kriteria Hantu dengan JOIN
                                        $query = mysqli_query($Conn, "SELECT DISTINCT n.id_kriteria FROM nilai n JOIN kriteria k ON n.id_kriteria = k.id_kriteria WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_kriteria ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria= $data['id_kriteria'];

                                            // Hitung Nilai SQRT murni hanya untuk UMKM yang masih aktif
                                            $sum_sq = 0;
                                            $qU = mysqli_query($Conn, "SELECT DISTINCT n.id_umkm FROM nilai n JOIN umkm u ON n.id_umkm = u.id_umkm WHERE n.id_periode_penilaian='$id_periode_penilaian'");
                                            while ($du = mysqli_fetch_array($qU)) {
                                                $id_u = $du['id_umkm'];
                                                $qN = mysqli_query($Conn, "SELECT nilai FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_u' AND id_kriteria='$id_kriteria'");
                                                $dN = mysqli_fetch_array($qN);
                                                $val = floatval($dN['nilai'] ?? 0);
                                                $sum_sq += ($val * $val);
                                            }
                                            $sqrt_val = floatval(sqrt($sum_sq));
                                            $pembagi_kriteria[$id_kriteria] = $sqrt_val;

                                            // Tarik info kriteria untuk kebutuhan render header
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
                                    // JOIN UMKM mematikan kemunculan data UMKM terhapus di laporan
                                    $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT n.id_umkm, u.nama_umkm, u.nama_pemilik FROM nilai n JOIN umkm u ON n.id_umkm = u.id_umkm WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_umkm ASC");
                                    while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                        $id_umkm= $DataUMKM['id_umkm'];
                                        $nama_umkm = $DataUMKM['nama_umkm'];
                                        $nama_pemilik = $DataUMKM['nama_pemilik'];
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
                                            $query = mysqli_query($Conn, "SELECT DISTINCT n.id_kriteria FROM nilai n JOIN kriteria k ON n.id_kriteria = k.id_kriteria WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_kriteria ASC");
                                            while ($data = mysqli_fetch_array($query)) {
                                                $id_kriteria= $data['id_kriteria'];
                                                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                                $DataNilai = mysqli_fetch_array($QryNilai);
                                                $nilai = floatval($DataNilai['nilai'] ?? 0);
                                                
                                                // Rumus R = Xij / SQRT Pembagi
                                                $pembagi = $pembagi_kriteria[$id_kriteria] ?? 0;
                                                $rij = ($pembagi == 0) ? 0 : ($nilai / $pembagi);

                                                // Print desimal 4 angka di belakang koma murni identik Excel
                                                echo '<td align="right">'.number_format($rij, 4, '.', '').'</td>';
                                            }
                                        ?>
                                    </tr>
                                <?php $no++; } ?>
                                
                                <tr class="table-light" style="background-color: #f8f9fa;">
                                    <td class="text-center text-xs" colspan="2"><b>Nilai Pembagi (SQRT)</b></td>
                                    <?php
                                        $query = mysqli_query($Conn, "SELECT DISTINCT n.id_kriteria FROM nilai n JOIN kriteria k ON n.id_kriteria = k.id_kriteria WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_kriteria ASC");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_kriteria = $data['id_kriteria'];
                                            $pembagi = $pembagi_kriteria[$id_kriteria] ?? 0;
                                            
                                            echo '<td align="right"><b>'.number_format($pembagi, 4, '.', '').'</b></td>';
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
                            $query = mysqli_query($Conn, "SELECT DISTINCT n.id_kriteria FROM nilai n JOIN kriteria k ON n.id_kriteria = k.id_kriteria WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_kriteria ASC");
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
                        $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT n.id_umkm, u.nama_umkm, u.nama_pemilik FROM nilai n JOIN umkm u ON n.id_umkm = u.id_umkm WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_umkm ASC");
                        while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                            $id_umkm= $DataUMKM['id_umkm'] ?? 0;
                            $nama_umkm = $DataUMKM['nama_umkm'] ?? '-';
                            $nama_pemilik = $DataUMKM['nama_pemilik'] ?? '-';
                    ?>
                        <tr>
                            <td class="text-center text-xs"><?php echo $no; ?></td>
                            <td class="text-left" align="left">
                                <b><?php echo $nama_umkm; ?></b><br>
                                <small>Pemilik: <?php echo $nama_pemilik; ?></small>
                            </td>
                            <?php
                                $query = mysqli_query($Conn, "SELECT DISTINCT n.id_kriteria FROM nilai n JOIN kriteria k ON n.id_kriteria = k.id_kriteria WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_kriteria ASC");
                                while ($data = mysqli_fetch_array($query)) {
                                    $id_kriteria= $data['id_kriteria'];
                                    $QryNormalisasiTerbobot = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND id_umkm='$id_umkm'")or die(mysqli_error($Conn));
                                    $DataNormalisasiTerbobot = mysqli_fetch_array($QryNormalisasiTerbobot);
                                    $nilai_terbobot = floatval($DataNormalisasiTerbobot['normalisasi_terbobot'] ?? 0);
                                    echo '<td align="right"><span class="text-success">'.number_format($nilai_terbobot, 4, '.', '').'</span></td>';
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
                            $query = mysqli_query($Conn, "SELECT DISTINCT n.id_kriteria FROM nilai n JOIN kriteria k ON n.id_kriteria = k.id_kriteria WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_kriteria ASC");
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
                            $query = mysqli_query($Conn, "SELECT DISTINCT n.id_kriteria FROM nilai n JOIN kriteria k ON n.id_kriteria = k.id_kriteria WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_kriteria ASC");
                            while ($data = mysqli_fetch_array($query)) {
                                $id_kriteria= $data['id_kriteria'];
                                $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Positif'")or die(mysqli_error($Conn));
                                $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                echo '<td align="right">'.number_format(floatval($DataSolusiIdeal['solusi_ideal'] ?? 0), 4, '.', '').'</td>';
                            }
                        ?>
                    </tr>
                    <tr>
                        <td class="text-left text-xs"><b>Negatif (A-)</b></td>
                        <?php
                            $query = mysqli_query($Conn, "SELECT DISTINCT n.id_kriteria FROM nilai n JOIN kriteria k ON n.id_kriteria = k.id_kriteria WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_kriteria ASC");
                            while ($data = mysqli_fetch_array($query)) {
                                $id_kriteria= $data['id_kriteria'];
                                $QrySolusiIdeal = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_periode_penilaian='$id_periode_penilaian' AND id_kriteria='$id_kriteria' AND positif_negatif='Negatif'")or die(mysqli_error($Conn));
                                $DataSolusiIdeal = mysqli_fetch_array($QrySolusiIdeal);
                                echo '<td align="right">'.number_format(floatval($DataSolusiIdeal['solusi_ideal'] ?? 0), 4, '.', '').'</td>';
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
                        $QryUMKM = mysqli_query($Conn, "SELECT DISTINCT n.id_umkm, u.nama_umkm, u.nama_pemilik FROM nilai n JOIN umkm u ON n.id_umkm = u.id_umkm WHERE n.id_periode_penilaian='$id_periode_penilaian' ORDER BY n.id_umkm ASC");
                        while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                            $id_umkm= $DataUMKM['id_umkm'] ?? 0;
                            $nama_umkm = $DataUMKM['nama_umkm'] ?? '-';
                            $nama_pemilik = $DataUMKM['nama_pemilik'] ?? '-';
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
                                
                                $positif = floatval($DataPreferensi['positif'] ?? 0);
                                $negatif = floatval($DataPreferensi['negatif'] ?? 0);
                                $preferensi = floatval($DataPreferensi['preferensi'] ?? 0);
                                
                                echo '<td align="right">'.number_format($positif, 4, '.', '').'</td>';
                                echo '<td align="right">'.number_format($negatif, 4, '.', '').'</td>';
                                echo '<td align="right"><b>'.number_format($preferensi, 4, '.', '').'</b></td>';
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
                        // Mencegah UMKM terhapus muncul di perankingan dengan JOIN langsung ke tabel umkm
                        $QryPreferensi = mysqli_query($Conn, "SELECT p.*, u.nama_umkm, u.nama_pemilik FROM preferensi p JOIN umkm u ON p.id_umkm = u.id_umkm WHERE p.id_periode_penilaian='$id_periode_penilaian' ORDER BY p.preferensi DESC");
                        while ($DataPreferensi = mysqli_fetch_array($QryPreferensi)) {
                            $preferensi = floatval($DataPreferensi['preferensi'] ?? 0);
                            $nama_umkm = $DataPreferensi['nama_umkm'] ?? '-';
                            $nama_pemilik = $DataPreferensi['nama_pemilik'] ?? '-';
                    ?>
                        <tr>
                            <td class="text-center text-xs">
                                <b class="text-success h5"><?php echo $no; ?></b>
                            </td>
                            <td class="text-left" align="left"><?php echo $nama_umkm; ?></td>
                            <td class="text-left" align="left"><?php echo $nama_pemilik; ?></td>
                            <td class="text-center" align="center"><b><?php echo number_format($preferensi, 4, '.', ''); ?></b></td>
                        </tr>
                    <?php $no++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>