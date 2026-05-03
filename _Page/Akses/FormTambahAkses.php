<div class="row">
    <div class="col-md-6 mt-3">
        <label for="nama">Nama Lengkap</label>
        <input type="text" name="nama" id="nama" class="form-control">
    </div>
    <div class="col-md-6 mt-3">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control">
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-3">
        <label for="akses">Akses</label>
        <select name="akses" id="akses" class="form-control">
            <option value="">Pilih..</option>
            <option value="HRD">HRD</option>
            <option value="Pimpinan">Pimpinan</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mt-3">
        <label for="password1">Password</label>
        <input type="password" name="password1" id="password1" class="form-control">
        <small class="credit">Password hanya boleh terdiri dari 6-20 karakter angka dan huruf</small>
    </div>
    <div class="col-md-6 mt-3">
        <label for="password2">Ulangi Password</label>
        <input type="password" name="password2" id="password2" class="form-control">
        <small class="credit">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="Tampilkan" id="TampilkanPassword" name="TampilkanPassword">
                <label class="form-check-label" for="TampilkanPassword">
                    Tampilkan Password
                </label>
            </div>
        </small>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-3" id="NotifikasiTambahAkses">
        <small class="text-primary">Pastkan data yang anda input sudah benar</small>
    </div>
</div>