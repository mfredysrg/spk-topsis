<?php
    $JumlahAkses = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM akses"));
    
    // Mengambil dari tabel umkm
    $JumlahUMKM = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM umkm"));
    
    $JumlahKriteria = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM kriteria"));
    $JumlahPeriodePenilaian = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM periode_penilaian"));
    $JumlahNilai = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM nilai"));
    
?>
<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">UMKM<span></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-shop"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $JumlahUMKM ;?></h6>
                                    <span class="text-muted small pt-2 ps-1">Unit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Kriteria <span></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-list-check"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $JumlahKriteria;?></h6>
                                    <span class="text-muted small pt-2 ps-1">Data</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Periode Penilaian</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-hammer"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $JumlahPeriodePenilaian;?></h6>
                                    <span class="text-muted small pt-2 ps-1">Periode</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Penilaian <span></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-shield-plus"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $JumlahNilai;?></h6>
                                    <span class="text-muted small pt-2 ps-1">Record</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Rata-Rata Nilai</h5>
                            <div id="reportsChart"></div>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#reportsChart"), {
                                    series: [{
                                        name: 'Penilaian',
                                        data: [
                                            <?php
                                                $QryPeriodePenilaian = mysqli_query($Conn, "SELECT * FROM periode_penilaian ORDER BY id_periode_penilaian ASC");
                                                while ($DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian)) {
                                                    $id_periode_penilaian= $DataPeriodePenilaian['id_periode_penilaian'];
                                                    $TanggalPenilaian= $DataPeriodePenilaian['tanggal'];
                                                    
                                                    $Sum = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(nilai) AS jumlah FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian'"));
                                                    $JumlahSkorNilai = $Sum['jumlah'];
                                                    $JumlahPeserta = mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian'"));
                                                    
                                                    if(empty($JumlahPeserta)){
                                                        $RataRata=0;
                                                    }else{
                                                        $RataRata=$JumlahSkorNilai/$JumlahPeserta;
                                                    }
                                                    
                                                    echo ''.$RataRata.', ';
                                                }
                                            ?>
                                        ],
                                    }], 
                                    chart: {
                                    height: 350,
                                    type: 'area',
                                    toolbar: {
                                        show: false
                                    },
                                    },
                                    markers: {
                                    size: 4
                                    },
                                    colors: ['#4154f1', '#2eca6a', '#ff771d'],
                                    fill: {
                                    type: "gradient",
                                    gradient: {
                                        shadeIntensity: 1,
                                        opacityFrom: 0.3,
                                        opacityTo: 0.4,
                                        stops: [0, 90, 100]
                                    }
                                    },
                                    dataLabels: {
                                    enabled: false
                                    },
                                    stroke: {
                                    curve: 'smooth',
                                    width: 2
                                    },
                                    xaxis: {
                                    type: 'text',
                                    categories: [
                                        <?php
                                            $QryPeriodePenilaian = mysqli_query($Conn, "SELECT * FROM periode_penilaian ORDER BY id_periode_penilaian ASC");
                                            while ($DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian)) {
                                                $TanggalPenilaian= $DataPeriodePenilaian['tanggal'];
                                                $strtotime=strtotime($TanggalPenilaian);
                                                $TanggalPenilaian=date('d-m-Y',$strtotime);
                                                echo '"'.$TanggalPenilaian.'", ';
                                            }
                                        ?>
                                    ]
                                    },
                                    tooltip: {
                                    x: {
                                        format: 'd/m/y'
                                    },
                                    }
                                }).render();
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Periode Penilaian</h5>
                            <div class="activity">
                                <?php
                                    if(empty($JumlahPeriodePenilaian)){
                                        echo '<div class="activity-item d-flex">';
                                        echo '  Data Belum Tersedia';
                                        echo '</div>';
                                    }else{
                                        //Arraykan Data
                                        $no=1;
                                        $QryPeriodePenilaian= mysqli_query($Conn, "SELECT * FROM periode_penilaian ORDER BY id_periode_penilaian DESC LIMIT 5");
                                        while ($DataPenilaian = mysqli_fetch_array($QryPeriodePenilaian)) {
                                            $id_periode_penilaian= $DataPenilaian['id_periode_penilaian'];
                                            $tanggal= $DataPenilaian['tanggal'];
                                            $keterangan= $DataPenilaian['keterangan'];
                                            $status= $DataPenilaian['status'];
                                            echo '<div class="activity-item d-flex">';
                                            echo '  <div class="activite-label">'.$no.'</div>';
                                            echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
                                            echo '  <div class="activity-content">';
                                            echo '      <b>'.$keterangan.'</b> <br>'.$status.'<br><small>'.$tanggal.'</small>';
                                            echo '  </div>';
                                            echo '</div>';
                                            $no++;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">UMKM Terbaru</h5>
                            <div class="activity">
                                <?php
                                    if(empty($JumlahUMKM)){
                                        echo '<div class="activity-item d-flex">';
                                        echo '  Data Belum Tersedia';
                                        echo '</div>';
                                    }else{
                                        //Arraykan log UMKM
                                        $no=1;
                                        $QryUMKM = mysqli_query($Conn, "SELECT * FROM umkm ORDER BY id_umkm DESC LIMIT 6");
                                        while ($DataUMKM = mysqli_fetch_array($QryUMKM)) {
                                            $id_umkm = $DataUMKM['id_umkm'];
                                            $nama_umkm = $DataUMKM['nama_umkm'];
                                            $nama_pemilik = $DataUMKM['nama_pemilik'];
                                            
                                            echo '<div class="activity-item d-flex">';
                                            echo '  <div class="activite-label">'.$no.'</div>';
                                            echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
                                            echo '  <div class="activity-content">';
                                            echo '      <b>'.$nama_umkm.'</b><br><small>Pemilik: '.$nama_pemilik.'</small>';
                                            echo '  </div>';
                                            echo '</div>';
                                            $no++;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data Kriteria & Bobot</h5>
                            <div class="activity">
                                <?php
                                    if(empty($JumlahKriteria)){
                                        echo '<div class="activity-item d-flex">';
                                        echo '  Data Belum Tersedia';
                                        echo '</div>';
                                    }else{
                                        $no=1;
                                        $QryKriteria = mysqli_query($Conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
                                        while ($DataKrieria = mysqli_fetch_array($QryKriteria)) {
                                            $kriteria= $DataKrieria['kriteria'];
                                            $atribut= $DataKrieria['atribut'];
                                            $bobot_anp= $DataKrieria['bobot_anp'];
                                            $bobot_swara= $DataKrieria['bobot_swara'];
                                            
                                            echo '<div class="activity-item d-flex mb-3">';
                                            
                                            // Label Urutan
                                            echo '  <div class="activite-label fw-bold text-muted" style="min-width: 35px;">#'.$no.'</div>';
                                            echo '  <i class="bi bi-circle-fill activity-badge text-primary align-self-start"></i>';
                                            
                                            // Konten Kriteria
                                            echo '  <div class="activity-content w-100">';
                                            echo '      <div class="d-flex justify-content-between align-items-center">';
                                            echo '          <strong class="text-dark">'.$kriteria.'</strong>';
                                            
                                            // Badge Warna untuk Tipe Atribut
                                            if($atribut == 'Benefit'){
                                                echo '      <span class="badge bg-success rounded-pill" style="font-size:0.70em;">Benefit</span>';
                                            } else {
                                                echo '      <span class="badge bg-danger rounded-pill" style="font-size:0.70em;">Cost</span>';
                                            }
                                            
                                            echo '      </div>';
                                            
                                            // Badge Keterangan Bobot ANP dan SWARA
                                            echo '      <div class="mt-2">';
                                            echo '          <span class="badge bg-primary mb-1 me-1"><i class="bi bi-bar-chart-steps me-1"></i> ANP: '.$bobot_anp.'</span>';
                                            echo '          <span class="badge bg-info text-dark mb-1"><i class="bi bi-pie-chart-fill me-1"></i> SWARA: '.$bobot_swara.'</span>';
                                            echo '      </div>';
                                            
                                            echo '  </div>';
                                            echo '</div>';
                                            $no++;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Penilaian Terbaru</h5>
                            <div class="activity">
                                <?php
                                    $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM preferensi ORDER BY id_periode_penilaian DESC LIMIT 1");
                                    $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
                                    
                                    if(!empty($DataPeriodePenilaian['id_periode_penilaian'])){
                                        $id_periode_penilaian= $DataPeriodePenilaian['id_periode_penilaian'];
                                        //Arraykan log
                                        $no=1;
                                        $QryPreferensi = mysqli_query($Conn, "SELECT * FROM preferensi WHERE id_periode_penilaian='$id_periode_penilaian' ORDER BY preferensi DESC LIMIT 6");
                                        while ($DataPreferensi = mysqli_fetch_array($QryPreferensi)) {
                                            $id_umkm = $DataPreferensi['id_umkm'];
                                            $preferensi= $DataPreferensi['preferensi'];
                                            
                                            //Buka data UMKM
                                            $QryDetailUMKM = mysqli_query($Conn,"SELECT * FROM umkm WHERE id_umkm='$id_umkm'");
                                            $DataUMKM = mysqli_fetch_array($QryDetailUMKM);
                                            
                                            $nama_umkm = $DataUMKM['nama_umkm'];
                                            $nama_pemilik = $DataUMKM['nama_pemilik'];
                                            
                                            echo '<div class="activity-item d-flex">';
                                            echo '  <div class="activite-label">'.round($preferensi,3).'</div>';
                                            echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
                                            echo '  <div class="activity-content">';
                                            echo '      <b>'.$nama_umkm.'</b><br><small>'.$nama_pemilik.'</small>';
                                            echo '  </div>';
                                            echo '</div>';
                                            $no++;
                                        }
                                    } else {
                                        echo '<div class="activity-item d-flex">Belum ada penilaian</div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>