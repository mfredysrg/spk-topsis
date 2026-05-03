<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set("Asia/Jakarta");
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    //Id Akses
    if(empty($_POST['id_akses'])){
        echo '<small class="text-danger">ID Akses Tidak Boleh Kosong</small>';
    }else{
        $id_akses=$_POST['id_akses'];
        //Buka data askes
        $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$id_akses'")or die(mysqli_error($Conn));
        $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
        $email_akses_lama = $DataDetailAkses['email'];
        //Validasi nama tidak boleh kosong
        if(empty($_POST['nama_akses'])){
            echo '<small class="text-danger">Nama Tidak Boleh Kosong</small>';
        }else{
            //Validasi email tidak boleh kosong
            if(empty($_POST['email_akses'])){
                echo '<small class="text-danger">Email Tidak Boleh Kosong</small>';
            }else{
                //Validasi email duplikat
                $email_akses=$_POST['email_akses'];
                if($email_akses_lama==$email_akses){
                    $ValidasiEmailDuplikat=0;
                }else{
                    $ValidasiEmailDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akses WHERE email='$email_akses'"));
                }
                if(!empty($ValidasiEmailDuplikat)){
                    echo '<small class="text-danger">Email Sudah Terdaftar</small>';
                }else{
                    //Variabel Lainnya
                    $nama_akses=$_POST['nama_akses'];
                    $email_akses=$_POST['email_akses'];
                    $UpdateAkses = mysqli_query($Conn,"UPDATE akses SET 
                        nama='$nama_akses',
                        email='$email_akses'
                    WHERE id_akses='$id_akses'") or die(mysqli_error($Conn)); 
                    if($UpdateAkses){
                        $_SESSION ["NotifikasiSwal"]="Edit Akses Berhasil";
                        echo '<small class="text-success" id="NotifikasiEditAksesBerhasil">Success</small>';
                    }else{
                        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data akses</small>';
                    }
                }
            }
        }
    }
?>