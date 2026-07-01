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
        echo '<span class="text-danger">Kode Kriteria tidak boleh kosong</span>';
    }else{
        //Validasi kriteria tidak boleh kosong
        if(empty($_POST['kriteria'])){
            echo '<span class="text-danger">Kriteria tidak boleh kosong</span>';
        }else{
            //Validasi atribut tidak boleh kosong
            if(empty($_POST['atribut'])){
                echo '<span class="text-danger">Atribut tidak boleh kosong</span>';
            }else{
                //Validasi bobot_anp tidak boleh kosong
                if($_POST['bobot_anp'] == ""){
                    echo '<span class="text-danger">Bobot ANP tidak boleh kosong</span>';
                }else{
                    //Validasi bobot_swara tidak boleh kosong
                    if($_POST['bobot_swara'] == ""){
                        echo '<span class="text-danger">Bobot SWARA tidak boleh kosong</span>';
                    }else{
                        //Variabel
                        $kode_kriteria=$_POST['kode_kriteria'];
                        $kriteria=$_POST['kriteria'];
                        $atribut=$_POST['atribut'];
                        $bobot_anp=$_POST['bobot_anp'];
                        $bobot_swara=$_POST['bobot_swara'];
                        
                        $ValidasiDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM kriteria WHERE kode_kriteria='$kode_kriteria'"));
                        if(!empty($ValidasiDuplikat)){
                            echo '<span class="text-danger">Kode Kriteria tersebut sudah terdaftar</span>';
                        }else{   
                            $entry="INSERT INTO kriteria (
                                kode_kriteria,
                                kriteria,
                                atribut,
                                bobot_anp,
                                bobot_swara
                            ) VALUES (
                                '$kode_kriteria',
                                '$kriteria',
                                '$atribut',
                                '$bobot_anp',
                                '$bobot_swara'
                            )";
                            
                            $Input=mysqli_query($Conn, $entry);
                            if($Input){
                                $_SESSION["NotifikasiSwal"]="Tambah Kriteria Berhasil";
                                echo '<span class="text-success" id="NotifikasiTambahKriteriaBerhasil">Success</span>';
                            }else{
                                // Menampilkan pesan error database jika ada yang salah di MySQL
                                echo '<span class="text-danger">Gagal menyimpan ke database: '.mysqli_error($Conn).'</span>';
                            }
                        }
                    }
                }
            }
        }
    }
?>