<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Tangkap id_karyawan
    if(empty($_POST['id_karyawan'])){
        echo '  <div class="row">';
        echo '      <div class="col-md-6 mb-3">';
        echo '          Access ID Data Undefined.';
        echo '      </div>';
        echo '  </div>';
    }else{
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
?>
    <input type="hidden" name="id_karyawan" id="id_karyawan" value="<?php echo "$id_karyawan"; ?>">
    <input type="hidden" name="id_akses" id="id_akses" value="<?php echo "$id_akses"; ?>">
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="nama">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?php echo "$nama"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mt-3">
            <label for="nip">NIP</label>
            <input type="text" name="nip" id="nip" class="form-control" value="<?php echo "$nip"; ?>">
        </div>
        <div class="col-md-6 mt-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo "$email"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mt-3">
            <label for="kontak">No.Konta</label>
            <input type="text" name="kontak" id="kontak" class="form-control" value="<?php echo "$kontak"; ?>">
        </div>
        <div class="col-md-6 mt-3">
            <label for="jabatan">Jabatan</label>
            <input type="text" name="jabatan" id="jabatan" class="form-control" value="<?php echo "$jabatan"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3" id="NotifikasiEditKaryawan">
            <small class="text-primary">Pastikan data yang anda input sudah sesuai</small>
        </div>
    </div>
<?php } ?>