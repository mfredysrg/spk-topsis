$(document).ready(function() {
    $('#pilih_metode').change(function() {
        var metode = $(this).val();
        
        if (metode !== "") {
            // Tampilkan animasi loading selagi sistem menghitung
            $('#AreaHitungBobot').html('<div class="text-center mt-5 mb-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted">Mengkalkulasi data dari Kadiv...</p></div>');
            
            // Proses AJAX
            $.ajax({
                type: 'POST',
                url: '_Page/BobotKriteria/ProsesTampilBobot.php',
                data: { metode: metode },
                success: function(response) {
                    $('#AreaHitungBobot').html(response);
                },
                error: function() {
                    $('#AreaHitungBobot').html('<div class="alert alert-danger mt-3">Terjadi kesalahan pada server saat memuat perhitungan.</div>');
                }
            });
        } else {
            // Reset ke tampilan awal
            $('#AreaHitungBobot').html('<div class="alert alert-info mt-3 text-center"><i class="bi bi-info-circle"></i> Silakan pilih metode pembobotan di atas untuk melihat detail perhitungan dari penilaian Kadiv.</div>');
        }
    });
});