<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    //Validasi nama tidak boleh kosong
    if(empty($_POST['nama'])){
        echo '<small class="text-danger">Nama tidak boleh kosong</small>';
    }else{
        //Validasi kontak tidak boleh kosong
        if(empty($_POST['email'])){
            echo '<small class="text-danger">Kontak tidak boleh kosong</small>';
        }else{
            //Validasi akses tidak boleh kosong
            if(empty($_POST['akses'])){
                echo '<small class="text-danger">Akses tidak boleh kosong</small>';
            }else{
                //Validasi password1 tidak boleh kosong
                if(empty($_POST['password1'])){
                    echo '<small class="text-danger">Password tidak boleh kosong</small>';
                }else{
                    if($_POST['password1']!==$_POST['password2']){
                        echo '<small class="text-danger">Password Tidak sama</small>';
                    }else{
                        //Validasi jumlah dan jenis karakter password
                        $JumlahKarakterPassword=strlen($_POST['password1']);
                        if($JumlahKarakterPassword>20||$JumlahKarakterPassword<6||!preg_match("/^[a-zA-Z0-9]*$/", $_POST['password1'])){
                            echo '<small class="text-danger">Password can only have 6-20 numeric characters</small>';
                        }else{  
                            //Variabel Lainnya
                            $nama=$_POST['nama'];
                            $email=$_POST['email'];
                            $akses=$_POST['akses'];
                            $password1=$_POST['password1'];
                            $password1=$_POST['password1'];
                            $password1=MD5($password1);
                            $ValidasiDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akses WHERE email='$email'"));
                            if(!empty($ValidasiDuplikat)){
                                echo '<small class="text-danger">Email tersebut sudah terdaftar</small>';
                            }else{   
                                $entry="INSERT INTO akses (
                                    nama,
                                    email,
                                    password,
                                    akses
                                ) VALUES (
                                    '$nama',
                                    '$email',
                                    '$password1',
                                    '$akses'
                                )";
                                $Input=mysqli_query($Conn, $entry);
                                if($Input){
                                    $_SESSION ["NotifikasiSwal"]="Tambah Akses Berhasil";
                                    echo '<small class="text-success" id="NotifikasiTambahAksesBerhasil">Success</small>';
                                }else{
                                    echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                                }
                            }
                        }
                    }
                }
            }
        }
    }
?>