<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Tangkap id_alternatif
    if(empty($_POST['id_alternatif'])){
        echo '  <div class="row">';
        echo '      <div class="col-md-6 mb-3">';
        echo '          Access ID Data Undefined.';
        echo '      </div>';
        echo '  </div>';
    }else{
        $id_alternatif=$_POST['id_alternatif'];
        //Buka data alternatif
        $QryAlternatif = mysqli_query($Conn,"SELECT * FROM alternatif WHERE id_alternatif='$id_alternatif'")or die(mysqli_error($Conn));
        $DataAlternatif = mysqli_fetch_array($QryAlternatif);
        $id_kriteria= $DataAlternatif['id_kriteria'];
        $alternatif= $DataAlternatif['alternatif'];
        $nilai = $DataAlternatif['nilai'];
?>
    <input type="hidden" name="id_alternatif" id="id_alternatif" value="<?php echo "$id_alternatif"; ?>">
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="alternatif">Alternatif</label>
            <input type="text" name="alternatif" id="alternatif" class="form-control" value="<?php echo "$alternatif"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="nilai">Nilai</label>
            <input type="number" min="0" name="nilai" id="nilai" class="form-control" value="<?php echo "$nilai"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3" id="NotifikasiEditAlternatif">
            <small class="text-primary">Pastkan data yang anda input sudah benar</small>
        </div>
    </div>
<?php } ?>