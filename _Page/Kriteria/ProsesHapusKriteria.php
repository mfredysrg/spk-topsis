<?php
    //Connection
    include "../../_Config/Connection.php";
    if(empty($_POST['id_kriteria'])){
        echo '<span class="text-danger">ID Kriteria tidak dapat ditangkap oleh sistem</span>';
    }else{
        $id_kriteria=$_POST['id_kriteria'];
        //Proses hapus data
        $query = mysqli_query($Conn, "DELETE FROM kriteria WHERE id_kriteria='$id_kriteria'") or die(mysqli_error($Conn));
        if ($query) {
            echo '<span class="text-success" id="NotifikasiHapusKriteriaBerhasil">Success</span>';
        }else{
            echo '<span class="text-danger">Hapus Data Gagal</span>';
        }
    }
?>