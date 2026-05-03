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