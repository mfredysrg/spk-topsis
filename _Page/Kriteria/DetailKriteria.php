<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    //Tangkap id_kriteria
    if(empty($_GET['id_kriteria'])){
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
        $id_kriteria=$_GET['id_kriteria'];
        //Buka data kriteria
        $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
        $DataKriteria = mysqli_fetch_array($QryKriteria);
        $kode_kriteria= $DataKriteria['kode_kriteria'];
        $kriteria = $DataKriteria['kriteria'];
        $atribut= $DataKriteria['atribut'];
        $bobot= $DataKriteria['bobot'];
        //Hitung jumlah alternatif
        $JumlahAlternatif = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM alternatif WHERE id_kriteria='$id_kriteria'"));
?>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                    <b class="card-title">
                        <i class="bi bi-info-circle"></i> Detail Kriteria
                    </b>
                </div>
                <div class="col-md-2">
                    <a href="index.php?Page=Kriteria" class="btn btn-md btn-dark btn-rounded btn-block">
                        <i class="bi bi-arrow-left-short"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-2"> 
                <div class="col-md-12">
                    <div class="row mt-2"> 
                        <div class="col-md-12">
                            <table class="">
                                <tbody>
                                    <tr>
                                        <td>
                                            <small><dt>Kode Kriteria</dt></small>
                                        </td>
                                        <td><b>:</b></td>
                                        <td>
                                            <small><?php echo $kode_kriteria; ?></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <small><dt>Kriteria</dt></small>
                                        </td>
                                        <td><b>:</b></td>
                                        <td>
                                            <small><?php echo $kriteria; ?></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <small><dt>Atribut</dt></small>
                                        </td>
                                        <td><b>:</b></td>
                                        <td>
                                            <small><?php echo $atribut; ?></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <small><dt>Bobot</dt></small>
                                        </td>
                                        <td><b>:</b></td>
                                        <td>
                                            <small><?php echo $bobot; ?></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <small><dt>Alternatif Jawaban</dt></small>
                                        </td>
                                        <td><b>:</b></td>
                                        <td>
                                            <small><?php echo $JumlahAlternatif; ?></small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9">
                    <b class="card-title">
                        <i class="bi bi-question-circle"></i> Alternatif Jawaban
                    </b>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary btn-rounded btn-md w-100" data-bs-toggle="modal" data-bs-target="#ModalTambahAlternatif" data-id="<?php echo "$id_kriteria"; ?>">
                        <i class="bi bi-plus-circle"></i> Tambah Alternatif
                    </button>  
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
                                        <b>Alternatif</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Nilai</b>
                                    </th>
                                    <th class="text-center">
                                        <b>Option</b>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(empty($JumlahAlternatif)){
                                        echo '<tr>';
                                        echo '  <td colspan="4" align="center">';
                                        echo '      Belum Ada Alternatif Jawaban';
                                        echo '  </td>';
                                        echo '</tr>';
                                    }else{
                                        $no = 1;
                                        //List Data Alternatif
                                        $QryAlternatif = mysqli_query($Conn, "SELECT*FROM alternatif WHERE id_kriteria='$id_kriteria'");
                                        while ($DataAlternatif = mysqli_fetch_array($QryAlternatif)) {
                                            $id_alternatif= $DataAlternatif['id_alternatif'];
                                            $alternatif= $DataAlternatif['alternatif'];
                                            $nilai= $DataAlternatif['nilai'];
                                ?>
                                    <tr>
                                        <td class="text-center text-xs">
                                            <?php echo "$no" ?>
                                        </td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "$alternatif";
                                            ?>
                                        </td>
                                        <td class="text-left" align="left">
                                            <?php 
                                                echo "$nilai";
                                            ?>
                                        </td>
                                        <td align="center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditAlternatif" data-id="<?php echo "$id_alternatif"; ?>">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>  
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#ModalHapusAlternatif" data-id="<?php echo "$id_alternatif"; ?>">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>  
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                    $no++; }}
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>