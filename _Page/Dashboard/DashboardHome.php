<?php
    $JumlahAkses = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM akses"));
    $JumlahKaryawan = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM karyawan"));
    $JumlahKaryawan2 = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM karyawan"));
    $JumlahKriteria = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM kriteria"));
    $JumlahPeriodePenilaian = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM periode_penilaian"));
    $JumlahNilai = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM nilai"));
    
?>
<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Karyawan<span></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $JumlahKaryawan ;?></h6>
                                    <span class="text-muted small pt-2 ps-1">Orang</span>
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
                                    <h6><?php echo $JumlahKaryawan;?></h6>
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
                <!-- Reports -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Rata-Rata Kinerja</h5>
                            <div id="reportsChart">
                                <!-- Line Chart -->
                            </div>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#reportsChart"), {
                                    series: [{
                                        name: 'Penilaian',
                                        data: [
                                                    <?php
                                                        $QryPeriodePenilaian = mysqli_query($Conn, "SELECT*FROM periode_penilaian ORDER BY id_periode_penilaian ASC");
                                                        while ($DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian)) {
                                                            $id_periode_penilaian= $DataPeriodePenilaian['id_periode_penilaian'];
                                                            $TanggalPenilaian= $DataPeriodePenilaian['tanggal'];
                                                            $Sum = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(nilai) AS jumlah FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian'"));
                                                            $JumlahNilai = $Sum['jumlah'];
                                                            $JumlahKaryawan = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM nilai WHERE id_periode_penilaian='$id_periode_penilaian'"));
                                                            if(empty($JumlahKaryawan)){
                                                                $RataRata=0;
                                                            }else{
                                                                $RataRata=$JumlahNilai/$JumlahKaryawan;
                                                            }
                                                            
                                                            echo ''.$RataRata.', ';
                                                        }
                                                    ?>
                                                    // 31, 40, 28, 51, 42, 82, 90
                                                ],
                                            }, 
                                            // {
                                            //     name: 'Revenue',
                                            //     data: [11, 32, 45, 32, 34, 52, 41]
                                            // }, 
                                            // {
                                            //     name: 'Customers',
                                            //     data: [15, 11, 32, 18, 9, 24, 11]
                                            // }
                                        ],
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
                                            $QryPeriodePenilaian = mysqli_query($Conn, "SELECT*FROM periode_penilaian ORDER BY id_periode_penilaian ASC");
                                            while ($DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian)) {
                                                $id_periode_penilaian= $DataPeriodePenilaian['id_periode_penilaian'];
                                                $TanggalPenilaian= $DataPeriodePenilaian['tanggal'];
                                                $strtotime=strtotime($TanggalPenilaian);
                                                $TanggalPenilaian=date('d-m-Y',$strtotime);
                                                echo '"'.$TanggalPenilaian.'", ';
                                            }
                                        ?>
                                        // "2018-09-19T00:00:00.000Z", 
                                        // "2018-09-19T01:30:00.000Z", 
                                        // "2018-09-19T02:30:00.000Z", 
                                        // "2018-09-19T03:30:00.000Z", 
                                        // "2018-09-19T04:30:00.000Z", 
                                        // "2018-09-19T05:30:00.000Z", 
                                        // "2018-09-19T06:30:00.000Z"
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
                        <!-- <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start"><h6>Filter</h6></li>
                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div> -->
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
                                        $QryPeriodePenilaian= mysqli_query($Conn, "SELECT*FROM periode_penilaian ORDER BY id_periode_penilaian DESC LIMIT 5");
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
                            <h5 class="card-title">Karyawan Terbaru</h5>
                            <div class="activity">
                                <?php
                                    if(empty($JumlahKaryawan2)){
                                        echo '<div class="activity-item d-flex">';
                                        echo '  Data Belum Tersedia';
                                        echo '</div>';
                                    }else{
                                        //Arraykan log
                                        $no=1;
                                        $QryKaryawan = mysqli_query($Conn, "SELECT*FROM karyawan ORDER BY id_karyawan DESC LIMIT 6");
                                        while ($DataKaryawan = mysqli_fetch_array($QryKaryawan)) {
                                            $id_karyawan= $DataKaryawan['id_karyawan'];
                                            $nama= $DataKaryawan['nama'];
                                            $nip= $DataKaryawan['nip'];
                                            $jabatan= $DataKaryawan['jabatan'];
                                            echo '<div class="activity-item d-flex">';
                                            echo '  <div class="activite-label">'.$no.'</div>';
                                            echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
                                            echo '  <div class="activity-content">';
                                            echo '      <b>'.$nama.'</b><br>'.$jabatan.'';
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
                            <h5 class="card-title">Kriteria</h5>
                            <div class="activity">
                                <?php
                                    if(empty($JumlahKriteria)){
                                        echo '<div class="activity-item d-flex">';
                                        echo '  Data Belum Tersedia';
                                        echo '</div>';
                                    }else{
                                        //Arraykan log
                                        $no=1;
                                        $QryKriteria = mysqli_query($Conn, "SELECT*FROM kriteria ORDER BY bobot");
                                        while ($DataKrieria = mysqli_fetch_array($QryKriteria)) {
                                            $id_kriteria= $DataKrieria['id_kriteria'];
                                            $kriteria= $DataKrieria['kriteria'];
                                            $atribut= $DataKrieria['atribut'];
                                            $bobot= $DataKrieria['bobot'];
                                            echo '<div class="activity-item d-flex">';
                                            echo '  <div class="activite-label">'.$bobot.'</div>';
                                            echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
                                            echo '  <div class="activity-content">';
                                            echo '      <b>'.$kriteria.'</b><br>'.$atribut.'';
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
                                    $QryPeriodePenilaian = mysqli_query($Conn,"SELECT * FROM preferensi ORDER BY id_periode_penilaian DESC")or die(mysqli_error($Conn));
                                    $DataPeriodePenilaian = mysqli_fetch_array($QryPeriodePenilaian);
                                    $id_periode_penilaian= $DataPeriodePenilaian['id_periode_penilaian'];
                                    //Arraykan log
                                    $no=1;
                                    $QryPreferensi = mysqli_query($Conn, "SELECT*FROM preferensi ORDER BY preferensi DESC");
                                    while ($DataPreferensi = mysqli_fetch_array($QryPreferensi)) {
                                        $id_karyawan= $DataPreferensi['id_karyawan'];
                                        $positif= $DataPreferensi['positif'];
                                        $negatif= $DataPreferensi['negatif'];
                                        $preferensi= $DataPreferensi['preferensi'];
                                        //Buka data karyawan
                                        $QryDetailKaryawan = mysqli_query($Conn,"SELECT * FROM karyawan WHERE id_karyawan='$id_karyawan'")or die(mysqli_error($Conn));
                                        $DataKaryawan = mysqli_fetch_array($QryDetailKaryawan);
                                        $id_akses= $DataKaryawan['id_akses'];
                                        $nama= $DataKaryawan['nama'];
                                        $kontak = $DataKaryawan['kontak'];
                                        $jabatan= $DataKaryawan['jabatan'];
                                        echo '<div class="activity-item d-flex">';
                                        echo '  <div class="activite-label">'.$preferensi.'</div>';
                                        echo '  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>';
                                        echo '  <div class="activity-content">';
                                        echo '      <b>'.$nama.'</b><br>'.$jabatan.'';
                                        echo '  </div>';
                                        echo '</div>';
                                        $no++;
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