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
        //Validasi id_UMKM (yang dikirim dari form JS) tidak boleh kosong
        if(empty($_POST['id_UMKM'])){
            echo '<small class="text-danger">ID UMKM tidak boleh kosong</small>';
        }else{
            //Variabel
            $id_periode_penilaian=$_POST['id_periode_penilaian'];
            
            // Variabel POST dari form tetap menggunakan 'id_UMKM', namun nilainya adalah ID UMKM
            $id_umkm=$_POST['id_UMKM']; 
            
            // [REVISI] Buka data dari tabel UMKM
            $QryDetailUMKM = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
            $DataUMKM = mysqli_fetch_array($QryDetailUMKM);
            
            $id_akses= $DataUMKM['id_akses'];
            $nama_umkm= $DataUMKM['nama_umkm'];
            
            $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
            while ($data = mysqli_fetch_array($query)) {
                $id_kriteria= $data['id_kriteria'];
                $kriteria= $data['kriteria'];
                
                // [REVISI] Cek apakah data nilai sudah ada (berdasarkan id_umkm)
                $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
                $DataNilai = mysqli_fetch_array($QryNilai);
                
                if(empty($DataNilai['id_nilai'])){
                    //Apabila data belum ada maka tambahkan
                    //menangkap nilai kriteria
                    if(!empty($_POST['NilaiKriteria'.$id_kriteria.''])){
                        $NilaiKriteria=$_POST['NilaiKriteria'.$id_kriteria.''];
                        
                        // [REVISI] Query Insert ke tabel nilai disesuaikan ke struktur UMKM
                        $entry="INSERT INTO nilai (
                            id_periode_penilaian,
                            id_umkm,
                            id_kriteria,
                            nama,
                            kriteria,
                            nilai
                        ) VALUES (
                            '$id_periode_penilaian',
                            '$id_umkm',
                            '$id_kriteria',
                            '$nama_umkm',
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
                        
                        // [REVISI] Update tabel nilai
                        $UpdateNilai = mysqli_query($Conn,"UPDATE nilai SET 
                            nama='$nama_umkm',
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