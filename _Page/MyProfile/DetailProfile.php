<div class="card mt-5">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row mt-2"> 
                    <div class="col-md-3"><dt>Nama Lengkap</dt></div>
                    <div class="col-md-9"><?php echo "$SessionNama"; ?></div>
                </div>
                <div class="row mt-2"> 
                    <div class="col-md-3"><dt>Email Akses</dt></div>
                    <div class="col-md-9"><?php echo "$SessionEmail"; ?></div>
                </div>
                <div class="row mt-2"> 
                    <div class="col-md-3"><dt>Level Akses</dt></div>
                    <div class="col-md-9 text-primary"><?php echo "$SessionAkses"; ?></div>
                </div>
                <?php if($SessionAkses=="Karyawan"){ ?>
                    <div class="row mt-2"> 
                        <div class="col-md-3"><dt>NIP</dt></div>
                        <div class="col-md-9 text-primary"><?php echo "$SessionNip"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-3"><dt>Kontak</dt></div>
                        <div class="col-md-9 text-primary"><?php echo "$SesonKontak"; ?></div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-3"><dt>Jabatan</dt></div>
                        <div class="col-md-9 text-primary"><?php echo "$SessionJabatan"; ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
        
    </div>
    <div class="card-footer">
        <small class="credit">
            <a href="index.php?Page=MyProfile&Sub=EditProfile&id_akses=<?php echo "$SessionIdAkses"; ?>" class="btn btn-sm btn-success mt-2 mb-2">
                <i class="bi bi-pencil-square"></i> Ubah Pofile
            </a>
            <a href="index.php?Page=MyProfile&Sub=ChangePassword&id_akses=<?php echo "$SessionIdAkses"; ?>" class="btn btn-sm btn-success mt-2 mb-2">
                <i class="bi bi-person-check"></i> Ganti Password
            </a>
        </small>
    </div>
</div>