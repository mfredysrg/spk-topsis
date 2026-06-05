<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    if(empty($_POST['id_akses'])){
        echo '<small class="text-danger">ID Akses tidak boleh kosong</small>';
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
                    $id_akses=$_POST['id_akses'];
                    $password1=$_POST['password1'];
                    $password2=$_POST['password2'];
                    $password1=MD5($password1);
                    $UpdateAkses = mysqli_query($Conn,"UPDATE akses SET 
                        password='$password1'
                    WHERE id_akses='$id_akses'") or die(mysqli_error($Conn)); 
                    if($UpdateAkses){
                        echo '<small class="text-success" id="NotifikasiEditPasswordBerhasil">Success</small>';
                    }else{
                        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                    }
                }
            }
        }
    }
?>