$('#MenampilkanTabelKriteria').html("Loading...");
$('#MenampilkanTabelKriteria').load("_Page/Kriteria/TabelKriteria.php");
$('#batas').change(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelKriteria').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kriteria/TabelKriteria.php',
        data 	    :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelKriteria').html(data);
        }
    });
});
$('#ProsesBatas').submit(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelKriteria').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kriteria/TabelKriteria.php',
        data 	    :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelKriteria').html(data);
        }
    });
});
$('#ProsesFilterKriteria').submit(function(){
    var batas = $('#FilterBatas').val();
    var OrderBy = $('#OrderBy').val();
    var ShortBy = $('#ShortBy').val();
    var KeywordBy = $('#KeywordBy').val();
    var FilterKeyword = $('#FilterKeyword').val();
    $('#MenampilkanTabelKriteria').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kriteria/TabelKriteria.php',
        data 	    :  {batas: batas, OrderBy: OrderBy, ShortBy: ShortBy, KeywordBy: KeywordBy, keyword: FilterKeyword},
        success     : function(data){
            $('#MenampilkanTabelKriteria').html(data);
            $('#ModalFilterKriteria').modal('hide');
        }
    });
});
//Tambah Kriteria
$('#ModalTambahKriteria').on('show.bs.modal', function (e) {
    $('#FormTambahKriteria').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kriteria/FormTambahKriteria.php',
        success     : function(data){
            $('#FormTambahKriteria').html(data);
            //Proses Tambah Kriteria
            $('#ProsesTambahKriteria').submit(function(){
                $('#NotifikasiTambahKriteria').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesTambahKriteria')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Kriteria/ProsesTambahKriteria.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiTambahKriteria').html(data);
                        var NotifikasiTambahKriteriaBerhasil=$('#NotifikasiTambahKriteriaBerhasil').html();
                        if(NotifikasiTambahKriteriaBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});
//Edit Kriteria
$('#ModalEditKriteria').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_kriteria = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormEditKriteria').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kriteria/FormEditKriteria.php',
        data        : {id_kriteria: id_kriteria},
        success     : function(data){
            $('#FormEditKriteria').html(data);
            //Proses Edit Kriteria
            $('#ProsesEditKriteria').submit(function(){
                $('#NotifikasiEditKriteria').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditKriteria')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Kriteria/ProsesEditKriteria.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditKriteria').html(data);
                        var NotifikasiEditKriteriaBerhasil=$('#NotifikasiEditKriteriaBerhasil').html();
                        if(NotifikasiEditKriteriaBerhasil=="Success"){
                            $('#MenampilkanTabelKriteria').html("Loading...");
                            $.ajax({
                                type 	    : 'POST',
                                url 	    : '_Page/Kriteria/TabelKriteria.php',
                                data 	    :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelKriteria').html(data);
                                    $('#ModalEditKriteria').modal('hide');
                                    swal("Good Job!", "Edit Kriteria Berhasil!", "success");
                                }
                            });
                        }
                    }
                });
            });
        }
    });
});
//Hapus Kriteria
$('#ModalDeleteKriteria').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_kriteria = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormDeleteKriteria').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kriteria/FormDeleteKriteria.php',
        data        : {id_kriteria: id_kriteria},
        success     : function(data){
            $('#FormDeleteKriteria').html(data);
            //Konfirmasi Hapus Kriteria
            $('#KonfirmasiHapusKriteria').click(function(){
                $('#NotifikasiHapusKriteria').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Kriteria/ProsesHapusKriteria.php',
                    data        : {id_kriteria: id_kriteria},
                    success     : function(data){
                        $('#NotifikasiHapusKriteria').html(data);
                        var NotifikasiHapusKriteriaBerhasil=$('#NotifikasiHapusKriteriaBerhasil').html();
                        if(NotifikasiHapusKriteriaBerhasil=="Success"){
                            $.ajax({
                                type 	    : 'POST',
                                url 	    : '_Page/Kriteria/TabelKriteria.php',
                                data 	    :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelKriteria').html(data);
                                    $('#ModalDeleteKriteria').modal('hide');
                                    swal("Good Job!", "Delete Kriteria Berhasil!", "success");
                                }
                            });
                        }
                    }
                });
            });
        }
    });
});
//Modal Detail Kriteria
$('#ModalDetailKriteria').on('show.bs.modal', function (e) {
    var id_kriteria = $(e.relatedTarget).data('id');
    $('#FormDetailKriteria').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kriteria/FormDetailKriteria.php',
        data        : {id_kriteria: id_kriteria},
        success     : function(data){
            $('#FormDetailKriteria').html(data);
        }
    });
});
//Tambah Alternatif
$('#ModalTambahAlternatif').on('show.bs.modal', function (e) {
    var id_kriteria = $(e.relatedTarget).data('id');
    $('#FormTambahAlternatif').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kriteria/FormTambahAlternatif.php',
        data        : {id_kriteria: id_kriteria},
        success     : function(data){
            $('#FormTambahAlternatif').html(data);
            //Proses Tambah alternatif
            $('#ProsesTambahAlternatif').submit(function(){
                $('#NotifikasiTambahAlternatif').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesTambahAlternatif')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Kriteria/ProsesTambahAlternatif.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiTambahAlternatif').html(data);
                        var NotifikasiTambahAlternatifBerhasil=$('#NotifikasiTambahAlternatifBerhasil').html();
                        if(NotifikasiTambahAlternatifBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});
//Tambah Alternatif
$('#ModalEditAlternatif').on('show.bs.modal', function (e) {
    var id_alternatif = $(e.relatedTarget).data('id');
    $('#FormEditAlternatif').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kriteria/FormEditAlternatif.php',
        data        : {id_alternatif: id_alternatif},
        success     : function(data){
            $('#FormEditAlternatif').html(data);
            //Proses Edit alternatif
            $('#ProsesEditAlternatif').submit(function(){
                $('#NotifikasiEditAlternatif').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditAlternatif')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Kriteria/ProsesEditAlternatif.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditAlternatif').html(data);
                        var NotifikasiEditAlternatifBerhasil=$('#NotifikasiEditAlternatifBerhasil').html();
                        if(NotifikasiEditAlternatifBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});
//Hapus Alternatif
$('#ModalHapusAlternatif').on('show.bs.modal', function (e) {
    var id_alternatif = $(e.relatedTarget).data('id');
    $('#FormHapusAlternatif').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Kriteria/FormHapusAlternatif.php',
        data        : {id_alternatif: id_alternatif},
        success     : function(data){
            $('#FormHapusAlternatif').html(data);
            //Konfirmasi Hapus Kriteria
            $('#KonfirmasiHapusAlternatif').click(function(){
                $('#NotifikasiHapusAlternatif').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Kriteria/ProsesHapusAlternatif.php',
                    data        : {id_alternatif: id_alternatif},
                    success     : function(data){
                        $('#NotifikasiHapusAlternatif').html(data);
                        var NotifikasiHapusAlternatifBerhasil=$('#NotifikasiHapusAlternatifBerhasil').html();
                        if(NotifikasiHapusAlternatifBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});