<?php
    if(empty($_GET['Sub'])){
        include "_Page/PenilaianKaryawan/PenilaianKaryawanHome.php";
    }else{
        $Sub=$_GET['Sub'];
        if($Sub=="DetailPenilaian"){
            include "_Page/PenilaianKaryawan/DetailPenilaian.php";
        }else{
            include "_Page/PenilaianKaryawan/PenilaianKaryawanHome.php";
        }
    }
?>