$('#TampilkanLaporan').click(function(){
    var ProsesTampilkanLaporan = $('#ProsesTampilkanLaporan').serialize();
    $('#MenampilkanLaporan').html('Loading...');
    $.ajax({
        type 	    : 'POST',
        url 	    : '_Page/Laporan/TabelLaporan.php',
        data 	    :  ProsesTampilkanLaporan,
        success     : function(data){
            $('#MenampilkanLaporan').html(data);
        }
    });
});