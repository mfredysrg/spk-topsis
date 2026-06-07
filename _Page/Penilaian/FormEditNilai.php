<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    
    //Tangkap id_periode_penilaian
    if(empty($_POST['id_periode_penilaian'])){
        echo '  <div class="row">';
        echo '      <div class="col-md-12 mb-3">';
        echo '          ID Periode Penilaian Tidak Ditemukan.';
        echo '      </div>';
        echo '  </div>';
    }else{
        // [REVISI] Mengubah tangkapan dari id_UMKM ke id_umkm
        if(empty($_POST['id_UMKM'])){ // Tetap pakai id_UMKM untuk menangkap POST dari JS agar tidak perlu ubah banyak di JS
            echo '  <div class="row">';
            echo '      <div class="col-md-12 mb-3">';
            echo '          ID UMKM Tidak Ditemukan.';
            echo '      </div>';
            echo '  </div>';
        }else{
            $id_periode_penilaian=$_POST['id_periode_penilaian'];
            // Variabel POST dari JS masih 'id_UMKM', kita ubah namanya menjadi id_umkm di PHP
            $id_umkm=$_POST['id_UMKM']; 
            
            //Buka data periode penilaian
            $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
            $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
            $id_periode_penilaian= $DataPeriodePenilaian['id_periode_penilaian'];
            $tanggal= $DataPeriodePenilaian['tanggal'];
            $keterangan= $DataPeriodePenilaian['keterangan'];
            $status= $DataPeriodePenilaian['status'];
            
            // [REVISI] Buka data UMKM
            $QryDetailUMKM = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'")or die(mysqli_error($Conn));
            $DataUMKM = mysqli_fetch_array($QryDetailUMKM);
            
            $id_akses= $DataUMKM['id_akses'];
            $nama_umkm = $DataUMKM['nama_umkm'];
            $kontak = $DataUMKM['kontak'];
            $nama_pemilik = $DataUMKM['nama_pemilik'];
            
            $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$id_akses'")or die(mysqli_error($Conn));
            $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
            // Cek jika DataDetailAkses tidak kosong sebelum panggil email
            $email = !empty($DataDetailAkses['email']) ? $DataDetailAkses['email'] : 'Tidak Ada Email';
?>
    <input type="hidden" name="id_periode_penilaian" id="id_periode_penilaian" value="<?php echo "$id_periode_penilaian"; ?>">
    <input type="hidden" name="id_UMKM" id="id_UMKM" value="<?php echo "$id_umkm"; ?>">
    
    <div class="row mb-3"> 
        <div class="col-md-12">
            <table class="">
                <tbody>
                    <tr>
                        <td>
                            <small><dt>Nama UMKM</dt></small>
                        </td>
                        <td><b>:</b></td>
                        <td>
                            <small><?php echo "$nama_umkm"; ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><dt>No. Kontak</dt></small>
                        </td>
                        <td><b>:</b></td>
                        <td>
                            <small><?php echo $kontak; ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><dt>Nama Pemilik</dt></small>
                        </td>
                        <td><b>:</b></td>
                        <td>
                            <small><?php echo "$nama_pemilik"; ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><dt>Email Akun</dt></small>
                        </td>
                        <td><b>:</b></td>
                        <td>
                            <small><?php echo "$email"; ?></small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php
        //Arraykan data kriteria berdasarkan id_periode_penilaian
        $no=1;
        $query = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
        while ($data = mysqli_fetch_array($query)) {
            $id_kriteria= $data['id_kriteria'];
            $kriteria= $data['kriteria'];
            
            // [REVISI] Buka nilai berdasarkan id_umkm
            $QryNilai = mysqli_query($Conn,"SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian' AND id_umkm='$id_umkm' AND id_kriteria='$id_kriteria'")or die(mysqli_error($Conn));
            $DataNilai = mysqli_fetch_array($QryNilai);
            
            if(empty($DataNilai['nilai'])){
                $nilai =0;
            }else{
                $nilai =$DataNilai['nilai'];
            }
            
            echo '<div class="row mb-3">';
            echo '  <div class="col-md-12">';
            echo '      <label for="NilaiKriteria'.$id_kriteria.'">'.$kriteria.'</label>';
            echo '      <select class="form-control" required name="NilaiKriteria'.$id_kriteria.'" id="NilaiKriteria'.$id_kriteria.'">';
            echo '          <option selected value="">Pilih</option>';
            
            $QryAlternatif = mysqli_query($Conn, "SELECT * FROM alternatif WHERE id_kriteria='$id_kriteria'");
            while ($DataAlternatif = mysqli_fetch_array($QryAlternatif)) {
                $Aternatif= $DataAlternatif['alternatif'];
                $NilaiAlterantif= $DataAlternatif['nilai'];
                if($NilaiAlterantif==$nilai){
                    echo '  <option selected value="'.$NilaiAlterantif.'">'.$Aternatif.'</option>';
                }else{
                    echo '  <option value="'.$NilaiAlterantif.'">'.$Aternatif.'</option>';
                }
            }
            
            echo '      </select>';
            echo '  </div>';
            echo '</div>';
            
        $no++;
        }
    ?>
    <div class="row">
        <div class="col-md-12 mt-3" id="NotifikasiEditNilai">
            <small class="text-primary">Pastikan data yang anda input sudah benar</small>
        </div>
    </div>
<?php }} ?>