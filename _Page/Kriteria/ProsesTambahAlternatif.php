<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    //Validasi id_kriteria tidak boleh kosong
    if(empty($_POST['id_kriteria'])){
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
                    $id_kriteria=$_POST['id_kriteria'];
                    $alternatif=$_POST['alternatif'];
                    $nilai=$_POST['nilai'];
                    $ValidasiDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM alternatif WHERE id_kriteria='$id_kriteria' AND alternatif='$alternatif' AND nilai='$nilai'"));
                    if(!empty($ValidasiDuplikat)){
                        echo '<small class="text-danger">Data Altenatif tersebut sudah terdaftar</small>';
                    }else{   
                        $entry="INSERT INTO alternatif (
                            id_kriteria,
                            alternatif,
                            nilai
                        ) VALUES (
                            '$id_kriteria',
                            '$alternatif',
                            '$nilai'
                        )";
                        $Input=mysqli_query($Conn, $entry);
                        if($Input){
                            $_SESSION ["NotifikasiSwal"]="Tambah Alternatif Berhasil";
                            echo '<small class="text-success" id="NotifikasiTambahAlternatifBerhasil">Success</small>';
                        }else{
                            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                        }
                    }
                }
            }
        }
    }
?>