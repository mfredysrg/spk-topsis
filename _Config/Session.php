<?php
    // Menangkap session kemudian menampilkannya
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if(empty($_SESSION["id_akses"])){
        header("Location:Login.php");
    }else{
        $SessionIdAkses=$_SESSION ["id_akses"];
        
        //Inisiasi data akses dari database
        $QuerySessionAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$SessionIdAkses'")or die(mysqli_error($Conn));
        $DataSessionAkses = mysqli_fetch_array($QuerySessionAkses);
        
        //Apabila data akses ada
        if(!empty($DataSessionAkses['id_akses'])){
            $SessionNama= $DataSessionAkses['nama'];
            $SessionEmail= $DataSessionAkses['email'];
            $SessionAkses= $DataSessionAkses['akses'];
            
            // [REVISI SKRIPSI] Buka data UMKM sebagai pengganti tabel Umkm
            $QrySessionUMKM = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_akses='$SessionIdAkses'")or die(mysqli_error($Conn));
            $DataSessionUMKM = mysqli_fetch_array($QrySessionUMKM);
            
            // Cek berdasarkan id_umkm yang baru
            if(!empty($DataSessionUMKM['id_umkm'])){
                // Variabel session tetap bernama 'umkm' agar template sistem tidak error,
                // Namun datanya secara cerdas mengambil dari tabel 'umkm' yang sudah direvisi
                $SessionIdUMKM= $DataSessionUMKM['id_umkm'];
                $SessionNip= $DataSessionUMKM['nip'];
                $SesonKontak= $DataSessionUMKM['kontak'];
                $SessionJabatan= $DataSessionUMKM['nama_pemilik']; // Mengambil dari kolom nama_pemilik
            }else{
                $SessionIdUMKM="";
                $SessionNip="";
                $SesonKontak="";
                $SessionJabatan="";
            }
        }else{
            header("Location:Login.php");
        }
    }
?>