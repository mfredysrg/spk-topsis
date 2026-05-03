<?php
    //Menangkap seasson kemudian menampilkannya
    session_start();
    if(empty($_SESSION["id_akses"])){
        header("Location:Login.php");
    }else{
        $SessionIdAkses=$_SESSION ["id_akses"];
        //Inisiasi data akses dari database
        $QuerySessionAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$SessionIdAkses'")or die(mysqli_error($Conn));
        $DataSessionAkses = mysqli_fetch_array($QuerySessionAkses);
        //Apabila data akses ada
        if(!empty($DataSessionAkses['id_akses'])){
            $SessionNama= $DataSessionAkses['nama'];
            $SessionEmail= $DataSessionAkses['email'];
            $SessionAkses= $DataSessionAkses['akses'];
            //Buka data karyawan apabila karyawan
            $QrySessionKaryawan = mysqli_query($Conn,"SELECT * FROM karyawan WHERE id_akses='$SessionIdAkses'")or die(mysqli_error($Conn));
            $DataSessionKaryawan = mysqli_fetch_array($QrySessionKaryawan);
            if(!empty($DataSessionKaryawan['id_karyawan'])){
                $SessionIdKaryawan= $DataSessionKaryawan['id_karyawan'];
                $SessionNip= $DataSessionKaryawan['nip'];
                $SesonKontak= $DataSessionKaryawan['kontak'];
                $SessionJabatan= $DataSessionKaryawan['jabatan'];
            }else{
                $SessionIdKaryawan="";
                $SessionNip="";
                $SesonKontak="";
                $SessionJabatan="";
            }
        }else{
            header("Location:Login.php");
        }
    }
?>
