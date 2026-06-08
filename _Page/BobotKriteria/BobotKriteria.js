$(document).ready(function () {

    $("#metode_pembobotan").change(function () {

        let metode = $(this).val();

        if (metode === "") {

            $("#TampilPerhitunganBobot").html(`
                <div class="alert alert-info">
                    Silakan pilih metode.
                </div>
            `);

            return;
        }

        $("#TampilPerhitunganBobot").html(`
            <div class="text-center p-3">
                <div class="spinner-border text-primary"></div>
                <br>
                Memproses metode ${metode}
            </div>
        `);

        $.ajax({
            url: "_Page/BobotKriteria/ProsesTampilBobot.php",
            type: "POST",
            data: {
                metode_pembobotan: metode
            },

            success: function (hasil) {
                $("#TampilPerhitunganBobot").html(hasil);
            },

            error: function () {

                $("#TampilPerhitunganBobot").html(`
                    <div class="alert alert-danger">
                        Gagal memuat data.
                    </div>
                `);

            }
        });

    });

});