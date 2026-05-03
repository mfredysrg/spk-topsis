<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert"> 
                <small>
                    Halaman laporan digunakan oleh pimpinan untuk memperoleh data hasil perhitungan penilaian kinerja.
                    Pilih periode laporan dan<b>Tampilkan</b> untuk menampilkan data laporan penilaian, atau klik <b>Cetak</b> untuk melakukan percetakan. 
                </small>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <form action="_Page/Laporan/CetakLaporan.php" method="POST" target="_blan" id="ProsesTampilkanLaporan">
                        <div class="row">
                            <div class="col-md-4 mt-3">
                                <select name="id_periode_penilaian" id="id_periode_penilaian" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                        //menampilkan list periode penilaian
                                        $query = mysqli_query($Conn, "SELECT*FROM periode_penilaian WHERE status='Selesai'");
                                        while ($data = mysqli_fetch_array($query)) {
                                            $id_periode_penilaian= $data['id_periode_penilaian'];
                                            $tanggal= $data['tanggal'];
                                            $keterangan= $data['keterangan'];
                                            echo '<option value="'.$id_periode_penilaian.'">'.$tanggal.' ('.$keterangan.')</option>';
                                        }
                                    ?>
                                </select>
                                <small>Periode Laporan</small>
                            </div>
                            <div class="col-md-4 mt-3">
                                <select name="FormatCetak" id="FormatCetak" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="HTML">HTML</option>
                                    <option value="PDF">PDF</option>
                                </select>
                                <small>Format Cetak</small>
                            </div>
                            <div class="col-md-2 text-center mt-3">
                                <button type="button" class="btn btn-md btn-primary btn-block btn-rounded" id="TampilkanLaporan">
                                    <i class="bi bi-arrow-down-square"></i> Tampilkan
                                </button>
                            </div>
                            <div class="col-md-2 text-center mt-3">
                                <button type="submit" class="btn btn-md btn-info btn-block btn-rounded">
                                    <i class="bi bi-printer"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body" id="MenampilkanLaporan">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            Silahkan Pilih Periode Laporan Terlebih Dulu, Kemudian Tampilkan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>