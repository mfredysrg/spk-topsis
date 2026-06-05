<?php
    if(empty($_GET['Sub'])){
        // Memanggil file halaman utama UMKM
        include "_Page/UMKM/UMKMHome.php"; 
    }else{
        $Sub=$_GET['Sub'];
        // Memanggil file detail jika tombol detail diklik
        if($Sub=="DetailUMKM"){
            include "_Page/UMKM/DetailUMKM.php";
        }else{
            include "_Page/UMKM/UMKMHome.php";
        }
    }
?>