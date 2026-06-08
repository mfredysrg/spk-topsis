<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    
    //Tangkap id_UMKM (dari JS AJAX)
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
        // Buka data UMKM
        $QryDetailUMKM = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
        $DataUMKM = mysqli_fetch_array($QryDetailUMKM);
        $id_akses = $DataUMKM['id_akses'];
        $nama_umkm = $DataUMKM['nama_umkm'];
        $kontak = $DataUMKM['kontak'];
        $nama_pemilik = $DataUMKM['nama_pemilik']; 
        
        // Panggil NIP kembali (jika tiada, ia akan kosong)
        $nip = isset($DataUMKM['nip']) ? $DataUMKM['nip'] : ''; 
        
        // Buka data email di tabel akses
        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$id_akses'")or die(mysqli_error($Conn));
        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
        $email = $DataDetailAkses['email'];
?>
    <input type="hidden" name="id_UMKM" id="id_UMKM" value="<?php echo "$id_umkm"; ?>">
    <input type="hidden" name="id_akses" id="id_akses" value="<?php echo "$id_akses"; ?>">
    
    <div class="row">
        <div class="col-md-12 mt-3">
            <label for="nama">Nama UMKM / Lengkap</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?php echo "$nama_umkm"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mt-3">
            <label for="nip">Lokasi Usaha</label>
            <input type="text" name="nip" id="nip" class="form-control" value="<?php echo "$nip"; ?>">
        </div>
        <div class="col-md-6 mt-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo "$email"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mt-3">
            <label for="kontak">No. Kontak / HP</label>
            <input type="text" name="kontak" id="kontak" class="form-control" value="<?php echo "$kontak"; ?>">
        </div>
        <div class="col-md-6 mt-3">
            <label for="jabatan">Nama Pemilik Usaha</label>
            <input type="text" name="jabatan" id="jabatan" class="form-control" value="<?php echo "$nama_pemilik"; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-3" id="NotifikasiEditUMKM">
            <small class="text-primary">Pastikan data yang anda input sudah sesuai</small>
        </div>
    </div>
<?php } ?>