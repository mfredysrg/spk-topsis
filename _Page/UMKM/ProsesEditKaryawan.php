<?php
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";

    // Validasi data kosong
    if(empty($_POST['id_karyawan'])){
        echo '<small class="text-danger">ID Data tidak boleh kosong</small>';
    }else if(empty($_POST['nama'])){
        echo '<small class="text-danger">Nama tidak boleh kosong</small>';
    }else if(empty($_POST['nip'])){
        echo '<small class="text-danger">NIP / ID Lainnya tidak boleh kosong</small>';
    }else if(empty($_POST['kontak'])){
        echo '<small class="text-danger">Kontak tidak boleh kosong</small>';
    }else if(empty($_POST['email'])){
        echo '<small class="text-danger">Email tidak boleh kosong</small>';
    }else if(empty($_POST['jabatan'])){
        echo '<small class="text-danger">Jabatan / Pemilik tidak boleh kosong</small>';
    }else{
        // Tangkap Data
        $id_umkm = $_POST['id_karyawan'];
        $nama = $_POST['nama'];
        $nip = $_POST['nip'];
        $kontak = $_POST['kontak'];
        $email = $_POST['email'];
        $nama_pemilik = $_POST['jabatan'];
        
        // Dapatkan ID Akses
        $QryDetail = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
        $DataDetail = mysqli_fetch_array($QryDetail);
        $id_akses = $DataDetail['id_akses'];

        // Kemas kini jadual akses (Nama dan Email)
        $UpdateAkses = mysqli_query($Conn,"UPDATE akses SET nama='$nama', email='$email' WHERE id_akses='$id_akses'")or die(mysqli_error($Conn));
        
        if($UpdateAkses){
            // Kemas kini jadual umkm berserta NIP (menggantikan tabel umkm)
            $UpdateUMKM = mysqli_query($Conn,"UPDATE umkm SET nama_umkm='$nama', nip='$nip', kontak='$kontak', nama_pemilik='$nama_pemilik' WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
            
            if($UpdateUMKM){
                echo '<span id="NotifikasiEditKaryawanBerhasil">Success</span>';
            }else{
                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data UMKM</small>';
            }
        }else{
            echo '<small class="text-danger">Terjadi kesalahan pada saat update data akses</small>';
        }
    }
?>