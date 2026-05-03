<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-10">
                <b class="card-title">
                    <i class="bi bi-pencil-square"></i> Riwayat Penilaian
                </b>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mt-2"> 
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-items-center mb-0">
                        <thead class="">
                            <tr>
                                <th class="text-center">
                                    <b>No</b>
                                </th>
                                <th class="text-center">
                                    <b>Tanggal</b>
                                </th>
                                <th class="text-center">
                                    <b>Keterangan</b>
                                </th>
                                <th class="text-center">
                                    <b>Status</b>
                                </th>
                                <th class="text-center">
                                    <b>Option</b>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $QryNilai = mysqli_query($Conn, "SELECT DISTINCT id_periode_penilaian FROM nilai WHERE id_karyawan='$SessionIdKaryawan' ORDER BY id_periode_penilaian ASC");
                                while ($DataNilai = mysqli_fetch_array($QryNilai)) {
                                    $id_periode_penilaian= $DataNilai['id_periode_penilaian'];
                                    //Buka detail periode_penilaian
                                    $QryPenilaian = mysqli_query($Conn,"SELECT * FROM periode_penilaian WHERE id_periode_penilaian='$id_periode_penilaian'")or die(mysqli_error($Conn));
                                    $DataPenilaian = mysqli_fetch_array($QryPenilaian);
                                    $tanggal = $DataPenilaian['tanggal'];
                                    $keterangan = $DataPenilaian['keterangan'];
                                    $status = $DataPenilaian['status'];
                            ?>
                                <tr>
                                    <td class="text-center text-xs">
                                        <?php echo "$no" ?>
                                    </td>
                                    <td class="text-left text-xs">
                                        <?php echo "$tanggal" ?>
                                    </td>
                                    <td class="text-left text-xs">
                                        <?php echo "$keterangan" ?>
                                    </td>
                                    <td class="text-left text-xs">
                                        <?php echo "$status" ?>
                                    </td>
                                    <td align="center">
                                        <div class="btn-group">
                                            <?php if($status=="Selesai"){ ?>
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalDetailPenilaianKaryawan" data-id="<?php echo "$id_periode_penilaian,$SessionIdKaryawan"; ?>">
                                                    <i class="bi bi-list-check"></i> Nilai
                                                </button>  
                                                <a href="index.php?Page=PenilaianKaryawan&Sub=DetailPenilaian&id_periode_penilaian=<?php echo "$id_periode_penilaian"; ?>" class="btn btn-primary btn-sm" data-id="<?php echo "$id_periode_penilaian,$SessionIdKaryawan"; ?>">
                                                    <i class="bi bi-table"></i> Uraian
                                                </a>  
                                            <?php }else{ ?>
                                                <button type="button" disabled class="btn btn-success btn-sm">
                                                    <i class="bi bi-list-check"></i> Nilai
                                                </button>  
                                                <button type="button" disabled class="btn btn-primary btn-sm">
                                                    <i class="bi bi-table"></i> Uraian
                                                </button>  
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                $no++; }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
