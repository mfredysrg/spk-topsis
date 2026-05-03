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
                        //Validasi id_karyawan tidak boleh kosong
                        if(empty($_POST['id_karyawan'])){
                            echo '<small class="text-danger">ID Karyawan tidak boleh kosong</small>';
                        }else{
                            //Validasi id_akses tidak boleh kosong
                            if(empty($_POST['id_akses'])){
                                echo '<small class="text-danger">ID Akses tidak boleh kosong</small>';
                            }else{
                                //Variabel Lainnya
                                $id_karyawan=$_POST['id_karyawan'];
                                $id_akses=$_POST['id_akses'];
                                $nama=$_POST['nama'];
                                $email=$_POST['email'];
                                $nip=$_POST['nip'];
                                $jabatan=$_POST['jabatan'];
                                $kontak=$_POST['kontak'];
                                //Buka email dan nip lama
                                //Buka data karyawan
                                $QryDetailKaryawan = mysqli_query($Conn,"SELECT * FROM karyawan WHERE id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
                                $DataKaryawan = mysqli_fetch_array($QryDetailKaryawan);
                                $nip_lama= $DataKaryawan['nip'];
                                $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$id_akses'")or die(mysqli_error($Conn));
                                $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                $email_lama = $DataDetailAkses['email'];
                                if($nip==$nip_lama){
                                    $ValidasiNip=0;
                                }else{
                                    $ValidasiNip=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM karyawan WHERE nip='$nip'"));
                                }
                                if($email==$email_lama){
                                    $ValidasiDuplikat=0;
                                }else{
                                    $ValidasiDuplikat=mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akses WHERE email='$email'"));
                                }
                                if(!empty($ValidasiDuplikat)){
                                    echo '<small class="text-danger">Email tersebut sudah terdaftar</small>';
                                }else{   
                                    if(!empty($ValidasiNip)){
                                        echo '<small class="text-danger">NIP tersebut sudah terdaftar</small>';
                                    }else{   
                                        $UpdateAkses = mysqli_query($Conn,"UPDATE akses SET 
                                            nama='$nama',
                                            email='$email'
                                        WHERE id_akses='$id_akses'") or die(mysqli_error($Conn)); 
                                        if($UpdateAkses){
                                            $UpdateKaryawan = mysqli_query($Conn,"UPDATE karyawan SET 
                                                nama='$nama',
                                                kontak='$kontak',
                                                nip='$nip',
                                                jabatan='$jabatan'
                                            WHERE id_karyawan='$id_karyawan'") or die(mysqli_error($Conn)); 
                                            if($UpdateKaryawan){
                                                echo '<small class="text-success" id="NotifikasiEditKaryawanBerhasil">Success</small>';
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
?>