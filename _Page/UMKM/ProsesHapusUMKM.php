<?php
    //Connection
    include "../../_Config/Connection.php";
    
    // Tetap menangkap id_UMKM karena dikirim dari AJAX JS
    if(empty($_POST['id_UMKM'])){
        echo '<span class="text-danger">ID UMKM tidak dapat ditangkap oleh sistem</span>';
    }else{
        $id_umkm = $_POST['id_UMKM'];
        
        // [REVISI] Ambil id_akses dari tabel umkm
        $QryDetailUMKM = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
        $DataUMKM = mysqli_fetch_array($QryDetailUMKM);
        $id_akses = $DataUMKM['id_akses'];
        
        // Proses hapus data akses terlebih dahulu (jika ada relasi, hapus parent/child dengan benar)
        $query = mysqli_query($Conn, "DELETE FROM akses WHERE id_akses='$id_akses'") or die(mysqli_error($Conn));
        if ($query) {
            
            // [REVISI] Hapus dari tabel umkm
            $query2 = mysqli_query($Conn, "DELETE FROM umkm WHERE id_umkm='$id_umkm'") or die(mysqli_error($Conn));
            
            if ($query2) {
                // ID NotifikasiHapusUMKMBerhasil dibiarkan agar dibaca "Success" oleh file JS
                echo '<span class="text-success" id="NotifikasiHapusUMKMBerhasil">Success</span>';
            }else{
                echo '<span class="text-danger">Hapus Data UMKM Gagal</span>';
            }
        }else{
            echo '<span class="text-danger">Hapus Data Akses Gagal</span>';
        }
    }
?>