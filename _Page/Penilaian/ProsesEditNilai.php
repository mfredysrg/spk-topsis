<?php
    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    //Time Zone
    date_default_timezone_set('Asia/Jakarta');
    //Time Now Tmp
    $now=date('Y-m-d H:i:s');
    if(empty($_POST['id_periode_penilaian'])){
        echo '<small class="text-danger">ID Periode Penilaian tidak boleh kosong</small>';
    }else{
        //Validasi id_karyawan tidak boleh kosong
        if(empty($_POST['id_karyawan'])){
            echo '<small class="text-danger">ID Karyawan tidak boleh kosong</small>';
        }else{
            //Variabel
            $id_periode_penilaian=$_POST['id_periode_penilaian'];
            $id_karyawan=$_POST['id_karyawan'];
            //Buka nama karyawan
            $QryDetailKaryawan = mysqli_query($Conn,"SELECT * FROM karyawan WHERE id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
            $DataKaryawan = mysqli_fetch_array($QryDetailKaryawan);
            $id_akses= $DataKaryawan['id_akses'];
            $nama= $DataKaryawan['nama'];
            $query = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY kode_kriteria ASC");
            while ($data = mysqli_fetch_array($query)) {
                $id_kriteria= $data['id_kriteria'];
                $kriteria= $data['kriteria'];
                //Cek apakah data sudah ada
                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_karyawan='$id_karyawan' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                $DataNilai = mysqli_fetch_array($QryNilai);
                if(empty($DataNilai['id_nilai'])){
                    //Apabila data belum ada maka tambahkan
                    //menangkap nilai kriteria
                    if(!empty($_POST['NilaiKriteria'.$id_kriteria.''])){
                        $NilaiKriteria=$_POST['NilaiKriteria'.$id_kriteria.''];
                        $entry="INSERT INTO nilai (
                            id_periode_penilaian,
                            id_karyawan,
                            id_kriteria,
                            nama,
                            kriteria,
                            nilai
                        ) VALUES (
                            '$id_periode_penilaian',
                            '$id_karyawan',
                            '$id_kriteria',
                            '$nama',
                            '$kriteria',
                            '$NilaiKriteria'
                        )";
                        $Input=mysqli_query($Conn, $entry);
                    }
                }else{
                    //Apabila data sudah ada maka update
                    $id_nilai = $DataNilai['id_nilai'];
                    if(!empty($_POST['NilaiKriteria'.$id_kriteria.''])){
                        $NilaiKriteria=$_POST['NilaiKriteria'.$id_kriteria.''];
                        $UpdateNilai = mysqli_query($Conn,"UPDATE nilai SET 
                            nama='$nama',
                            kriteria='$kriteria',
                            nilai='$NilaiKriteria'
                        WHERE id_nilai='$id_nilai'") or die(mysqli_error($Conn)); 
                    }
                }
            }
            $_SESSION ["NotifikasiSwal"]="Proses Penilaian Berhasil";
            echo '<small class="text-success" id="NotifikasiEditNilaiBerhasil">Success</small>';
        }
    }
?>