<?php
    include "_Page/Logout/ModalLogout.php";
    
    $Page = empty($_GET['Page']) ? "" : $_GET['Page'];

    if($Page == "Akses"){
        include "_Page/Akses/ModalAkses.php";
    } else if($Page == "UMKM"){
        // ALARM PENDETEKSI FILE MODAL UMKM
        if(file_exists("_Page/UMKM/ModalUMKM.php")){
            include "_Page/UMKM/ModalUMKM.php";
        } else if(file_exists("_Page/UMKM/ModalKaryawan.php")){
            include "_Page/UMKM/ModalKaryawan.php";
        } else {
            echo "<script>alert('ALARM ERROR: File Modal HTML tidak ditemukan! Pastikan nama filenya ModalUMKM.php atau ModalKaryawan.php di dalam folder _Page/UMKM/');</script>";
        }
    } else if($Page == "Kriteria"){
        include "_Page/Kriteria/ModalKriteria.php";
    } else if($Page == "Penilaian"){
        include "_Page/Penilaian/ModalPenilaian.php";
    } else if($Page == "MyProfile"){
        include "_Page/MyProfile/ModalMyProfile.php";
    } else if($Page == "PenilaianKaryawan"){
        if(file_exists("_Page/PenilaianKaryawan/ModalPenilaianKaryawan.php")){
            include "_Page/PenilaianKaryawan/ModalPenilaianKaryawan.php";
        }
    } else if($Page == "Help"){
        include "_Page/Help/ModalHelp.php";
    }
?>