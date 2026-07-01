// ===============================================
// 1. MEMUAT TABEL KRITERIA
// ===============================================
$('#MenampilkanTabelKriteria').html("Loading...");
$('#MenampilkanTabelKriteria').load("_Page/Kriteria/TabelKriteria.php");

$('#batas').change(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelKriteria').html('Loading...');
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/TabelKriteria.php',
        data        :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelKriteria').html(data);
        }
    });
});

$('#ProsesBatas').submit(function(e){
    e.preventDefault();
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelKriteria').html('Loading...');
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/TabelKriteria.php',
        data        :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelKriteria').html(data);
        }
    });
});

$('#ProsesFilterKriteria').submit(function(e){
    e.preventDefault();
    var batas = $('#FilterBatas').val();
    var OrderBy = $('#OrderBy').val();
    var ShortBy = $('#ShortBy').val();
    var KeywordBy = $('#KeywordBy').val();
    var FilterKeyword = $('#FilterKeyword').val();
    $('#MenampilkanTabelKriteria').html('Loading...');
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/TabelKriteria.php',
        data        :  {batas: batas, OrderBy: OrderBy, ShortBy: ShortBy, KeywordBy: KeywordBy, keyword: FilterKeyword},
        success     : function(data){
            $('#MenampilkanTabelKriteria').html(data);
            $('#ModalFilterKriteria').modal('hide');
        }
    });
});

// ===============================================
// 2. MODAL & PROSES TAMBAH KRITERIA
// ===============================================
$('#ModalTambahKriteria').on('show.bs.modal', function (e) {
    $('#FormTambahKriteria').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/FormTambahKriteria.php',
        success     : function(data){
            $('#FormTambahKriteria').html(data);
        }
    });
});

$(document).on('submit', '#ProsesTambahKriteria', function(e) {
    e.preventDefault();
    $('#NotifikasiTambahKriteria').html('<span class="text-info">Sedang menyimpan data...</span>');
    
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/ProsesTambahKriteria.php',
        data        : new FormData(this),
        contentType : false,
        cache       : false,
        processData : false,
        success     : function(response){
            $('#NotifikasiTambahKriteria').html(response);
            
            // JIKA SUKSES
            if(response.includes("Success") || response.includes("Berhasil")){
                $('#ModalTambahKriteria').modal('hide');
                Swal.fire('Berhasil!', 'Data Kriteria Berhasil Ditambahkan', 'success').then(function(){
                    location.reload(); 
                });
            }
        }
    });
});


// ===============================================
// 3. MODAL & PROSES EDIT KRITERIA
// ===============================================
$('#ModalEditKriteria').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_kriteria = pecah[0];

    $('#FormEditKriteria').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/FormEditKriteria.php',
        data        : {id_kriteria: id_kriteria},
        success     : function(data){
            $('#FormEditKriteria').html(data);
        }
    });
});

$(document).on('submit', '#ProsesEditKriteria', function(e) {
    e.preventDefault();
    $('#NotifikasiEditKriteria').html('<span class="text-info">Sedang mengubah data...</span>');
    
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/ProsesEditKriteria.php',
        data        : new FormData(this),
        contentType : false,
        cache       : false,
        processData : false,
        success     : function(response){
            $('#NotifikasiEditKriteria').html(response);
            
            // JIKA SUKSES (Diperbaiki agar memunculkan SweetAlert)
            if(response.includes("Success") || response.includes("Berhasil") || $('#NotifikasiEditKriteriaBerhasil').length > 0){
                $('#ModalEditKriteria').modal('hide');
                Swal.fire('Berhasil!', 'Data Kriteria Berhasil Diubah', 'success').then(function(){
                    location.reload(); 
                });
            }
        }
    });
});


// ===============================================
// 4. DELETE KRITERIA
// ===============================================
$('#ModalDeleteKriteria').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_kriteria = pecah[0];

    $('#FormDeleteKriteria').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/FormDeleteKriteria.php',
        data        : {id_kriteria: id_kriteria},
        success     : function(data){
            $('#FormDeleteKriteria').html(data);
            
            $('#KonfirmasiHapusKriteria').off('click').on('click', function(){
                $('#NotifikasiHapusKriteria').html('Sedang Menghapus...');
                $.ajax({
                    type        : 'POST',
                    url         : '_Page/Kriteria/ProsesHapusKriteria.php',
                    data        : {id_kriteria: id_kriteria},
                    success     : function(response){
                        if(response.includes("Success") || response.includes("Berhasil")){
                            $('#ModalDeleteKriteria').modal('hide');
                            Swal.fire("Berhasil!", "Data Kriteria Dihapus!", "success").then(function(){
                                location.reload();
                            });
                        } else {
                            $('#NotifikasiHapusKriteria').html(response);
                        }
                    }
                });
            });
        }
    });
});


// ===============================================
// 5. DETAIL KRITERIA
// ===============================================
$('#ModalDetailKriteria').on('show.bs.modal', function (e) {
    var id_kriteria = $(e.relatedTarget).data('id');
    $('#FormDetailKriteria').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/FormDetailKriteria.php',
        data        : {id_kriteria: id_kriteria},
        success     : function(data){
            $('#FormDetailKriteria').html(data);
        }
    });
});


// ===============================================
// 6. ALTERNATIF KRITERIA (Tambah, Edit, Hapus)
// ===============================================

// -- Tambah Alternatif --
$('#ModalTambahAlternatif').on('show.bs.modal', function (e) {
    var id_kriteria = $(e.relatedTarget).data('id');
    $('#FormTambahAlternatif').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/FormTambahAlternatif.php',
        data        : {id_kriteria: id_kriteria},
        success     : function(data){
            $('#FormTambahAlternatif').html(data);
        }
    });
});
$(document).on('submit', '#ProsesTambahAlternatif', function(e) {
    e.preventDefault();
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/ProsesTambahAlternatif.php',
        data        : new FormData(this),
        contentType : false,
        cache       : false,
        processData : false,
        success     : function(response){
            if(response.includes("Success")){
                location.reload();
            } else {
                Swal.fire('Gagal Menyimpan', 'Silahkan periksa inputan Anda', 'warning');
            }
        }
    });
});

// -- Edit Alternatif --
$('#ModalEditAlternatif').on('show.bs.modal', function (e) {
    var id_alternatif = $(e.relatedTarget).data('id');
    $('#FormEditAlternatif').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/FormEditAlternatif.php',
        data        : {id_alternatif: id_alternatif},
        success     : function(data){
            $('#FormEditAlternatif').html(data);
        }
    });
});
$(document).on('submit', '#ProsesEditAlternatif', function(e) {
    e.preventDefault();
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/ProsesEditAlternatif.php',
        data        : new FormData(this),
        contentType : false,
        cache       : false,
        processData : false,
        success     : function(response){
            if(response.includes("Success")){
                location.reload();
            } else {
                Swal.fire('Gagal Mengubah', 'Silahkan periksa inputan Anda', 'warning');
            }
        }
    });
});

// -- Hapus Alternatif --
$('#ModalHapusAlternatif').on('show.bs.modal', function (e) {
    var id_alternatif = $(e.relatedTarget).data('id');
    $('#FormHapusAlternatif').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/Kriteria/FormHapusAlternatif.php',
        data        : {id_alternatif: id_alternatif},
        success     : function(data){
            $('#FormHapusAlternatif').html(data);
            
            $('#KonfirmasiHapusAlternatif').off('click').on('click', function(){
                $.ajax({
                    type        : 'POST',
                    url         : '_Page/Kriteria/ProsesHapusAlternatif.php',
                    data        : {id_alternatif: id_alternatif},
                    success     : function(response){
                        if(response.includes("Success")){
                            location.reload();
                        } else {
                            Swal.fire('Gagal Menghapus', 'Terjadi Kesalahan', 'error');
                        }
                    }
                });
            });
        }
    });
});