<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    //Validasi tanggal tidak boleh kosong
    if(empty($_POST['tanggal'])){
        echo '<small class="text-danger">Tanggal Penilaian tidak boleh kosong</small>';
    }else{
        //Validasi keterangan tidak boleh kosong
        if(empty($_POST['keterangan'])){
            echo '<small class="text-danger">Keterangan tidak boleh kosong</small>';
        }else{
            //Validasi status tidak boleh kosong
            if(empty($_POST['status'])){
                echo '<small class="text-danger">Status tidak boleh kosong</small>';
            }else{
                //Variabel
                $tanggal=$_POST['tanggal'];
                $keterangan=$_POST['keterangan'];
                $status=$_POST['status'];
                $ValidasiDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM periode_penilaian WHERE tanggal='$tanggal'"));
                if(!empty($ValidasiDuplikat)){
                    echo '<small class="text-danger">Periode Penilaian tersebut sudah terdaftar</small>';
                }else{   
                    $entry="INSERT INTO periode_penilaian (
                        tanggal,
                        keterangan,
                        status
                    ) VALUES (
                        '$tanggal',
                        '$keterangan',
                        '$status'
                    )";
                    $Input=mysqli_query($Conn, $entry);
                    if($Input){
                        $_SESSION ["NotifikasiSwal"]="Tambah Penilaian Berhasil";
                        echo '<small class="text-success" id="NotifikasiTambahPenilaianBerhasil">Success</small>';
                    }else{
                        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                    }
                }
            }
        }
    }
?>