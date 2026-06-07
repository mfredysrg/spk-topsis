<?php
    if(empty($_GET['Sub'])){
        include "_Page/PenilaianUMKM/PenilaianUMKMHome.php";
    }else{
        $Sub=$_GET['Sub'];
        if($Sub=="DetailPenilaian"){
            include "_Page/PenilaianUMKM/DetailPenilaian.php";
        }else{
            include "_Page/PenilaianUMKM/PenilaianUMKMHome.php";
        }
    }
?>