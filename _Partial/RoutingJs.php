<?php 
    if(empty($_GET['Page'])){
        echo '<script type="text/javascript" src="_Page/Dashboard/Dashboard.js"></script>';
    }else{
        if($_GET['Page']=="Akses"){
            echo '<script type="text/javascript" src="_Page/Akses/Akses.js"></script>';
        }else{
            if($_GET['Page']=="Karyawan"){
                echo '<script type="text/javascript" src="_Page/Karyawan/Karyawan.js"></script>';
            }else{
                if($_GET['Page']=="Kriteria"){
                    echo '<script type="text/javascript" src="_Page/Kriteria/Kriteria.js"></script>';
                }else{
                    if($Page=="Penilaian"){
                        echo '<script type="text/javascript" src="_Page/Penilaian/Penilaian.js"></script>';
                    }else{
                        if($Page=="Laporan"){
                            echo '<script type="text/javascript" src="_Page/Laporan/Laporan.js"></script>';
                        }else{
                            if($Page=="PenilaianKaryawan"){
                                echo '<script type="text/javascript" src="_Page/PenilaianKaryawan/PenilaianKaryawan.js"></script>';
                            }else{
                                if($Page=="MyProfile"){
                                    echo '<script type="text/javascript" src="_Page/MyProfile/MyProfile.js"></script>';
                                }else{
                                    echo '<script type="text/javascript" src="_Page/Dashboard/Dashboard.js"></script>';
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    //default Login
    echo '<script type="text/javascript" src="_Page/Login/Login.js"></script>';
    echo '<script type="text/javascript" src="_Page/Pendaftaran/Pendaftaran.js"></script>';
    echo '<script type="text/javascript" src="_Page/ResetPassword/ResetPassword.js"></script>';
?>