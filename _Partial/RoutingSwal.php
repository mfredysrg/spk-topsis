<?php
    if(!empty($_SESSION['NotifikasiSwal'])){
        $NotifikasiSwal=$_SESSION['NotifikasiSwal'];
?>
    <!------- Notifikasi ------------>
    <?php if($NotifikasiSwal=="Login Berhasil"){ ?>
        <script>
            swal("Welcome back!", "Login Successful", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Pendaftaran Berhasil"){ ?>
        <script>
            swal("Good Job!", "Pendaftaran Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Akses Berhasil"){ ?>
        <script>
            swal("Welcome back!", "Tambah Akses Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Akses Berhasil"){ ?>
        <script>
            swal("Welcome back!", "Hapus Akses Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Atur Akses Berhasil"){ ?>
        <script>
            swal("Welcome back!", "Atur Akses Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Karyawan Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Karyawan Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Kriteria Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Kriteria Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Penilaian Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Penilaian Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Alternatif Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Alternatif Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Alternatif Berhasil"){ ?>
        <script>
            swal("Success!", "Hapus Alternatif Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Alternatif Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Alternatif Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Proses Penilaian Berhasil"){ ?>
        <script>
            swal("Success!", "Proses Penilaian Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Simpan Setting Email Berhasil"){ ?>
        <script>
            swal("Success!", "Save Email Settings Successful", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Kirim Email Berhasil"){ ?>
        <script>
            swal("Success!", "Send Email Successful", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Simpan Dokumentasi API Berhasil"){ ?>
        <script>
            swal("Success!", "Save API Documentation Successful", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Kirim Request Dukungan Berhasil"){ ?>
        <script>
            swal("Success!", "Kirim Request Dukungan Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Riwayat Kerja Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Riwayat Kerja Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Dukungan Berhasil"){ ?>
        <script>
            swal("Success!", "Hapus Dukungan Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Data Dukungan Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Data Dukungan Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Agenda Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Agenda Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Agenda Berhasil"){ ?>
        <script>
            swal("Success!", "Hapus Agenda Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Agenda Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Agenda Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Event Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Event Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Event Berhasil"){ ?>
        <script>
            swal("Success!", "Hapus Event Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Event Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Event Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Jadwal Event Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Jadwal Event Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Akses Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Access Successful", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Password Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Password Successful", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Simpan Help Berhasil"){ ?>
        <script>
            swal("Success!", "Save content data successfully", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Help Berhasil"){ ?>
        <script>
            swal("Success!", "Delete content data successfully", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Waktu Henti Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Waktu Henti Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Inventaris Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Inventaris Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Screening Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Screening Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Screening Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Screening Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Satuan Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Satuan Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Satuan Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Satuan Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Satuan Berhasil"){ ?>
        <script>
            swal("Success!", "Hapus Satuan Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Harga Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Harga Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Update Kategori Harga Berhasil"){ ?>
        <script>
            swal("Success!", "Update Kategori Harga Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Expired Date Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Expired Date Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Expired Date Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Expired Date Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Simpan Pembayaran Berhasil"){ ?>
        <script>
            swal("Success!", "Simpan Pembayaran Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Simpan Tamplate Berhasil"){ ?>
        <script>
            swal("Success!", "Simpan Tamplate Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Rencana Kirim Pesan Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Rencana Kirim Pesan Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Chat Box Berhasil"){ ?>
        <script>
            swal("Success!", "Hapus Chat Box Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Kirim Pesan Berhasil"){ ?>
        <script>
            swal("Success!", "Kirim Pesan Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Simpan Form Setting Berhasil"){ ?>
        <script>
            swal("Success!", "Simpan Form Setting Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Form Setting Berhasil"){ ?>
        <script>
            swal("Success!", "Hapus Form Setting Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Draft Medrek Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Draft Medrek Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Draft Medrek Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Draft Medrek Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Draft Medrek Berhasil"){ ?>
        <script>
            swal("Success!", "Hapus Draft Medrek Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Master Pasien Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Master Pasien Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Wilayah Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Wilayah Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Dokter Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Data Tenaga Kesehatan Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Tindakan Medis Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Tindakan Medis Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Supplier Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Supplier Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Barang Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Barang Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Tambah Rincian Berhasil"){ ?>
        <script>
            swal("Success!", "Tambah Rincian Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Rincian Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Rincian Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Hapus Rincian Berhasil"){ ?>
        <script>
            swal("Success!", "Hapus Rincian Berhasil", "success");
        </script>
    <?php } ?>
    <?php if($NotifikasiSwal=="Edit Transaksi Berhasil"){ ?>
        <script>
            swal("Success!", "Edit Transaksi Berhasil", "success");
        </script>
    <?php } ?>
<?php 
    unset($_SESSION['NotifikasiSwal']);
    }
?>