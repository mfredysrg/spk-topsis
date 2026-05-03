<?php
    if(empty($_GET['Page'])){
        $PageMenu="";
    }else{
        $PageMenu=$_GET['Page'];
    }
    if(empty($_GET['Sub'])){
        $SubMenu="";
    }else{
        $SubMenu=$_GET['Sub'];
    }
?>
<aside id="sidebar" class="sidebar menu_background">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu==""){echo "";}else{echo "collapsed";} ?>" href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <?php if($SessionAkses=="HRD"){ ?>
            <li class="nav-item">
                <a class="nav-link <?php if($PageMenu!=="Akses"){echo "collapsed";} ?>" href="index.php?Page=Akses">
                    <i class="bi bi-person"></i>
                    <span>Akses</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($PageMenu!=="Karyawan"){echo "collapsed";} ?>" href="index.php?Page=Karyawan">
                    <i class="bi bi-person-badge"></i>
                    <span>Karyawan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($PageMenu!=="Kriteria"){echo "collapsed";} ?>" href="index.php?Page=Kriteria">
                    <i class="bi bi-list-columns"></i>
                    <span>Kriteria</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($PageMenu!=="Penilaian"){echo "collapsed";} ?>" href="index.php?Page=Penilaian">
                    <i class="bi bi-list-check"></i>
                    <span>Penilaian</span>
                </a>
            </li>
        <?php } ?>
        <?php if($SessionAkses=="Pimpinan"){ ?>
            <li class="nav-item">
                <a class="nav-link <?php if($PageMenu!=="Laporan"){echo "collapsed";} ?>" href="index.php?Page=Laporan">
                    <i class="bi bi-bar-chart"></i>
                    <span>Laporan</span>
                </a>
            </li>
        <?php } ?>
        <?php if($SessionAkses=="Karyawan"){ ?>
            <li class="nav-item">
                <a class="nav-link <?php if($PageMenu!=="PenilaianKaryawan"){echo "collapsed";} ?>" href="index.php?Page=PenilaianKaryawan">
                    <i class="bi bi-list-check"></i>
                    <span>Penilaian</span>
                </a>
            </li>
        <?php } ?>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu!=="Help"){echo "collapsed";} ?>" href="index.php?Page=Help&Sub=HelpData">
                <i class="bi bi-question"></i>
                <span>Bantuan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalLogout">
                <i class="bi bi-box-arrow-in-left"></i>
                <span>Keluar</span>
            </a>
        </li>
    </ul>
</aside>  
