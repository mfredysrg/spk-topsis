//Modal Detail Penilaian Umkm
$('#ModalDetailPenilaianUMKM').on('show.bs.modal', function (e) {
    var GetData = $(e.relatedTarget).data('id');
    var pecah = GetData.split(",");
    var id_periode_penilaian = pecah[0];
    var id_UMKM = pecah[1];
    $('#FormDetailPenilaianUMKM').html("Loading...");
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Umkm/FormDetailPenilaianUMKM.php',
        data        : {id_periode_penilaian: id_periode_penilaian, id_UMKM: id_UMKM},
        success     : function(data){
            $('#FormDetailPenilaianUMKM').html(data);
        }
    });
});