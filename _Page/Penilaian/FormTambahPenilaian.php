<?php
    date_default_timezone_set('Asia/Jakarta');
?>
<div class="row">
    <div class="col-md-12 mt-3">
        <label for="tanggal">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>">
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-3">
        <label for="keterangan">Keterangan</label>
        <input type="text" name="keterangan" id="keterangan" class="form-control">
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-3">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control">
            <option value="">Pilih..</option>
            <option value="Selesai">Selesai</option>
            <option value="Proses">Proses</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-3" id="NotifikasiTambahPenilaian">
        <small class="text-primary">Pastkan data yang anda input sudah benar</small>
    </div>
</div>