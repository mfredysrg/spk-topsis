<?php
if (empty($_GET['Page'])) {
    include "_Page/Dashboard/Dashboard.php";
} else {
    $Page = $_GET['Page'];

    switch ($Page) {
        case "Akses":
            include "_Page/Akses/Akses.php";
            break;
        case "UMKM":
            include "_Page/UMKM/UMKM.php";
            break;
        case "PerbandinganKriteria":
            include "_Page/PerbandinganKriteria/PerbandinganKriteria.php";
            break;
        case "BobotKriteria":
            include "_Page/BobotKriteria/BobotKriteria.php";
            break;
        case "Kriteria":
            include "_Page/Kriteria/Kriteria.php";
            break;
        case "Penilaian":
            include "_Page/Penilaian/Penilaian.php";
            break;
        case "Laporan":
            include "_Page/Laporan/Laporan.php";
            break;
        case "MyProfile":
            include "_Page/MyProfile/MyProfile.php";
            break;
        case "PenilaianUMKM":
            include "_Page/PenilaianUMKM/PenilaianUMKM.php";
            break;
        case "Help":
            include "_Page/Help/Help.php";
            break;
        default:
            include "_Page/Dashboard/Dashboard.php";
            break;
    }
}
?>