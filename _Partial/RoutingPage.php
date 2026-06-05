<?php
    if(empty($_GET['Page'])){
        include "_Page/Dashboard/Dashboard.php";
    }else{
        $Page=$_GET['Page'];
        if($Page=="Akses"){
            include "_Page/Akses/Akses.php";
        }else{
            // RUTE MENU UMKM
            if($Page=="UMKM"){
                include "_Page/UMKM/UMKM.php";
            }else{
                if($Page=="Kriteria"){
                    include "_Page/Kriteria/Kriteria.php";
                }else{
                    if($Page=="Penilaian"){
                        include "_Page/Penilaian/Penilaian.php";
                    }else{
                        if($Page=="Laporan"){
                            include "_Page/Laporan/Laporan.php";
                        }else{
                            if($Page=="MyProfile"){
                                include "_Page/MyProfile/MyProfile.php";
                            }else{
                                if($Page=="PenilaianKaryawan"){
                                    include "_Page/PenilaianKaryawan/PenilaianKaryawan.php";
                                }else{
                                    if($Page=="Help"){
                                        include "_Page/Help/Help.php";
                                    }else{
                                        // JIKA RUTE TIDAK DIKENALI, KEMBALI KE DASHBOARD
                                        include "_Page/Dashboard/Dashboard.php";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
?>