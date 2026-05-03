<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Tangkap id_kriteria
    if(empty($_POST['id_kriteria'])){
        echo '  <div class="row">';
        echo '      <div class="col-md-6 mb-3">';
        echo '          Access ID Data Undefined.';
        echo '      </div>';
        echo '  </div>';
    }else{
        $id_kriteria=$_POST['id_kriteria'];
        //Buka data kriteria
        $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
        $DataKriteria = mysqli_fetch_array($QryKriteria);
        $id_kriteria= $DataKriteria['id_kriteria'];
        $kode_kriteria= $DataKriteria['kode_kriteria'];
        $kriteria = $DataKriteria['kriteria'];
        $atribut= $DataKriteria['atribut'];
        $bobot= $DataKriteria['bobot'];
?>
    <input type="hidden" name="id_kriteria" id="id_kriteria" value="<?php echo "$id_kriteria"; ?>">
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="alternatif">Alternatif</label>
            <input type="text" name="alternatif" id="alternatif" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="nilai">Nilai</label>
            <input type="number" min="0" name="nilai" id="nilai" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3" id="NotifikasiTambahAlternatif">
            <small class="text-primary">Pastkan data yang anda input sudah benar</small>
        </div>
    </div>
<?php } ?>