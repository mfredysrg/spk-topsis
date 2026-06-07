<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    
    // Menangkap ID (Bisa dari key id_UMKM dari JS lama, atau id_umkm)
    $id_umkm = "";
    if(!empty($_POST['id_UMKM'])){
        $id_umkm = $_POST['id_UMKM'];
    } else if(!empty($_POST['id_umkm'])){
        $id_umkm = $_POST['id_umkm'];
    }

    if(empty($id_umkm)){
        echo '  <div class="row">';
        echo '      <div class="col-md-6 mb-3">';
        echo '          Access ID Data Undefined.';
        echo '      </div>';
        echo '  </div>';
    }else{
        // [REVISI] Buka data UMKM menggantikan Umkm
        $QryDetailUMKM = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
        $DataUMKM = mysqli_fetch_array($QryDetailUMKM);
        $id_akses= $DataUMKM['id_akses'];
        
        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$id_akses'")or die(mysqli_error($Conn));
        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
        $email = $DataDetailAkses['email'];
?>
    <input type="hidden" name="id_UMKM" id="id_UMKM" value="<?php echo "$id_umkm"; ?>">
    <input type="hidden" name="id_akses" id="id_akses" value="<?php echo "$id_akses"; ?>">
    
    <div class="row">
        <div class="col-md-6 mt-3">
            <label for="password1_edit">Password</label>
            <input type="password" name="password1" id="password1_edit" class="form-control">
            <small class="credit">Password hanya boleh terdiri dari 6-20 karakter angka dan huruf</small>
        </div>
        <div class="col-md-6 mt-3">
            <label for="password2_edit">Ulangi Password</label>
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
        <div class="col-md-12 mt-3" id="NotifikasiEditPassword">
            <small class="text-primary">Pastikan data yang anda input sudah sesuai</small>
        </div>
    </div>
<?php } ?>