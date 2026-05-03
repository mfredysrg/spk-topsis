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
        //Validasi nip tidak boleh kosong
        if(empty($_POST['nip'])){
            echo '<small class="text-danger">NIP tidak boleh kosong</small>';
        }else{
            //Validasi kontak tidak boleh kosong
            if(empty($_POST['kontak'])){
                echo '<small class="text-danger">Kontak tidak boleh kosong</small>';
            }else{
                //Validasi kontak tidak boleh kosong
                if(empty($_POST['email'])){
                    echo '<small class="text-danger">Kontak tidak boleh kosong</small>';
                }else{
                    //Validasi jabatan tidak boleh kosong
                    if(empty($_POST['jabatan'])){
                        echo '<small class="text-danger">Jabatan tidak boleh kosong</small>';
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
                                    $nip=$_POST['nip'];
                                    $jabatan=$_POST['jabatan'];
                                    $kontak=$_POST['kontak'];
                                    $password1=$_POST['password1'];
                                    $password1=$_POST['password1'];
                                    $password1=MD5($password1);
                                    $ValidasiNip=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM karyawan WHERE nip='$nip'"));
                                    $ValidasiDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akses WHERE email='$email'"));
                                    if(!empty($ValidasiDuplikat)){
                                        echo '<small class="text-danger">Email tersebut sudah terdaftar</small>';
                                    }else{   
                                        if(!empty($ValidasiNip)){
                                            echo '<small class="text-danger">NIP tersebut sudah terdaftar</small>';
                                        }else{   
                                            //Membuat ID Akses
                                            $QryMaks=mysqli_query($Conn, "SELECT max(id_akses) as id_akses FROM akses")or die(mysqli_error($Conn));
                                            while($HasilNilai=mysqli_fetch_array($QryMaks)){
                                                $id_akses_maks=$HasilNilai['id_akses'];
                                            }
                                            $id_akses=$id_akses_maks+1;
                                            $entry="INSERT INTO akses (
                                                id_akses,
                                                nama,
                                                email,
                                                password,
                                                akses
                                            ) VALUES (
                                                '$id_akses',
                                                '$nama',
                                                '$email',
                                                '$password1',
                                                'Karyawan'
                                            )";
                                            $Input=mysqli_query($Conn, $entry);
                                            if($Input){
                                                $entry="INSERT INTO karyawan (
                                                    id_akses,
                                                    nama,
                                                    nip,
                                                    kontak,
                                                    jabatan
                                                ) VALUES (
                                                    '$id_akses',
                                                    '$nama',
                                                    '$nip',
                                                    '$kontak',
                                                    '$jabatan'
                                                )";
                                                $Input=mysqli_query($Conn, $entry);
                                                if($Input){
                                                    $_SESSION ["NotifikasiSwal"]="Tambah Karyawan Berhasil";
                                                    echo '<small class="text-success" id="NotifikasiTambahKaryawanBerhasil">Success</small>';
                                                }else{
                                                    echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                                                }
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
            }
        }
    }
?>