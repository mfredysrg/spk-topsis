<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    if(empty($_POST['id_kriteria'])){
        echo '<small class="text-danger">ID Kriteria tidak boleh kosong</small>';
    }else{
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
                        $id_kriteria=$_POST['id_kriteria'];
                        $kode_kriteria=$_POST['kode_kriteria'];
                        $kriteria=$_POST['kriteria'];
                        $atribut=$_POST['atribut'];
                        $bobot=$_POST['bobot'];
                        //Buka kriteria lama
                        $QryKriteria = mysqli_query($Conn,"SELECT * FROM kriteria WHERE id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                        $DataKriteria = mysqli_fetch_array($QryKriteria);
                        $kode_kriteria_lama= $DataKriteria['kode_kriteria'];
                        if($kode_kriteria_lama==$kode_kriteria){
                            $ValidasiDuplikat=0;
                        }else{
                            $ValidasiDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM kriteria WHERE kode_kriteria='$kode_kriteria'"));
                        }
                        
                        if(!empty($ValidasiDuplikat)){
                            echo '<small class="text-danger">Kode Kriteria tersebut sudah terdaftar</small>';
                        }else{   
                            $UpdateKriteria = mysqli_query($Conn,"UPDATE kriteria SET 
                                kode_kriteria='$kode_kriteria',
                                kriteria='$kriteria',
                                atribut='$atribut',
                                bobot='$bobot'
                            WHERE id_kriteria='$id_kriteria'") or die(mysqli_error($Conn)); 
                            if($UpdateKriteria){
                                echo '<small class="text-success" id="NotifikasiEditKriteriaBerhasil">Success</small>';
                            }else{
                                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                            }
                        }
                    }
                }
            }
        }
    }
?>