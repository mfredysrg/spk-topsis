<?php
    //Connection
    include "../../_Config/Connection.php";
    if(empty($_POST['id_periode_penilaian'])){
        echo '<span class="text-danger">ID Kriteria tidak dapat ditangkap oleh sistem</span>';
    }else{
        $id_periode_penilaian=$_POST['id_periode_penilaian'];
        //Proses hapus data
        $query = mysqli_query($Conn, "DELETE FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'") or die(mysqli_error($Conn));
        if ($query) {
            echo '<span class="text-success" id="NotifikasiHapusPenilaianBerhasil">Success</span>';
        }else{
            echo '<span class="text-danger">Hapus Data Gagal</span>';
        }
    }
?>