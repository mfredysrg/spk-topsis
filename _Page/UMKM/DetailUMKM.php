<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    //Tangkap id_UMKM
    if(empty($_GET['id_UMKM'])){
        echo '<div class="card">';
        echo '  <div class="card-header">';
        echo '      <h4 class="card-title">Halaman Error</h4>';
        echo '  </div>';
        echo '  <div class="card-body">';
        echo '      <div class="row">';
        echo '          <div class="col-md-12 mb-3 text-danger text-center">';
        echo '              ID Umkm Tidak Boleh Kosong';
        echo '          </div>';
        echo '      </div>';
        echo '  </div>';
        echo '  <div class="card-footer">';
        echo '      Silahkan Kembali Ke Halaman Sebelumnya';
        echo '  </div>';
        echo '</div>';
    }else{
        $id_UMKM=$_GET['id_UMKM'];
        //Buka data umkm
        $QryDetailUMKM = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_UMKM='$id_UMKM'")or die(mysqli_error($Conn));
        $DataUMKM = mysqli_fetch_array($QryDetailUMKM);
        $id_akses= $DataUMKM['id_akses'];
        $nama= $DataUMKM['nama'];
        $kontak = $DataUMKM['kontak'];
        $jabatan= $DataUMKM['jabatan'];
        $nip= $DataUMKM['nip'];
        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$id_akses'")or die(mysqli_error($Conn));
        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
        $email = $DataDetailAkses['email'];
        $JumlahPeriode = mysqli_num_rows(mysqli_query($Conn, "SELECT DISTINCT id_periode_penilaian FROM nilai WHERE id_UMKM='$id_UMKM'"));
?>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">
                        <i class="bi bi-info-circle"></i> Detail Umkm
                    </b>
                </div>
                <div class="col-md-2">
                    <a href="index.php?Page=Umkm" class="btn btn-md btn-dark btn-rounded btn-block">
                        <i class="bi bi-arrow-left-short"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-2"> 
                <div class="col-md-12">
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>ID Akses</dt></div>
                        <div class="col-md-7"><?php echo "$id_akses"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>ID Umkm</dt></div>
                        <div class="col-md-7"><?php echo "$id_UMKM"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>NIP</dt></div>
                        <div class="col-md-7"><?php echo "$nip"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>Nama Lengkap</dt></div>
                        <div class="col-md-7"><?php echo "$nama"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>Jabatan</dt></div>
                        <div class="col-md-7"><?php echo "$jabatan"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>Email</dt></div>
                        <div class="col-md-7"><?php echo "$email"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-5"><dt>Kontak</dt></div>
                        <div class="col-md-7"><?php echo "$kontak"; ?></div>
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
                        <i class="bi bi-pencil-square"></i> Riwayat Penilaian
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
                                        <b>Tanggal</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Keterangan</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Status</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Option</b>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    $QryNilai = mysqli_query($Conn, "SELECT DISTINCT id_periode_penilaian FROM nilai WHERE id_UMKM='$id_UMKM' ORDER BY id_periode_penilaian ASC");
                                    while ($DataNilai = mysqli_fetch_array($QryNilai)) {
                                        $id_periode_penilaian= $DataNilai['id_periode_penilaian'];
                                        //Buka detail periode_penilaian
                                        $QryPenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
                                        $DataPenilaian = mysqli_fetch_array($QryPenilaian);
                                        $tanggal = $DataPenilaian['tanggal'];
                                        $keterangan = $DataPenilaian['keterangan'];
                                        $status = $DataPenilaian['status'];
                                ?>
                                    <tr>
                                        <td class="text-center text-xs">
                                            <?php echo "$no" ?>
                                        </td>
                                        <td class="text-left text-xs">
                                            <?php echo "$tanggal" ?>
                                        </td>
                                        <td class="text-left text-xs">
                                            <?php echo "$keterangan" ?>
                                        </td>
                                        <td class="text-left text-xs">
                                            <?php echo "$status" ?>
                                        </td>
                                        <td align="center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalDetailPenilaianUMKM" data-id="<?php echo "$id_periode_penilaian,$id_UMKM"; ?>">
                                                    <i class="bi bi-three-dots-vertical"></i> Selengkapnya
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
    </div>
<?php } ?>