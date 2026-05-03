<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    //Validasi kode_kriteria tidak boleh kosong
    if(empty($_POST['kode_kriteria'])){
        echo '<small class="text-danger">Kode Kriteria tidak boleh kosong</small>';
    }else{
        //Validasi kriteria tidak boleh kosong
        if(empty($_POST['kriteria'])){
            echo '<small class="text-danger">Kriteria tidak boleh kosong</small>';
        }else{
            //Validasi atribut tidak boleh kosong
            if(empty($_POST['atribut'])){
                echo '<small class="text-danger">Atribut tidak boleh kosong</small>';
            }else{
                //Validasi bobot tidak boleh kosong
                if(empty($_POST['bobot'])){
                    echo '<small class="text-danger">Bobot tidak boleh kosong</small>';
                }else{
                    //Variabel
                    $kode_kriteria=$_POST['kode_kriteria'];
                    $kriteria=$_POST['kriteria'];
                    $atribut=$_POST['atribut'];
                    $bobot=$_POST['bobot'];
                    $ValidasiDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM kriteria WHERE kode_kriteria='$kode_kriteria'"));
                    if(!empty($ValidasiDuplikat)){
                        echo '<small class="text-danger">Kode Kriteria tersebut sudah terdaftar</small>';
                    }else{   
                        $entry="INSERT INTO kriteria (
                            kode_kriteria,
                            kriteria,
                            atribut,
                            bobot
                        ) VALUES (
                            '$kode_kriteria',
                            '$kriteria',
                            '$atribut',
                            '$bobot'
                        )";
                        $Input=mysqli_query($Conn, $entry);
                        if($Input){
                            $_SESSION ["NotifikasiSwal"]="Tambah Kriteria Berhasil";
                            echo '<small class="text-success" id="NotifikasiTambahKriteriaBerhasil">Success</small>';
                        }else{
                            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                        }
                    }
                }
            }
        }
    }
?>