<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Tangkap id_kriteria
    if(empty($_POST['id_kriteria'])){
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
        $id_kriteria=$_POST['id_kriteria'];
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
<div class="modal-body">
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
<div class="modal-footer bg-info">
    <a href="index.php?Page=Kriteria&Sub=DetailKriteria&id_kriteria=<?php echo "$id_kriteria"; ?>" class="btn btn-success btn-rounded">
        <i class="bi bi-list-columns"></i> Alternatif Jawaban
    </a>
    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
        <i class="bi bi-x-circle"></i> Tutup
    </button>
</div>
<?php } ?>