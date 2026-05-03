<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    //Validasi id_alternatif tidak boleh kosong
    if(empty($_POST['id_alternatif'])){
        echo '<small class="text-danger">ID Kriteria tidak boleh kosong</small>';
    }else{
        //Validasi alternatif tidak boleh kosong
        if(empty($_POST['alternatif'])){
            echo '<small class="text-danger">Alternatif tidak boleh kosong</small>';
        }else{
            //Validasi nilai tidak boleh kosong
            if(empty($_POST['nilai'])){
                echo '<small class="text-danger">Nilai tidak boleh kosong</small>';
            }else{
                //Validasi nilai tidak boleh kosong
                if(empty($_POST['nilai'])){
                    echo '<small class="text-danger">Nilai tidak boleh kosong</small>';
                }else{
                    //Variabel
                    $id_alternatif=$_POST['id_alternatif'];
                    $alternatif=$_POST['alternatif'];
                    $nilai=$_POST['nilai'];
                    $UpdateAlternatif = mysqli_query($Conn,"UPDATE alternatif SET 
                        alternatif='$alternatif',
                        nilai='$nilai'
                    WHERE id_alternatif='$id_alternatif'") or die(mysqli_error($Conn)); 
                    if($UpdateAlternatif){
                        $_SESSION ["NotifikasiSwal"]="Edit Alternatif Berhasil";
                        echo '<small class="text-success" id="NotifikasiEditAlternatifBerhasil">Success</small>';
                    }else{
                        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                    }
                }
            }
        }
    }
?>