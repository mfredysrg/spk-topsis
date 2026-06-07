$('#MenampilkanTabelUMKM').html("Loading...");
$('#MenampilkanTabelUMKM').load("_Page/UMKM/TabelUMKM.php");

$('#batas').change(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelUMKM').html('Loading...');
    $.ajax({
        type        : 'POST',
        url         : '_Page/UMKM/TabelUMKM.php',
        data        :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelUMKM').html(data);
        }
    });
});

$('#ProsesBatas').submit(function(){
    var ProsesBatas = $('#ProsesBatas').serialize();
    $('#MenampilkanTabelUMKM').html('Loading...');
    $.ajax({
        type        : 'POST',
        url         : '_Page/UMKM/TabelUMKM.php',
        data        :  ProsesBatas,
        success     : function(data){
            $('#MenampilkanTabelUMKM').html(data);
        }
    });
});

$('#ProsesFilterUMKM').submit(function(){
    var batas = $('#FilterBatas').val();
    var OrderBy = $('#OrderBy').val();
    var ShortBy = $('#ShortBy').val();
    var KeywordBy = $('#KeywordBy').val();
    var FilterKeyword = $('#FilterKeyword').val();
    $('#MenampilkanTabelUMKM').html('Loading...');
    $.ajax({
        type        : 'POST',
        url         : '_Page/UMKM/TabelUMKM.php',
        data        :  {batas: batas, OrderBy: OrderBy, ShortBy: ShortBy, KeywordBy: KeywordBy, keyword: FilterKeyword},
        success     : function(data){
            $('#MenampilkanTabelUMKM').html(data);
            $('#ModalFilterUMKM').modal('hide');
        }
    });
});

//Tambah Umkm
$('#ModalTambahUMKM').on('show.bs.modal', function (e) {
    $('#FormTambahUMKM').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/UMKM/FormTambahUMKM.php',
        success     : function(data){
            $('#FormTambahUMKM').html(data);
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
            //Proses Tambah Umkm
            $('#ProsesTambahUMKM').submit(function(){
                $('#NotifikasiTambahUMKM').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesTambahUMKM')[0];
                var data = new FormData(form);
                $.ajax({
                    type        : 'POST',
                    url         : '_Page/UMKM/ProsesTambahUMKM.php',
                    data        :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiTambahUMKM').html(data);
                        var NotifikasiTambahUMKMBerhasil=$('#NotifikasiTambahUMKMBerhasil').html();
                        if(NotifikasiTambahUMKMBerhasil=="Success"){
                            location.reload();
                        }
                    }
                });
            });
        }
    });
});

//Detail Umkm
$('#ModalDetailUMKM').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_UMKM = pecah[0];
    $('#FormDetailUMKM').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/UMKM/FormDetailUMKM.php',
        data        : {id_UMKM: id_UMKM},
        success     : function(data){
            $('#FormDetailUMKM').html(data);
        }
    });
});

//Edit Umkm
$('#ModalEditUMKM').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_UMKM = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormEditUMKM').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/UMKM/FormEditUMKM.php',
        data        : {id_UMKM: id_UMKM},
        success     : function(data){
            $('#FormEditUMKM').html(data);
            //Proses Edit Umkm
            $('#ProsesEditUMKM').submit(function(){
                $('#NotifikasiEditUMKM').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                var form = $('#ProsesEditUMKM')[0];
                var data = new FormData(form);
                $.ajax({
                    type        : 'POST',
                    url         : '_Page/UMKM/ProsesEditUMKM.php',
                    data        :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditUMKM').html(data);
                        var NotifikasiEditUMKMBerhasil=$('#NotifikasiEditUMKMBerhasil').html();
                        if(NotifikasiEditUMKMBerhasil=="Success"){
                            $('#MenampilkanTabelUMKM').html("Loading...");
                            $.ajax({
                                type        : 'POST',
                                url         : '_Page/UMKM/TabelUMKM.php',
                                data        :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelUMKM').html(data);
                                    $('#ModalEditUMKM').modal('hide');
                                    swal("Good Job!", "Edit UMKM Berhasil!", "success");
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
    var id_UMKM = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormEditPassword').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/UMKM/FormEditPassword.php',
        data        : {id_UMKM: id_UMKM},
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
                    type        : 'POST',
                    url         : '_Page/UMKM/ProsesEditPassword.php',
                    data        :  data,
                    cache       : false,
                    processData : false,
                    contentType : false,
                    enctype     : 'multipart/form-data',
                    success     : function(data){
                        $('#NotifikasiEditPassword').html(data);
                        var NotifikasiEditPasswordBerhasil=$('#NotifikasiEditPasswordBerhasil').html();
                        if(NotifikasiEditPasswordBerhasil=="Success"){
                            $('#MenampilkanTabelUMKM').html("Loading...");
                            $.ajax({
                                type        : 'POST',
                                url         : '_Page/UMKM/TabelUMKM.php',
                                data        :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelUMKM').html(data);
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

//Hapus Umkm
$('#ModalDeleteUMKM').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_UMKM = pecah[0];
    var keyword = pecah[1];
    var batas = pecah[2];
    var ShortBy = pecah[3];
    var OrderBy = pecah[4];
    var page = pecah[5];
    var posisi = pecah[6];
    var keyword_by = pecah[7];
    $('#FormDeleteUMKM').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/UMKM/FormDeleteUMKM.php',
        data        : {id_UMKM: id_UMKM},
        success     : function(data){
            $('#FormDeleteUMKM').html(data);
            //Konfirmasi Hapus Umkm
            $('#KonfirmasiHapusUMKM').click(function(){
                $('#NotifikasiHapusUMKM').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
                $.ajax({
                    type        : 'POST',
                    url         : '_Page/UMKM/ProsesHapusUMKM.php',
                    data        : {id_UMKM: id_UMKM},
                    success     : function(data){
                        $('#NotifikasiHapusUMKM').html(data);
                        var NotifikasiHapusUMKMBerhasil=$('#NotifikasiHapusUMKMBerhasil').html();
                        if(NotifikasiHapusUMKMBerhasil=="Success"){
                            $.ajax({
                                type        : 'POST',
                                url         : '_Page/UMKM/TabelUMKM.php',
                                data        :  {keyword: keyword, batas: batas, ShortBy: ShortBy, OrderBy: OrderBy, page: page, posisi: posisi, keyword_by: keyword_by},
                                success     : function(data){
                                    $('#MenampilkanTabelUMKM').html(data);
                                    $('#ModalDeleteUMKM').modal('hide');
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

//Modal Detail Penilaian Umkm
$('#ModalDetailPenilaianUMKM').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_periode_penilaian = pecah[0];
    var id_UMKM = pecah[1];
    $('#FormDetailPenilaianUMKM').html("Loading...");
    $.ajax({
        type        : 'POST',
        url         : '_Page/UMKM/FormDetailPenilaianUMKM.php',
        data        : {id_periode_penilaian: id_periode_penilaian, id_UMKM: id_UMKM},
        success     : function(data){
            $('#FormDetailPenilaianUMKM').html(data);
        }
    });
});