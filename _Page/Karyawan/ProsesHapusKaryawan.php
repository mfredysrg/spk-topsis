<?php
    //Connection
    include "../../_Config/Connection.php";
    if(empty($_POST['id_karyawan'])){
        echo '<span class="text-danger">ID Karyawan tidak dapat ditangkap oleh sistem</span>';
    }else{
        $id_karyawan=$_POST['id_karyawan'];
        $QryDetailKaryawan = mysqli_query($Conn,"SELECT * FROM karyawan WHERE id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
        $DataKaryawan = mysqli_fetch_array($QryDetailKaryawan);
        $id_akses= $DataKaryawan['id_akses'];
        //Proses hapus data
        $query = mysqli_query($Conn, "DELETE FROM akses WHERE id_akses='$id_akses'") or die(mysqli_error($Conn));
        if ($query) {
            $query2 = mysqli_query($Conn, "DELETE FROM karyawan WHERE id_karyawan='$id_karyawan'") or die(mysqli_error($Conn));
            if ($query2) {
                echo '<span class="text-success" id="NotifikasiHapusKaryawanBerhasil">Success</span>';
            }else{
                echo '<span class="text-danger">Hapus Data Gagal</span>';
            }
        }else{
            echo '<span class="text-danger">Hapus Data Gagal</span>';
        }
    }
?>