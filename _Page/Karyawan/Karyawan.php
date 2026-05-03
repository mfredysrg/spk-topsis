<?php
    if(empty($_GET['Sub'])){
        include "_Page/Karyawan/KaryawanHome.php";
    }else{
        $Sub=$_GET['Sub'];
        if($Sub=="DetailKaryawan"){
            include "_Page/Karyawan/DetailKaryawan.php";
        }else{
            include "_Page/Karyawan/KaryawanHome.php";
        }
    }
?>