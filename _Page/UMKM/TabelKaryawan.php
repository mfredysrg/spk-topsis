<?php
    //koneksi dan session
    ini_set("display_errors", "off");
    include "../../_Config/Connection.php";

    //Keyword_by
    if(!empty($_POST['keyword_by'])){
        $keyword_by = $_POST['keyword_by'];
    }else{
        $keyword_by = "";
    }
    //keyword
    if(!empty($_POST['keyword'])){
        $keyword = $_POST['keyword'];
    }else{
        $keyword = "";
    }
    //batas
    if(!empty($_POST['batas'])){
        $batas = $_POST['batas'];
    }else{
        $batas = "10";
    }
    //ShortBy
    if(!empty($_POST['ShortBy'])){
        $ShortBy = $_POST['ShortBy'];
        if($ShortBy == "ASC"){
            $NextShort = "DESC";
        }else{
            $NextShort = "ASC";
        }
    }else{
        $ShortBy = "DESC";
        $NextShort = "ASC";
    }
    //OrderBy
    if(!empty($_POST['OrderBy'])){
        $OrderBy = $_POST['OrderBy'];
    }else{
        // [REVISI] Order by default menggunakan id_umkm
        $OrderBy = "id_umkm"; 
    }
    //Atur Page
    if(!empty($_POST['page'])){
        $page = $_POST['page'];
        $posisi = ( $page - 1 ) * $batas;
    }else{
        $page = "1";
        $posisi = 0;
    }

    // [REVISI] Query pencarian disesuaikan dengan tabel umkm
    if(empty($keyword_by)){
        if(empty($keyword)){
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM umkm"));
        }else{
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM umkm WHERE nama_umkm LIKE '%$keyword%' OR kontak LIKE '%$keyword%' OR nama_pemilik LIKE '%$keyword%'"));
        }
    }else{
        if(empty($keyword)){
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM umkm"));
        }else{
            $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM umkm WHERE ($keyword_by LIKE '%$keyword%')"));
        }
    }
?>
<script>
    //ketika klik next
    $('#NextPage').click(function() {
        var valueNext = $('#NextPage').val();
        var batas = "<?php echo $batas; ?>";
        var keyword = "<?php echo $keyword; ?>";
        var keyword_by = "<?php echo $keyword_by; ?>";
        var OrderBy = "<?php echo $OrderBy; ?>";
        var ShortBy = "<?php echo $ShortBy; ?>";
        $.ajax({
            url     : "_Page/Umkm/TabelKaryawan.php",
            method  : "POST",
            data    :  { page: valueNext, batas: batas, keyword: keyword, keyword_by: keyword_by, OrderBy: OrderBy, ShortBy: ShortBy },
            success: function (data) {
                $('#MenampilkanTabelKaryawan').html(data);
            }
        })
    });
    //Ketika klik Previous
    $('#PrevPage').click(function() {
        var ValuePrev = $('#PrevPage').val();
        var batas = "<?php echo $batas; ?>";
        var keyword = "<?php echo $keyword; ?>";
        var keyword_by = "<?php echo $keyword_by; ?>";
        var OrderBy = "<?php echo $OrderBy; ?>";
        var ShortBy = "<?php echo $ShortBy; ?>";
        $.ajax({
            url     : "_Page/Umkm/TabelKaryawan.php",
            method  : "POST",
            data    :  { page: ValuePrev, batas: batas, keyword: keyword, keyword_by: keyword_by, OrderBy: OrderBy, ShortBy: ShortBy },
            success : function (data) {
                $('#MenampilkanTabelKaryawan').html(data);
            }
        })
    });
    <?php 
        $JmlHalaman = ceil($jml_data / $batas); 
        for ( $i = 1; $i <= $JmlHalaman; $i++ ){
    ?>
        //ketika klik page number
        $('#PageNumber<?php echo $i;?>').click(function() {
            var PageNumber = $('#PageNumber<?php echo $i;?>').val();
            var batas = "<?php echo $batas; ?>";
            var keyword = "<?php echo $keyword; ?>";
            var keyword_by = "<?php echo $keyword_by; ?>";
            var OrderBy = "<?php echo $OrderBy; ?>";
            var ShortBy = "<?php echo $ShortBy; ?>";
            $.ajax({
                url     : "_Page/Umkm/TabelKaryawan.php",
                method  : "POST",
                data    :  { page: PageNumber, batas: batas, keyword: keyword, keyword_by: keyword_by, OrderBy: OrderBy, ShortBy: ShortBy },
                success: function (data) {
                    $('#MenampilkanTabelKaryawan').html(data);
                }
            })
        });
    <?php } ?>
</script>
<div class="card-body">
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center"><b>No</b></th>
                            <th class="text-center"><b>Nama UMKM</b></th>
                            <th class="text-center"><b>Nama Pemilik</b></th>
                            <th class="text-center"><b>Kontak</b></th>
                            <th class="text-center"><b>Email Akun</b></th>
                            <th class="text-center"><b>Option</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(empty($jml_data)){
                                echo '<tr>';
                                echo '  <td colspan="6" class="text-center text-danger">Tidak Ada Data UMKM Yang Ditampilkan</td>';
                                echo '</tr>';
                            }else{
                                $no = 1 + $posisi;
                                // [REVISI] Menjalankan Query berdasarkan tabel UMKM
                                if(empty($keyword_by)){
                                    if(empty($keyword)){
                                        $query = mysqli_query($Conn, "SELECT * FROM umkm ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                    }else{
                                        $query = mysqli_query($Conn, "SELECT * FROM umkm WHERE nama_umkm LIKE '%$keyword%' OR kontak LIKE '%$keyword%' OR nama_pemilik LIKE '%$keyword%' ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                    }
                                }else{
                                    if(empty($keyword)){
                                        $query = mysqli_query($Conn, "SELECT * FROM umkm ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                    }else{
                                        $query = mysqli_query($Conn, "SELECT * FROM umkm WHERE ($keyword_by LIKE '%$keyword%') ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas");
                                    }
                                }

                                while ($data = mysqli_fetch_array($query)) {
                                    $id_umkm = $data['id_umkm'];
                                    $id_akses = $data['id_akses'];
                                    $nama_umkm = $data['nama_umkm'];
                                    $nama_pemilik = $data['nama_pemilik'];
                                    $kontak = $data['kontak'];
                                    
                                    // Ambil email dari tabel akses
                                    $QryDetailAkses = mysqli_query($Conn,"SELECT * FROM akses WHERE id_akses='$id_akses'");
                                    $DataDetailAkses = mysqli_fetch_array($QryDetailAkses);
                                    $email_akses = !empty($DataDetailAkses['email']) ? $DataDetailAkses['email'] : '-';
                        ?>
                            <tr>
                                <td class="text-center text-xs">
                                    <?php echo $no; ?>
                                </td>
                                <td class="text-left" align="left">
                                    <a href="index.php?Page=Umkm&Sub=DetailKaryawan&id_karyawan=<?php echo $id_umkm; ?>">
                                        <b><?php echo $nama_umkm; ?></b>
                                    </a>
                                </td>
                                <td class="text-left" align="left">
                                    <?php echo $nama_pemilik;?>
                                </td>
                                <td class="text-left" align="left">
                                    <?php echo $kontak;?>
                                </td>
                                <td class="text-left" align="left">
                                    <small><?php echo $email_akses; ?></small>
                                </td>
                                <td align="center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditPassword" data-id="<?php echo "$id_umkm,$keyword,$batas,$ShortBy,$OrderBy,$page,$keyword_by"; ?>">
                                            <i class="bi bi-key"></i>
                                        </button>  
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditKaryawan" data-id="<?php echo "$id_umkm,$keyword,$batas,$ShortBy,$OrderBy,$page,$keyword_by"; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>  
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#ModalDeleteKaryawan" data-id="<?php echo "$id_umkm,$keyword,$batas,$ShortBy,$OrderBy,$page,$keyword_by"; ?>">
                                            <i class="bi bi-x"></i>
                                        </button>   
                                    </div>
                                </td>
                            </tr>
                        <?php
                            $no++; 
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card-footer text-center">
    <div class="btn-group shadow-0" role="group" aria-label="Basic example">
        <?php
            $JmlHalaman = empty($jml_data) ? 1 : ceil($jml_data / $batas); 
            $prev = $page - 1;
            $next = $page + 1;
            if($next > $JmlHalaman) $next = $page;
            if($prev < 1) $prev = 1;
        ?>
        <button class="btn btn-sm btn-outline-info" id="PrevPage" value="<?php echo $prev;?>">
            <span aria-hidden="true">«</span>
        </button>
        <?php 
            for ( $i = 1; $i <= $JmlHalaman; $i++ ){
                if($page == "$i"){
                    echo '<button class="btn btn-sm btn-info" id="PageNumber'.$i.'" value="'.$i.'"><span aria-hidden="true">'.$i.'</span></button>';
                }else{
                    echo '<button class="btn btn-sm btn-outline-info" id="PageNumber'.$i.'" value="'.$i.'"><span aria-hidden="true">'.$i.'</span></button>';
                }
            }
        ?>
        <button class="btn btn-sm btn-outline-info" id="NextPage" value="<?php echo $next;?>">
            <span aria-hidden="true">»</span>
        </button>
    </div>
</div>