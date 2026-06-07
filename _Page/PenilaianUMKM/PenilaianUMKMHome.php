<?php
    // Menghitung jumlah kriteria untuk keperluan tabel nantinya
    $JumlahKriteria = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM kriteria"));
?>
<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert"> 
                <small>
                    Halaman ini menampilkan daftar <b>UMKM</b> yang terdaftar dalam sistem. 
                    Anda bisa melihat detail penilaian atau menambah data UMKM baru melalui tombol yang tersedia.
                </small>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <form action="javascript:void(0);" id="ProsesBatas">
                        <div class="row">
                            <div class="col-md-2 mt-3">
                                <select name="batas" id="batas" class="form-control">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                <small>Data Per Halaman</small>
                            </div>
                            <div class="col-md-3 mt-3">
                                <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Cari UMKM...">
                                <small>Pencarian Nama UMKM</small>
                            </div>
                            <div class="col-md-2 mt-3">
                                <button type="submit" class="btn btn-md btn-dark btn-block btn-rounded">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                            <div class="col-md-5 text-end mt-3">
                                <button type="button" class="btn btn-md btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#ModalTambahUMKM">
                                    <i class="bi bi-plus-circle"></i> Tambah UMKM
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama UMKM</th>
                                    <th>Pemilik</th>
                                    <th>Kontak</th>
                                    <th class="text-center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    // [PERBAIKAN] Mengambil data dari tabel umkm, bukan kriteria
                                    $QryUMKM = mysqli_query($Conn, "SELECT * FROM umkm ORDER BY nama_umkm ASC");
                                    $CekData = mysqli_num_rows($QryUMKM);

                                    if($CekData > 0){
                                        while ($Data = mysqli_fetch_array($QryUMKM)) {
                                            $id_umkm = $Data['id_umkm'];
                                            $nama_umkm = $Data['nama_umkm'];
                                            $nama_pemilik = $Data['nama_pemilik'];
                                            $kontak = $Data['kontak'];
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td><b><?php echo $nama_umkm; ?></b></td>
                                        <td><?php echo $nama_pemilik; ?></td>
                                        <td><?php echo $kontak; ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" title="Detail" data-bs-toggle="modal" data-bs-target="#ModalDetailUMKM" data-id="<?php echo $id_umkm; ?>">
                                                <i class="bi bi-info-circle"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" title="Edit" data-bs-toggle="modal" data-bs-target="#ModalEditUMKM" data-id="<?php echo $id_umkm; ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#ModalHapusUMKM" data-id="<?php echo $id_umkm; ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php 
                                        } 
                                    } else {
                                        echo '<tr><td colspan="5" class="text-center">Belum ada data UMKM.</td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>