<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Tangkap id_periode_penilaian
    if(empty($_POST['id_periode_penilaian'])){
        echo '  <div class="row">';
        echo '      <div class="col-md-6 mb-3">';
        echo '          Access ID Data Undefined.';
        echo '      </div>';
        echo '  </div>';
    }else{
        $id_periode_penilaian=$_POST['id_periode_penilaian'];
        //Buka data Periode Penilaian
        $QryPenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
        $DataPenilaian = mysqli_fetch_array($QryPenilaian);
        $tanggal= $DataPenilaian['tanggal'];
        $keterangan= $DataPenilaian['keterangan'];
        $status= $DataPenilaian['status'];
?>
    <input type="hidden" name="id_periode_penilaian" id="id_periode_penilaian" value="<?php echo "$id_periode_penilaian"; ?>">
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo "$tanggal"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="keterangan">Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control" value="<?php echo "$keterangan"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option <?php if($status==""){echo "selected";} ?> value="">Pilih..</option>
                <option <?php if($status=="Selesai"){echo "selected";} ?> value="Selesai">Selesai</option>
                <option <?php if($status=="Proses"){echo "selected";} ?> value="Proses">Proses</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3" id="NotifikasiEditPenilaian">
            <small class="text-primary">Pastkan data yang anda input sudah benar</small>
        </div>
    </div>
<?php } ?>