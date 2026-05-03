<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Tangkap id_akses
    if(empty($_POST['id_akses'])){
        echo '  <div class="row">';
        echo '      <div class="col-md-6 mb-3">';
        echo '          Access ID Data Undefined.';
        echo '      </div>';
        echo '  </div>';
    }else{
        $id_akses=$_POST['id_akses'];
        //Buka data askes
        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$id_akses'")or die(mysqli_error($Conn));
        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
        $nama_akses= $DataDetailAkses['nama'];
        $email_akses = $DataDetailAkses['email'];
        $password= $DataDetailAkses['password'];
        $akses= $DataDetailAkses['akses'];
?>
    <input type="hidden" name="id_akses" id="id_akses_edit" value="<?php echo "$id_akses"; ?>">
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="nama">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?php echo "$nama_akses"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mt-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo "$email_akses"; ?>">
        </div>
        <div class="col-md-6 mt-3">
            <label for="akses">Akses</label>
            <select name="akses" id="akses" class="form-control">
                <option <?php if($akses==""){echo "selected";} ?> value="">Pilih..</option>
                <option <?php if($akses=="HRD"){echo "selected";} ?> value="HRD">HRD</option>
                <option <?php if($akses=="Pimpinan"){echo "selected";} ?> value="Pimpinan">Pimpinan</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="password1">Password</label>
            <input type="password" name="password1" id="password1_edit" class="form-control">
            <small class="credit">Password hanya boleh terdiri dari 6-20 karakter angka dan huruf</small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="password2">Ulangi Password</label>
            <input type="password" name="password2" id="password2_edit" class="form-control">
            <small class="credit">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Tampilkan" id="TampilkanPassword2" name="TampilkanPassword2">
                    <label class="form-check-label" for="TampilkanPassword2">
                        Tampilkan Password
                    </label>
                </div>
            </small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3" id="NotifikasiEditAkses">
            <small class="text-primary">Pastikan data yang anda input sudah sesuai</small>
        </div>
    </div>
<?php } ?>