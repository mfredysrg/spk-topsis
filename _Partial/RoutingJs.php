<?php 
    $Page = empty($_GET['Page']) ? "" : $_GET['Page'];

    if($Page == ""){
        echo '<script type="text/javascript" src="_Page/Dashboard/Dashboard.js"></script>';
    } else if($Page == "Akses"){
        echo '<script type="text/javascript" src="_Page/Akses/Akses.js"></script>';
    } else if($Page == "UMKM"){
        // ALARM PENDETEKSI FILE JAVASCRIPT UMKM
        if(file_exists("_Page/UMKM/UMKM.js")){
            echo '<script type="text/javascript" src="_Page/UMKM/UMKM.js"></script>';
        } else if(file_exists("_Page/UMKM/Umkm.js")){
            echo '<script type="text/javascript" src="_Page/UMKM/Umkm.js"></script>';
        } else {
            echo "<script>alert('ALARM ERROR: File Javascript tidak ditemukan! Pastikan nama filenya UMKM.js atau Umkm.js di dalam folder _Page/UMKM/');</script>";
        }
    } else if($Page == "BobotKriteria"){
        echo '<script type="text/javascript" src="_Page/BobotKriteria/BobotKriteria.js"></script>';
    }else if($Page == "Kriteria"){
        echo '<script type="text/javascript" src="_Page/Kriteria/Kriteria.js"></script>';
    } else if($Page == "Penilaian"){
        echo '<script type="text/javascript" src="_Page/Penilaian/Penilaian.js"></script>';
    } else if($Page == "Laporan"){
        echo '<script type="text/javascript" src="_Page/Laporan/Laporan.js"></script>';
    } else if($Page == "PenilaianUMKM"){
        echo '<script type="text/javascript" src="_Page/PenilaianUMKM/PenilaianUMKM.js"></script>';
    } else if($Page == "MyProfile"){
        echo '<script type="text/javascript" src="_Page/MyProfile/MyProfile.js"></script>';
    }

    //default Login
    echo '<script type="text/javascript" src="_Page/Login/Login.js"></script>';
    echo '<script type="text/javascript" src="_Page/Pendaftaran/Pendaftaran.js"></script>';
    echo '<script type="text/javascript" src="_Page/ResetPassword/ResetPassword.js"></script>';
?>