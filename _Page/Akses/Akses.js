$('#MenampilkanTabelAkses').html("Loading...");
$('#MenampilkanTabelAkses').load("_Page/Akses/TabelAkses.php");
$('#batas').change(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelAkses').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Akses/TabelAkses.php',
        data 	    :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelAkses').html(data);
        }
    });
});
$('#ProsesBatas').submit(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelAkses').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Akses/TabelAkses.php',
        data 	    :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelAkses').html(data);
        }
    });
});
$('#ProsesFilterAkses').submit(function(){
    var batas = $('#FilterBatas').val();
    var OrderBy = $('#OrderBy').val();
    var ShortBy = $('#ShortBy').val();
    var KeywordBy = $('#KeywordBy').val();
    var FilterKeyword = $('#FilterKeyword').val();
    $('#MenampilkanTabelAkses').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Akses/TabelAkses.php',
        data 	    :  {batas: batas, OrderBy: OrderBy, ShortBy: ShortBy, KeywordBy: KeywordBy, keyword: FilterKeyword},
        success     : function(data){
            $('#MenampilkanTabelAkses').html(data);
            $('#ModalFilterAkses').modal('hide');
        }
    });
});
//Tambah Akses
$('#ModalTambahAkses').on('show.bs.modal', function (e) {
    $('#FormTambahAkses').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Akses/FormTambahAkses.php',
        success     : function(data){
            $('#FormTambahAkses').html(data);
            //Kondisi saat tampilkan password
            $('.form-check-input').click(function(){
                if($(this).is(':checked')){
                    $('#password1').attr('type','text');
                    $('#password2').attr('type','text');
                }else{
                    $('#password1').attr('type','password');
                    $('#password2').attr('type','password');
                }
            });
            //Proses Tambah Akses
            $('#ProsesTambahAkses').submit(function(){
                $('#NotifikasiTambahAkses').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesTambahAkses')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Akses/ProsesTambahAkses.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiTambahAkses').html(data);
                        var NotifikasiTambahAksesBerhasil=$('#NotifikasiTambahAksesBerhasil').html();
                        if(NotifikasiTambahAksesBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});

//Detail Akses
$('#ModalDetailAkses').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_akses = pecah[0];
    $('#FormDetailAkses').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Akses/FormDetailAkses.php',
        data        : {id_akses: id_akses},
        success     : function(data){
            $('#FormDetailAkses').html(data);
        }
    });
});
//Edit Akses
$('#ModalEditAkses').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_akses = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormEditAkses').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Akses/FormEditAkses.php',
        data        : {id_akses: id_akses},
        success     : function(data){
            $('#FormEditAkses').html(data);
            //Kondisi saat tampilkan password
            $('#TampilkanPassword2').click(function(){
                if($(this).is(':checked')){
                    $('#password1_edit').attr('type','text');
                    $('#password2_edit').attr('type','text');
                }else{
                    $('#password1_edit').attr('type','password');
                    $('#password2_edit').attr('type','password');
                }
            });
            //Kondisi id_mitra dipilih
            $('#id_mitra_edit').change(function(){
                var id_mitra_edit = $('#id_mitra_edit').val();
                if(id_mitra_edit==""){
                    $('#akses_edit').html('<option value="Admin">Admin</option><option value="">More Access Groups</option>');
                }else{
                    $("#grup_akses_edit").val("");
                    $("#grup_akses_edit").prop("disabled", true);
                    $('#akses_edit').load('_Page/Akses/PilihAksesMitra.php');
                }
            });
            //Kondisi ketika akses dipilih
            $('#akses_edit').change(function(){
                var akses_edit = $('#akses_edit').val();
                if(akses_edit==""){
                    $("#grup_akses_edit").prop("disabled", false);
                }else{
                    $("#grup_akses_edit").prop("disabled", true);
                }
            });
            //Proses Tambah Akses
            $('#ProsesEditAkses').submit(function(){
                $('#NotifikasiEditAkses').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditAkses')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Akses/ProsesEditAkses.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditAkses').html(data);
                        var NotifikasiEditAksesBerhasil=$('#NotifikasiEditAksesBerhasil').html();
                        if(NotifikasiEditAksesBerhasil=="Success"){
                            $('#MenampilkanTabelAkses').html("Loading...");
                            $.ajax({
                                type 	    : 'POST',
                                url 	    : '_Page/Akses/TabelAkses.php',
                                data 	    :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelAkses').html(data);
                                    $('#ModalEditAkses').modal('hide');
                                    swal("Good Job!", "Edit Access Success!", "success");
                                }
                            });
                        }
                    }
                });
            });
        }
    });
});
//Hapus Akses
$('#ModalDeleteAkses').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_akses = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormDeleteAkses').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Akses/FormDeleteAkses.php',
        data        : {id_akses: id_akses},
        success     : function(data){
            $('#FormDeleteAkses').html(data);
            //Konfirmasi Hapus akses
            $('#KonfirmasiHapusAkses').click(function(){
                $('#NotifikasiHapusAkses').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Akses/ProsesHapusAkses.php',
                    data        : {id_akses: id_akses},
                    success     : function(data){
                        $('#NotifikasiHapusAkses').html(data);
                        var NotifikasiHapusAksesBerhasil=$('#NotifikasiHapusAksesBerhasil').html();
                        if(NotifikasiHapusAksesBerhasil=="Success"){
                            $.ajax({
                                type 	    : 'POST',
                                url 	    : '_Page/Akses/TabelAkses.php',
                                data 	    :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelAkses').html(data);
                                    $('#ModalDeleteAkses').modal('hide');
                                    swal("Good Job!", "Delete Access Success!", "success");
                                }
                            });
                        }
                    }
                });
            });
        }
    });
});
