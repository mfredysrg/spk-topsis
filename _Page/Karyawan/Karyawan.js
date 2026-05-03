$('#MenampilkanTabelKaryawan').html("Loading...");
$('#MenampilkanTabelKaryawan').load("_Page/Karyawan/TabelKaryawan.php");
$('#batas').change(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelKaryawan').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Karyawan/TabelKaryawan.php',
        data 	    :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelKaryawan').html(data);
        }
    });
});
$('#ProsesBatas').submit(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelKaryawan').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Karyawan/TabelKaryawan.php',
        data 	    :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelKaryawan').html(data);
        }
    });
});
$('#ProsesFilterKaryawan').submit(function(){
    var batas = $('#FilterBatas').val();
    var OrderBy = $('#OrderBy').val();
    var ShortBy = $('#ShortBy').val();
    var KeywordBy = $('#KeywordBy').val();
    var FilterKeyword = $('#FilterKeyword').val();
    $('#MenampilkanTabelKaryawan').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Karyawan/TabelKaryawan.php',
        data 	    :  {batas: batas, OrderBy: OrderBy, ShortBy: ShortBy, KeywordBy: KeywordBy, keyword: FilterKeyword},
        success     : function(data){
            $('#MenampilkanTabelKaryawan').html(data);
            $('#ModalFilterKaryawan').modal('hide');
        }
    });
});
//Tambah Karyawan
$('#ModalTambahKaryawan').on('show.bs.modal', function (e) {
    $('#FormTambahKaryawan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Karyawan/FormTambahKaryawan.php',
        success     : function(data){
            $('#FormTambahKaryawan').html(data);
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
            //Proses Tambah Karyawan
            $('#ProsesTambahKaryawan').submit(function(){
                $('#NotifikasiTambahKaryawan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesTambahKaryawan')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Karyawan/ProsesTambahKaryawan.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiTambahKaryawan').html(data);
                        var NotifikasiTambahKaryawanBerhasil=$('#NotifikasiTambahKaryawanBerhasil').html();
                        if(NotifikasiTambahKaryawanBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});

//Detail Karyawan
$('#ModalDetailKaryawan').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_karyawan = pecah[0];
    $('#FormDetailKaryawan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Karyawan/FormDetailKaryawan.php',
        data        : {id_karyawan: id_karyawan},
        success     : function(data){
            $('#FormDetailKaryawan').html(data);
        }
    });
});
//Edit Karyawan
$('#ModalEditKaryawan').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_karyawan = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormEditKaryawan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Karyawan/FormEditKaryawan.php',
        data        : {id_karyawan: id_karyawan},
        success     : function(data){
            $('#FormEditKaryawan').html(data);
            //Proses Edit Karyawan
            $('#ProsesEditKaryawan').submit(function(){
                $('#NotifikasiEditKaryawan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditKaryawan')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Karyawan/ProsesEditKaryawan.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditKaryawan').html(data);
                        var NotifikasiEditKaryawanBerhasil=$('#NotifikasiEditKaryawanBerhasil').html();
                        if(NotifikasiEditKaryawanBerhasil=="Success"){
                            $('#MenampilkanTabelKaryawan').html("Loading...");
                            $.ajax({
                                type 	    : 'POST',
                                url 	    : '_Page/Karyawan/TabelKaryawan.php',
                                data 	    :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelKaryawan').html(data);
                                    $('#ModalEditKaryawan').modal('hide');
                                    swal("Good Job!", "Edit Karyawan Berhasil!", "success");
                                }
                            });
                        }
                    }
                });
            });
        }
    });
});
//Modal Edit Password
$('#ModalEditPassword').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_karyawan = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormEditPassword').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Karyawan/FormEditPassword.php',
        data        : {id_karyawan: id_karyawan},
        success     : function(data){
            $('#FormEditPassword').html(data);
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
            //Proses EditP assword
            $('#ProsesEditPassword').submit(function(){
                $('#NotifikasiEditPassword').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditPassword')[0];
                var data = new FormData(form);
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Karyawan/ProsesEditPassword.php',
                    data 	    :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditPassword').html(data);
                        var NotifikasiEditPasswordBerhasil=$('#NotifikasiEditPasswordBerhasil').html();
                        if(NotifikasiEditPasswordBerhasil=="Success"){
                            $('#MenampilkanTabelKaryawan').html("Loading...");
                            $.ajax({
                                type 	    : 'POST',
                                url 	    : '_Page/Karyawan/TabelKaryawan.php',
                                data 	    :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelKaryawan').html(data);
                                    $('#ModalEditPassword').modal('hide');
                                    swal("Good Job!", "Edit Password Berhasil!", "success");
                                }
                            });
                        }
                    }
                });
            });
        }
    });
});
//Hapus Karyawan
$('#ModalDeleteKaryawan').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_karyawan = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormDeleteKaryawan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Karyawan/FormDeleteKaryawan.php',
        data        : {id_karyawan: id_karyawan},
        success     : function(data){
            $('#FormDeleteKaryawan').html(data);
            //Konfirmasi Hapus Karyawan
            $('#KonfirmasiHapusKaryawan').click(function(){
                $('#NotifikasiHapusKaryawan').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                $.ajax({
                    type 	    : 'POST',
                    url 	    : '_Page/Karyawan/ProsesHapusKaryawan.php',
                    data        : {id_karyawan: id_karyawan},
                    success     : function(data){
                        $('#NotifikasiHapusKaryawan').html(data);
                        var NotifikasiHapusKaryawanBerhasil=$('#NotifikasiHapusKaryawanBerhasil').html();
                        if(NotifikasiHapusKaryawanBerhasil=="Success"){
                            $.ajax({
                                type 	    : 'POST',
                                url 	    : '_Page/Karyawan/TabelKaryawan.php',
                                data 	    :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelKaryawan').html(data);
                                    $('#ModalDeleteKaryawan').modal('hide');
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
//Modal Detail Penilaian Karyawan
$('#ModalDetailPenilaianKaryawan').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_periode_penilaian = pecah[0];
    var id_karyawan = pecah[1];
    $('#FormDetailPenilaianKaryawan').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Karyawan/FormDetailPenilaianKaryawan.php',
        data        : {id_periode_penilaian: id_periode_penilaian, id_karyawan: id_karyawan},
        success     : function(data){
            $('#FormDetailPenilaianKaryawan').html(data);
        }
    });
});