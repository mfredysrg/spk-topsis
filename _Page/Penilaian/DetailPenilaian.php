<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    //Tangkap id_periode_penilaian
    if(empty($_GET['id_periode_penilaian'])){
        echo '<div class="card">';
        echo '  <div class="card-header">';
        echo '      <h4 class="card-title">Detail Periode Penilaian Tidak Bisa Dibuka</h4>';
        echo '  </div>';
        echo '  <div class="card-body">';
        echo '      <div class="row">';
        echo '          <div class="col-md-12 mb-3 text-danger text-center">';
        echo '              ID Periode Penilaian Tidak Boleh Kosong';
        echo '          </div>';
        echo '      </div>';
        echo '  </div>';
        echo '  <div class="card-footer">';
        echo '      Silahkan Kembali Ke Halaman Sebelumnya';
        echo '  </div>';
        echo '</div>';
    }else{
        $id_periode_penilaian=$_GET['id_periode_penilaian'];
        //Buka data periode penilaian
        $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
        $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
        $id_periode_penilaian= $DataPeriodePenilaian['id_periode_penilaian'];
        $tanggal= $DataPeriodePenilaian['tanggal'];
        $keterangan= $DataPeriodePenilaian['keterangan'];
        $status= $DataPeriodePenilaian['status'];
        //Jumlah Kriteria
        $JumlahKriteria = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian'"));
        //Jumlah Karyawan
        $JumlahKaryawan = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_karyawan FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian'"));
?>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">
                        <i class="bi bi-info-circle"></i> Detail Sesi Penilaian
                    </b>
                </div>
                <div class="col-md-2">
                    <a href="index.php?Page=Penilaian" class="btn btn-md btn-dark btn-rounded btn-block">
                        <i class="bi bi-arrow-left-short"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-2"> 
                <div class="col-md-12">
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>Tanggal Pelaksanaan</dt></div>
                        <div class="col-md-7"><?php echo "$tanggal"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>Keterangan</dt></div>
                        <div class="col-md-7"><?php echo "$keterangan"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>Status Sesi Penilaian</dt></div>
                        <div class="col-md-7"><?php echo "$status"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>Kriteria Penilaian</dt></div>
                        <div class="col-md-7"><?php echo "$JumlahKriteria Data"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>Karyawan Dinilai</dt></div>
                        <div class="col-md-7"><?php echo "$JumlahKaryawan Data"; ?></div>
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
                        <i class="bi bi-pencil-square"></i> Form Penilaian
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
                                    <th class="text-center">
                                        <b>Option</b>
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
                                            //Arraykan kriteria
                                            if($status=="Proses"){
                                                $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
                                            }else{
                                                $query = mysqli_query($Conn, "SELECT DISTINCT id_kriteria FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY id_kriteria ASC");
                                            }
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
                                                echo '<td class="text-center">'.$nilai.'</td>';
                                            }
                                        ?>
                                        <td align="center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditNilai" data-id="<?php echo "$id_periode_penilaian,$id_karyawan"; ?>">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>  
                                            </div>
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
            <button type="button" class="btn btn-primary btn-md w-100" data-bs-toggle="modal" data-bs-target="#ModalHitungPenilaian" data-id="<?php echo "$id_periode_penilaian"; ?>">
                <i class="bi bi-arrow-down-circle"></i> Hitung Penilaian
            </button> 
        </div>
    </div>
    <div id="HasilPerhitungan">

    </div>
<?php } ?>