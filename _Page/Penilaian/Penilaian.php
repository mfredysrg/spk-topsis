<?php
    if(empty($_GET['Sub'])){
        include "_Page/Penilaian/PenilaianHome.php";
    }else{
        $Sub=$_GET['Sub'];
        if($Sub=="DetailPenilaian"){
            include "_Page/Penilaian/DetailPenilaian.php";
        }else{
            include "_Page/Penilaian/PenilaianHome.php";
        }
    }
?>