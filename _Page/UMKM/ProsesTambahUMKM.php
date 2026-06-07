<?php
    include "../../_Config/Connection.php";

    if(empty($_POST['nama'])){
        echo '<small class="text-danger">Nama tidak boleh kosong</small>';
    }else if(empty($_POST['nip'])){
        echo '<small class="text-danger">NIP / ID Lainnya tidak boleh kosong</small>';
    }else if(empty($_POST['kontak'])){
        echo '<small class="text-danger">Kontak tidak boleh kosong</small>';
    }else if(empty($_POST['email'])){
        echo '<small class="text-danger">Email tidak boleh kosong</small>';
    }else if(empty($_POST['jabatan'])){
        echo '<small class="text-danger">Jabatan / Pemilik tidak boleh kosong</small>';
    }else if(empty($_POST['password'])){
        echo '<small class="text-danger">Password tidak boleh kosong</small>';
    }else{
        $nama = $_POST['nama'];
        $nip = $_POST['nip'];
        $kontak = $_POST['kontak'];
        $email = $_POST['email'];
        $nama_pemilik = $_POST['jabatan'];
        $password = $_POST['password'];

        // Cek email apakah sudah ada di tabel akses
        $QryCek = mysqli_query($Conn, "SELECT * FROM akses WHERE email='$email'");
        $CekAkses = mysqli_num_rows($QryCek);
        if($CekAkses > 0){
            echo '<small class="text-danger">Email sudah terdaftar, gunakan email lain.</small>';
        } else {
            // [REVISI FINAL] Menghapus kolom 'image' dan 'kontak' dari kueri INSERT tabel akses
            // Kita hanya memasukkan kolom yang pasti ada: nama, email, password, akses
            $InsertAkses = mysqli_query($Conn, "INSERT INTO akses (nama, email, password, akses) VALUES ('$nama', '$email', '$password', 'UMKM')");
            
            if($InsertAkses){
                // Ambil id_akses yang baru saja dibuat
                $id_akses = mysqli_insert_id($Conn);
                
                // Simpan data lengkap ke tabel umkm
                $InsertUMKM = mysqli_query($Conn, "INSERT INTO umkm (id_akses, nama_umkm, nip, kontak, nama_pemilik) VALUES ('$id_akses', '$nama', '$nip', '$kontak', '$nama_pemilik')");
                
                if($InsertUMKM){
                    echo '<span id="NotifikasiTambahUMKMBerhasil">Success</span>';
                } else {
                    // Jika gagal di tabel umkm, hapus data di tabel akses agar sinkron
                    mysqli_query($Conn, "DELETE FROM akses WHERE id_akses='$id_akses'");
                    echo '<small class="text-danger">Gagal simpan ke tabel UMKM: '.mysqli_error($Conn).'</small>';
                }
            } else {
                echo '<small class="text-danger">Gagal simpan ke tabel Akses: '.mysqli_error($Conn).'</small>';
            }
        }
    }
?>