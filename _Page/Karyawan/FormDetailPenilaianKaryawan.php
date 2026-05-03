<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Tangkap id_periode_penilaian
    if(empty($_POST['id_periode_penilaian'])){
        echo '  <div class="row">';
        echo '      <div class="col-md-12 mb-3">';
        echo '          ID Penilaan Tidak Ditemukan.';
        echo '      </div>';
        echo '  </div>';
    }else{
        if(empty($_POST['id_karyawan'])){
            echo '  <div class="row">';
            echo '      <div class="col-md-12 mb-3">';
            echo '          ID Karyawan Tidak Ditemukan.';
            echo '      </div>';
            echo '  </div>';
        }else{
            $id_periode_penilaian=$_POST['id_periode_penilaian'];
            $id_karyawan=$_POST['id_karyawan'];
            //Buka data karyawan
            $QryDetailKaryawan = mysqli_query($Conn,"SELECT * FROM karyawan WHERE id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
            $DataKaryawan = mysqli_fetch_array($QryDetailKaryawan);
            $id_akses= $DataKaryawan['id_akses'];
            $nama= $DataKaryawan['nama'];
            $kontak = $DataKaryawan['kontak'];
            $jabatan= $DataKaryawan['jabatan'];
            $nip= $DataKaryawan['nip'];
            $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$id_akses'")or die(mysqli_error($Conn));
            $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
            $email = $DataDetailAkses['email'];
            $JumlahNilai = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM nilai WHERE id_karyawan='$id_karyawan' AND id_periode_penilaian='$id_periode_penilaian'"));
            //Buka Nilai Preferensi
            $QryPreferensi = mysqli_query($Conn,"SELECT * FROM preferensi WHERE id_karyawan='$id_karyawan' AND id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
            $DataPreferensi = mysqli_fetch_array($QryPreferensi);
            $positif= $DataPreferensi['positif'];
            $negatif= $DataPreferensi['negatif'];
            $preferensi= $DataPreferensi['preferensi'];
?>
    <div class="row mt-2"> 
        <div class="col-md-12 mb-3">
            <b class="card-title">Penilaian Karyawan</b>
        </div>
    </div>
    <div class="row mt-2"> 
        <div class="col-md-12 mb-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-items-center mb-0">
                    <thead class="">
                        <tr>
                            <th class="text-center" rowspan="2">
                                <b>No</b>
                            </th>
                            <th class="text-center" rowspan="2">
                                <b>Kriteria</b>
                            </th>
                            <th class="text-center" rowspan="2">
                                <b>Bobot</b>
                            </th>
                            <th class="text-center" rowspan="2">
                                <b>Nilai</b>
                            </th>
                            <th class="text-center" rowspan="2">
                                <b>Normalisasi</b>
                            </th>
                            <th class="text-center" colspan="2">
                                <b>Solusi Ideal</b>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">
                                <b>Positiff</b>
                            </th>
                            <th class="text-center">
                                <b>Negatif</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $QryNilai = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_karyawan='$id_karyawan' AND id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                            while ($DataNiai = mysqli_fetch_array($QryNilai)) {
                                $id_kriteria= $DataNiai['id_kriteria'];
                                //Buka detail Kriteria
                                $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                                $DataKriteria = mysqli_fetch_array($QryKriteria);
                                $kriteria = $DataKriteria['kriteria'];
                                $atribut = $DataKriteria['atribut'];
                                $bobot = $DataKriteria['bobot'];
                                //Buka Nilai
                                $QryPenilaian = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_kriteria='$id_kriteria' AND id_karyawan='$id_karyawan' AND id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
                                $DataPenilaian = mysqli_fetch_array($QryPenilaian);
                                $nilai = $DataPenilaian['nilai'];
                                //Buka Normalisasi
                                $QryNormalisasi = mysqli_query($Conn,"SELECT * FROM normalisasi_terbobot WHERE id_kriteria='$id_kriteria' AND id_karyawan='$id_karyawan' AND id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
                                $DataNormalisasi = mysqli_fetch_array($QryNormalisasi);
                                $normalisasi_terbobot = $DataNormalisasi['normalisasi_terbobot'];
                                //Solusi Ideal Positif
                                $QrySolusiIdealPositif = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_kriteria='$id_kriteria' AND positif_negatif='Positif' AND id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
                                $DataSolusiIdealPositif = mysqli_fetch_array($QrySolusiIdealPositif);
                                $solusi_ideal_positif = $DataSolusiIdealPositif['solusi_ideal'];
                                //Solusi Ideal negatif
                                $QrySolusiIdealNegatif = mysqli_query($Conn,"SELECT * FROM solusi_ideal WHERE id_kriteria='$id_kriteria' AND positif_negatif='Negatif' AND id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
                                $DataSolusiIdealNegatif = mysqli_fetch_array($QrySolusiIdealNegatif);
                                $solusi_ideal_negatif = $DataSolusiIdealNegatif['solusi_ideal'];
                        ?>
                            <tr>
                                <td class="text-center text-xs">
                                    <?php echo "$no" ?>
                                </td>
                                <td class="text-left" align="left">
                                    <?php 
                                        echo "<b>$kriteria</b><br>";
                                        echo "<small>$atribut</small>";
                                    ?>
                                </td>
                                <td class="text-left" align="left">
                                    <?php 
                                        echo "<small>$bobot</small>";
                                    ?>
                                </td>
                                <td class="text-right" align="right">
                                    <?php 
                                        echo "<small>$nilai</small>";
                                    ?>
                                </td>
                                <td class="text-right" align="right">
                                    <?php 
                                        echo "<small>$normalisasi_terbobot</small>";
                                    ?>
                                </td>
                                <td class="text-right" align="right">
                                    <?php 
                                        echo "<small>$solusi_ideal_positif</small>";
                                    ?>
                                </td>
                                <td class="text-right" align="right">
                                    <?php 
                                        echo "<small>$solusi_ideal_negatif</small>";
                                    ?>
                                </td>
                            </tr>
                        <?php
                            $no++; }
                        ?>
                        <tr>
                            <td colspan="5">
                                <b>Nilai Preferensi</b>
                                <small>
                                    <?php 
                                        echo "($preferensi)";
                                    ?>
                                </small>
                            </td>
                            <td>
                                <?php 
                                    echo "<small>$positif</small>";
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo "<small>$negatif</small>";
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php }} ?>