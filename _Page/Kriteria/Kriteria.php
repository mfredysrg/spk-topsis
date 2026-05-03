<?php
    if(empty($_GET['Sub'])){
        include "_Page/Kriteria/KriteriaHome.php";
    }else{
        $Sub=$_GET['Sub'];
        if($Sub=="DetailKriteria"){
            include "_Page/Kriteria/DetailKriteria.php";
        }else{
            include "_Page/Kriteria/KriteriaHome.php";
        }
    }
?>