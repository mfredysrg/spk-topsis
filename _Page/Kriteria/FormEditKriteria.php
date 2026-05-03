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
            <label for="kode_kriteria">Kode Kriteria</label>
            <input type="text" name="kode_kriteria" id="kode_kriteria" class="form-control" value="<?php echo "$kode_kriteria"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="kriteria">Kriteria</label>
            <input type="text" name="kriteria" id="kriteria" class="form-control" value="<?php echo "$kriteria"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mt-3">
            <label for="atribut">Atribut</label>
            <select name="atribut" id="atribut" class="form-control">
                <option <?php if($atribut==""){echo "selected";} ?> value="">Pilih..</option>
                <option <?php if($atribut=="Benefit"){echo "selected";} ?> value="Benefit">Benefit</option>
                <option <?php if($atribut=="Cost"){echo "selected";} ?> value="Cost">Cost</option>
            </select>
        </div>
        <div class="col-md-6 mt-3">
            <label for="bobot">Bobot</label>
            <input type="number" min="0" name="bobot" id="bobot" class="form-control" value="<?php echo "$bobot"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3" id="NotifikasiEditKriteria">
            <small class="text-primary">Pastkan data yang anda input sudah benar</small>
        </div>
    </div>
<?php } ?>