<style>
    /* Pengaturan Khusus Saat Mode Print (Ctrl+P) */
    @media print {
        body * { visibility: hidden; }
        #MenampilkanLaporan, #MenampilkanLaporan * { visibility: visible; }
        #MenampilkanLaporan {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        @page { margin: 0mm; }
        body { margin: 1.5cm; }
    }
</style>

<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert"> 
                <small>
                    Halaman laporan digunakan oleh pimpinan untuk memperoleh data hasil perhitungan penilaian kinerja.
                    Pilih periode laporan dan <b>Tampilkan</b> untuk menampilkan data laporan penilaian. Anda bisa melakukan <b>Cetak (Print)</b> atau <b>Export Excel</b> untuk mengunduh laporan. 
                </small>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <form action="javascript:void(0);" id="ProsesTampilkanLaporan">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <select name="id_periode_penilaian" id="id_periode_penilaian" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                        //menampilkan list periode penilaian
                                        $query = mysqli_query($Conn, "SELECT * FROM periode_penilaian WHERE status='Selesai'");
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
                            
                            <div class="col-md-2 text-center mt-3">
                                <button type="button" class="btn btn-md btn-primary btn-block btn-rounded" id="TampilkanLaporan">
                                    <i class="bi bi-arrow-down-square"></i> Tampilkan
                                </button>
                            </div>
                            
                            <div class="col-md-2 text-center mt-3">
                                <button type="button" onclick="confirmPrint();" class="btn btn-md btn-info btn-block btn-rounded">
                                    <i class="bi bi-printer"></i> Cetak
                                </button>
                            </div>

                            <div class="col-md-2 text-center mt-3">
                                <button type="button" onclick="exportToExcel('MenampilkanLaporan', 'Laporan_Penilaian_Kinerja');" class="btn btn-md btn-success btn-block btn-rounded">
                                    <i class="bi bi-file-earmark-excel"></i> Excel
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

<script>
// Fungsi untuk Konfirmasi Print
function confirmPrint() {
    var elemenLaporan = document.getElementById("MenampilkanLaporan");

    // Mencegah print jika laporan belum ditampilkan ke layar
    if(elemenLaporan.innerText.includes("Silahkan Pilih Periode Laporan Terlebih Dulu")) {
        alert("Harap Tampilkan data laporan terlebih dulu sebelum mencetak!");
        return;
    }

    // Memunculkan text box konfirmasi (Iya/Tidak)
    var konfirmasi = confirm("Apakah Anda yakin ingin mencetak data laporan ini?");
    
    // Jika user memilih "OK" (Iya), jalankan perintah print
    if(konfirmasi) {
        window.print();
    }
}

// Fungsi untuk Export Excel
function exportToExcel(elementId, filename = '') {
    var elemenLaporan = document.getElementById(elementId);

    // Mencegah export jika laporan belum ditampilkan ke layar
    if(elemenLaporan.innerText.includes("Silahkan Pilih Periode Laporan Terlebih Dulu")) {
        alert("Harap Tampilkan data laporan terlebih dulu sebelum menekan Export Excel!");
        return;
    }

    // [REVISI] Memunculkan text box konfirmasi (Iya/Tidak) untuk EXCEL
    var konfirmasi = confirm("Apakah Anda yakin ingin mengunduh data laporan ini ke format Excel?");
    
    // Jika user memilih "OK" (Iya), barulah proses file excel-nya berjalan
    if(konfirmasi) {
        
        // Mengkloning elemen laporan agar tidak merusak tampilan asli di website saat proses perapian
        var clone = elemenLaporan.cloneNode(true);

        // Memaksa tabel memiliki atribut border dan perapian sel agar di Excel tidak berantakan
        var tables = clone.getElementsByTagName("table");
        for (var i = 0; i < tables.length; i++) {
            tables[i].setAttribute("border", "1");
            tables[i].setAttribute("cellpadding", "5");
            tables[i].setAttribute("cellspacing", "0");
            tables[i].style.width = "100%";
            tables[i].style.borderCollapse = "collapse";
        }

        // Memberikan warna background abu-abu khusus untuk judul kolom (header)
        var ths = clone.getElementsByTagName("th");
        for (var i = 0; i < ths.length; i++) {
            ths[i].style.backgroundColor = "#d3d3d3"; 
            ths[i].style.fontWeight = "bold";
        }

        // Mengambil HTML yang sudah dirapikan
        var html = clone.innerHTML;

        // Menyisipkan template standar Microsoft Excel
        var templateExcel = `
        <html xmlns:o="urn:schemas-microsoft-com:office:office"
              xmlns:x="urn:schemas-microsoft-com:office:excel"
              xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta charset="utf-8">
            <style>
                table { font-family: Arial, sans-serif; }
                th, td { border: 1px solid #000000; padding: 5px; text-align: center; vertical-align: middle; }
            </style>
        </head>
        <body>
            ${html}
        </body>
        </html>`;

        // Mengubah string HTML menjadi file Excel
        var blob = new Blob([templateExcel], { type: 'application/vnd.ms-excel' });
        var url = URL.createObjectURL(blob);
        
        var link = document.createElement("a");
        link.href = url;
        link.download = filename + ".xls";
        
        // Trigger download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}
</script>