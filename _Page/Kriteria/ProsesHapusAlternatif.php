<?php
    //Connection
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    if(empty($_POST['id_alternatif'])){
        echo '<span class="text-danger">ID Kriteria tidak dapat ditangkap oleh sistem</span>';
    }else{
        $id_alternatif=$_POST['id_alternatif'];
        //Proses hapus data
        $query = mysqli_query($Conn, "DELETE FROM alternatif WHERE id_alternatif='$id_alternatif'") or die(mysqli_error($Conn));
        if ($query) {
            $_SESSION ["NotifikasiSwal"]="Hapus Alternatif Berhasil";
            echo '<span class="text-success" id="NotifikasiHapusAlternatifBerhasil">Success</span>';
        }else{
            echo '<span class="text-danger">Hapus Data Gagal</span>';
        }
    }
?>