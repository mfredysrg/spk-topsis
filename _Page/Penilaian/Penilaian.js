$('#MenampilkanTabelPenilaian').html("Loading...");
$('#MenampilkanTabelPenilaian').load("_Page/Penilaian/TabelPenilaian.php");

$('#batas').change(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelPenilaian').html('Loading...');
    $.ajax({
        type        : 'POST',
        url         : '_Page/Penilaian/TabelPenilaian.php',
        data        :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelPenilaian').html(data);
        }
    });
});

$('#ProsesBatas').submit(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelPenilaian').html('Loading...');
    $.ajax({
        type        : 'POST',
        url         : '_Page/Penilaian/TabelPenilaian.php',
        data        :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelPenilaian').html(data);
        }
    });
});

$('#ProsesFilterPenilaian').submit(function(){
    var batas = $('#FilterBatas').val();
    var OrderBy = $('#OrderBy').val();
    var ShortBy = $('#ShortBy').val();
    var KeywordBy = $('#KeywordBy').val();
    var FilterKeyword = $('#FilterKeyword').val();
    $('#MenampilkanTabelPenilaian').html('Loading...');
    $.ajax({
        type        : 'POST',
        url         : '_Page/Penilaian/TabelPenilaian.php',
        data        :  {batas: batas, OrderBy: OrderBy, ShortBy: ShortBy, KeywordBy: KeywordBy, keyword: FilterKeyword},
        success     : function(data){
            $('#MenampilkanTabelPenilaian').html(data);
            $('#ModalFilterPenilaian').modal('hide');
        }
    });
});

//Tambah Penilaian
$('#ModalTambahPenilaian').on('show.bs.modal', function (e) {
    $('#FormTambahPenilaian').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Penilaian/FormTambahPenilaian.php',
        success     : function(data){
            $('#FormTambahPenilaian').html(data);
            //Proses Tambah Penilaian
            $('#ProsesTambahPenilaian').submit(function(){
                $('#NotifikasiTambahPenilaian').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesTambahPenilaian')[0];
                var data = new FormData(form);
                $.ajax({
                    type        : 'POST',
                    url         : '_Page/Penilaian/ProsesTambahPenilaian.php',
                    data        :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiTambahPenilaian').html(data);
                        var NotifikasiTambahPenilaianBerhasil=$('#NotifikasiTambahPenilaianBerhasil').html();
                        if(NotifikasiTambahPenilaianBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});

//Edit Penilaian
$('#ModalEditPenilaian').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_periode_penilaian = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormEditPenilaian').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Penilaian/FormEditPenilaian.php',
        data        : {id_periode_penilaian: id_periode_penilaian},
        success     : function(data){
            $('#FormEditPenilaian').html(data);
            //Proses Edit Penilaian
            $('#ProsesEditPenilaian').submit(function(){
                $('#NotifikasiEditPenilaian').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditPenilaian')[0];
                var data = new FormData(form);
                $.ajax({
                    type        : 'POST',
                    url         : '_Page/Penilaian/ProsesEditPenilaian.php',
                    data        :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditPenilaian').html(data);
                        var NotifikasiEditPenilaianBerhasil=$('#NotifikasiEditPenilaianBerhasil').html();
                        if(NotifikasiEditPenilaianBerhasil=="Success"){
                            $('#MenampilkanTabelPenilaian').html("Loading...");
                            $.ajax({
                                type        : 'POST',
                                url         : '_Page/Penilaian/TabelPenilaian.php',
                                data        :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelPenilaian').html(data);
                                    $('#ModalEditPenilaian').modal('hide');
                                    swal("Good Job!", "Edit Penilaian Berhasil!", "success");
                                }
                            });
                        }
                    }
                });
            });
        }
    });
});

//Hapus Penilaian
$('#ModalDeletePenilaian').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_periode_penilaian = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormDeletePenilaian').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Penilaian/FormDeletePenilaian.php',
        data        : {id_periode_penilaian: id_periode_penilaian},
        success     : function(data){
            $('#FormDeletePenilaian').html(data);
            //Konfirmasi Hapus Penilaian
            $('#KonfirmasiHapusPenilaian').click(function(){
                $('#NotifikasiHapusPenilaian').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                $.ajax({
                    type        : 'POST',
                    url         : '_Page/Penilaian/ProsesHapusPenilaian.php',
                    data        : {id_periode_penilaian: id_periode_penilaian},
                    success     : function(data){
                        $('#NotifikasiHapusPenilaian').html(data);
                        var NotifikasiHapusPenilaianBerhasil=$('#NotifikasiHapusPenilaianBerhasil').html();
                        if(NotifikasiHapusPenilaianBerhasil=="Success"){
                            $.ajax({
                                type        : 'POST',
                                url         : '_Page/Penilaian/TabelPenilaian.php',
                                data        :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelPenilaian').html(data);
                                    $('#ModalDeletePenilaian').modal('hide');
                                    swal("Good Job!", "Delete Penilaian Berhasil!", "success");
                                }
                            });
                        }
                    }
                });
            });
        }
    });
});

//Detail Penilaian
$('#ModalDetailPenilaian').on('show.bs.modal', function (e) {
    var id_periode_penilaian = $(e.relatedTarget).data('id');
    $('#FormDetailPenilaian').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Penilaian/FormDetailPenilaian.php',
        data        : {id_periode_penilaian: id_periode_penilaian},
        success     : function(data){
            $('#FormDetailPenilaian').html(data);
        }
    });
});

//Edit Nilai
$('#ModalEditNilai').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_periode_penilaian = pecah[0];
    var id_karyawan = pecah[1];
    $('#FormEditNilai').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Penilaian/FormEditNilai.php',
        data        : {id_periode_penilaian: id_periode_penilaian, id_karyawan: id_karyawan},
        success     : function(data){
            $('#FormEditNilai').html(data);
            $('#ProsesEditNilai').submit(function(){
                $('#NotifikasiEditNilai').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditNilai')[0];
                var data = new FormData(form);
                $.ajax({
                    type        : 'POST',
                    url         : '_Page/Penilaian/ProsesEditNilai.php',
                    data        :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditNilai').html(data);
                        var NotifikasiEditNilaiBerhasil=$('#NotifikasiEditNilaiBerhasil').html();
                        if(NotifikasiEditNilaiBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});

//Update Status Penilaian
$('#ModalUpdateStatusPenilaian').on('show.bs.modal', function (e) {
    var id_periode_penilaian = $(e.relatedTarget).data('id');
    $('#FormUpdateStatusPenilaian').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Penilaian/FormEditPenilaian.php',
        data        : {id_periode_penilaian: id_periode_penilaian},
        success     : function(data){
            $('#FormUpdateStatusPenilaian').html(data);
            //Proses Edit Penilaian
            $('#ProsesEditSesiPenilaian').submit(function(){
                $('#NotifikasiEditPenilaian').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditSesiPenilaian')[0];
                var data = new FormData(form);
                $.ajax({
                    type        : 'POST',
                    url         : '_Page/Penilaian/ProsesEditPenilaian.php',
                    data        :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditPenilaian').html(data);
                        var NotifikasiEditPenilaianBerhasil=$('#NotifikasiEditPenilaianBerhasil').html();
                        if(NotifikasiEditPenilaianBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});

// =========================================================================
// INI ADALAH BAGIAN YANG DIREVISI: Modal Hitung Penilaian (Integrasi MCDM)
// =========================================================================

// Event saat Modal Hitung muncul, kita simpan ID periode-nya
$('#ModalHitungPenilaian').on('show.bs.modal', function (e) {
    var id_periode_penilaian = $(e.relatedTarget).data('id');
    
    // Simpan ID ke dalam form agar bisa diambil saat tombol submit ditekan
    $('#FormHitungPenilaian').data('id', id_periode_penilaian);
    
    // Kosongkan notifikasi sebelumnya jika ada
    $('#NotifikasiHitungPenilaian').html('');
});

// Proses saat Form Hitung di-submit
$('#FormHitungPenilaian').submit(function(e){
    e.preventDefault();
    
    // Ambil ID periode yang sudah disimpan sebelumnya
    var id_periode_penilaian = $(this).data('id');
    
    // Ambil semua data form termasuk pilihan "metode_pembobotan"
    var form = $(this)[0];
    var formData = new FormData(form);
    
    // Tambahkan id_periode_penilaian ke dalam payload request
    formData.append('id_periode_penilaian', id_periode_penilaian);
    
    // Munculkan indikator loading di container hasil perhitungan
    $('#HasilPerhitungan').html('<div class="text-center my-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Sedang memproses perhitungan sistem Hybrid MCDM...</p></div>');
    
    // Ubah status tombol menjadi loading agar tidak diklik dua kali
    $('#BtnHitung').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghitung...');
    $('#BtnHitung').prop('disabled', true);
    
    $.ajax({
        type        : 'POST',
        url         : '_Page/Penilaian/HasilPerhitungan.php',
        data        : formData,
        cache       : false,
        processData : false,
        contentType : false,
        success     : function(response){
            // Tampilkan hasil tabel perhitungan
            $('#HasilPerhitungan').html(response);
            
            // Tutup modal
            $('#ModalHitungPenilaian').modal('hide');
            
            // Kembalikan tombol ke keadaan semula
            $('#BtnHitung').html('<i class="bi bi-check"></i> Ya, Hitung');
            $('#BtnHitung').prop('disabled', false);
        },
        error       : function(){
            $('#NotifikasiHitungPenilaian').html('<div class="alert alert-danger mt-3">Terjadi kesalahan saat terhubung ke server/database!</div>');
            $('#BtnHitung').html('<i class="bi bi-check"></i> Ya, Hitung');
            $('#BtnHitung').prop('disabled', false);
        }
    });
});