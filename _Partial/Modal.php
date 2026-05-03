<?php
    include "_Page/Logout/ModalLogout.php";
    if(empty($_GET['Page'])){
        $Page="";
    }else{
        $Page=$_GET['Page'];
    }
    if($Page=="Akses"){
        include "_Page/Akses/ModalAkses.php";
    }else{
        if($Page=="Karyawan"){
            include "_Page/Karyawan/ModalKaryawan.php";
        }else{
            if($Page=="Kriteria"){
                include "_Page/Kriteria/ModalKriteria.php";
            }else{
                if($Page=="Penilaian"){
                    include "_Page/Penilaian/ModalPenilaian.php";
                }else{
                    if($Page=="Laporan"){
                        include "_Page/Laporan/ModalLaporan.php";
                    }else{
                        if($Page=="MyProfile"){
                            include "_Page/MyProfile/ModalMyProfile.php";
                        }else{
                            if($Page=="PenilaianKaryawan"){
                                include "_Page/PenilaianKaryawan/ModalPenilaianKaryawan.php";
                            }else{
                            
                            }
                        }
                    }
                }
            }
        }
    }
?>