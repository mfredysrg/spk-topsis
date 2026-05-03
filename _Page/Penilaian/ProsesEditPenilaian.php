<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    if(empty($_POST['id_periode_penilaian'])){
        echo '<small class="text-danger">ID Periode Penilaian tidak boleh kosong</small>';
    }else{
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
                    $id_periode_penilaian=$_POST['id_periode_penilaian'];
                    $tanggal=$_POST['tanggal'];
                    $keterangan=$_POST['keterangan'];
                    $status=$_POST['status'];
                    //Buka Periode penilaian lama
                    $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
                    $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
                    $tanggal_lama= $DataPeriodePenilaian['tanggal'];
                    if($tanggal==$tanggal_lama){
                        $ValidasiDuplikat=0;
                    }else{
                        $ValidasiDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM periode_penilaian WHERE tanggal='$tanggal'"));
                    }
                    if(!empty($ValidasiDuplikat)){
                        echo '<small class="text-danger">Periode Penilaian tersebut sudah ada</small>';
                    }else{   
                        $UpdatePenilaian = mysqli_query($Conn,"UPDATE periode_penilaian SET 
                            tanggal='$tanggal',
                            keterangan='$keterangan',
                            status='$status'
                        WHERE id_periode_penilaian='$id_periode_penilaian'") or die(mysqli_error($Conn)); 
                        if($UpdatePenilaian){
                            echo '<small class="text-success" id="NotifikasiEditPenilaianBerhasil">Success</small>';
                        }else{
                            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                        }
                    }
                }
            }
        }
    }
?>