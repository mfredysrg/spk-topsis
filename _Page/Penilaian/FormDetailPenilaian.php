<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Tangkap id_periode_penilaian
    if(empty($_POST['id_periode_penilaian'])){
        echo '<div class="modal-body">';
        echo '  <div class="row">';
        echo '      <div class="col-md-12 mb-3">';
        echo '          ID Akses Tidak Ditemukan.';
        echo '      </div>';
        echo '  </div>';
        echo '</div>';
        echo ' <div class="modal-footer bg-info">';
        echo '  <div class="row">';
        echo '      <div class="col-md-12 mb-3">';
        echo '          <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">';
        echo '              <i class="bi bi-x-circle"></i> Tutup';
        echo '          </button>';
        echo '      </div>';
        echo '  </div>';
        echo '</div>';
    }else{
        $id_periode_penilaian=$_POST['id_periode_penilaian'];
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
<div class="modal-body">
    <div class="row mt-2"> 
        <div class="col-md-12">
            <table class="">
                <tbody>
                    <tr>
                        <td>
                            <small><dt>Tanggal</dt></small>
                        </td>
                        <td><b>:</b></td>
                        <td>
                            <small><?php echo $tanggal; ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><dt>Keterangan</dt></small>
                        </td>
                        <td><b>:</b></td>
                        <td>
                            <small><?php echo $keterangan; ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><dt>Status</dt></small>
                        </td>
                        <td><b>:</b></td>
                        <td>
                            <small><?php echo $status; ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><dt>Kriteria</dt></small>
                        </td>
                        <td><b>:</b></td>
                        <td>
                            <small><?php echo "$JumlahKriteria Data"; ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><dt>Karyawan</dt></small>
                        </td>
                        <td><b>:</b></td>
                        <td>
                            <small><?php echo "$JumlahKaryawan"; ?></small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer bg-info">
    <a href="index.php?Page=Penilaian&Sub=DetailPenilaian&id_periode_penilaian=<?php echo "$id_periode_penilaian"; ?>" class="btn btn-success btn-rounded">
        Selengkapnya <i class="bi bi-three-dots"></i> 
    </a>
    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
        <i class="bi bi-x-circle"></i> Tutup
    </button>
</div>
<?php } ?>