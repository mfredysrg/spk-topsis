<?php
    echo '<div class="pagetitle">';
    //Routing Page Title
    if(empty($_GET['Page'])){
        echo '<h1>Dashboard</h1>';
        echo '<nav>';
        echo '  <ol class="breadcrumb">';
        echo '      <li class="breadcrumb-item active">Dashboard</li>';
        echo '  </ol>';
        echo '</nav>';
    }else{
        if($_GET['Page']=="Akses"){
            echo '<h1><i class="bi bi-person"></i> Akses</h1>';
            echo '<nav>';
            echo '  <ol class="breadcrumb">';
            echo '      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>';
            echo '      <li class="breadcrumb-item active">Akses</li>';
            echo '  </ol>';
            echo '</nav>';
        }else{
            if($_GET['Page']=="Karyawan"){
                echo '<h1><i class="bi bi-person-badge"></i> Karyawan</h1>';
                echo '<nav>';
                echo '  <ol class="breadcrumb">';
                echo '      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>';
                echo '      <li class="breadcrumb-item active">Karyawan</li>';
                echo '  </ol>';
                echo '</nav>';
            }else{
                if($_GET['Page']=="Kriteria"){
                    echo '<h1><i class="bi bi-list-check"></i> Kriteria</h1>';
                    echo '<nav>';
                    echo '  <ol class="breadcrumb">';
                    echo '      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>';
                    echo '      <li class="breadcrumb-item active">Kriteria</li>';
                    echo '  </ol>';
                    echo '</nav>';
                }else{
                    if($_GET['Page']=="Penilaian"){
                        echo '<h1><i class="bi bi-pencil-square"></i> Penilaian</h1>';
                        echo '<nav>';
                        echo '  <ol class="breadcrumb">';
                        echo '      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>';
                        echo '      <li class="breadcrumb-item active">Penilaian</li>';
                        echo '  </ol>';
                        echo '</nav>';
                    }else{
                        if($_GET['Page']=="Laporan"){
                            echo '<h1><i class="bi bi-bar-chart-line"></i> Laporan</h1>';
                            echo '<nav>';
                            echo '  <ol class="breadcrumb">';
                            echo '      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>';
                            echo '      <li class="breadcrumb-item active">Laporan</li>';
                            echo '  </ol>';
                            echo '</nav>';
                        }else{
                            if($_GET['Page']=="MyProfile"){
                                echo '<h1><i class="bi bi-person-circle"></i> My Profile</h1>';
                                echo '<nav>';
                                echo '  <ol class="breadcrumb">';
                                echo '      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>';
                                echo '      <li class="breadcrumb-item active">My Profile</li>';
                                echo '  </ol>';
                                echo '</nav>';
                            }else{
                                if($_GET['Page']=="PenilaianKaryawan"){
                                    echo '<h1><i class="bi bi-question-circle"></i> Penilaian Karyawan</h1>';
                                    echo '<nav>';
                                    echo '  <ol class="breadcrumb">';
                                    echo '      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>';
                                    echo '      <li class="breadcrumb-item active">Penilaian Karyawan</li>';
                                    echo '  </ol>';
                                    echo '</nav>';
                                }else{
                                    if($_GET['Page']=="Help"){
                                        echo '<h1><i class="bi bi-question-circle"></i> Bantuan</h1>';
                                        echo '<nav>';
                                        echo '  <ol class="breadcrumb">';
                                        echo '      <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>';
                                        echo '      <li class="breadcrumb-item active">Bantuan</li>';
                                        echo '  </ol>';
                                        echo '</nav>';
                                    }else{
                                        echo '<h1>Error Page</h1>';
                                        echo '<nav>';
                                        echo '  <ol class="breadcrumb">';
                                        echo '      <li class="breadcrumb-item active">Error Page</li>';
                                        echo '  </ol>';
                                        echo '</nav>';
                                        
                                    }
                                    
                                }
                            }
                            
                        }
                    }
                }
            }
        }
    }
    echo '</div>';
?>
