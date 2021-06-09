            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Profil</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= site_url('beranda') ?>">Beranda</a></li>
                            <li class="breadcrumb-item active">Profil</li>
                        </ol>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <center class="m-t-30"> <img src="<?=base_url()?>_assets/material_pro/assets/images/users/3.jpg" class="img-circle" width="150" />

                                    <h4 class="card-title m-t-10" id="profil-nama">Trisia Widya</h4>

                                </center>
                            </div>
                            <div>
                                <hr>
                            </div>
                            <!-- <div class="card-body">
                               

                                    <small class="text-muted">SKPD </small>
                                    <h6 id="profil-skpd"></h6>

                                    <small class="text-muted">Bidang </small>
                                    <h6 id="profil-bidang"></h6>

                             
                                </div> -->
                            </div>
                        </div>
                        <!-- Column -->
                        <!-- Column -->
                        <div class="col-lg-8 col-xlg-9 col-md-7">
                            <div class="card">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs profile-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#password-tab" id="password-nav" role="tab">Password</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#profil-tab" id="profil-nav" role="tab">Profil</a>
                                    </li>

                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="password-tab" role="tabpanel">
                                        <div class="card-body">
                                            <form class="form-horizontal form-material" id="edit-password-form">
                                                <?php if ($this->session->userdata('is_login')): ?>
                                                    <div class="form-group">
                                                        <label class="col-md-12">Password</label>
                                                        <div class="col-md-12">
                                                            <input  type="password"
                                                            name="password"
                                                            placeholder="Password"
                                                            class="form-control form-control-line">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <button type="button" class="btn btn-success" onclick="editPassword()">Perbarui Password</button>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                            </form>
                                        </div>
                                    </div>

                                    <?php if ($this->session->userdata('author_id') == 6): ?>

                                        <div class="tab-pane" id="profil-tab" role="tabpanel">
                                            <div class="card-body">
                                                <form class="form-horizontal form-material" id="edit-profil-form">
                                                    <?php if ($this->session->userdata('is_login')): ?>
                                                        <div class="form-group">
                                                            <label class="col-md-12">Telepon</label>
                                                            <div class="col-md-12">
                                                                <input  type="text"
                                                                name="telepon"
                                                                placeholder="Telepon"
                                                                class="form-control form-control-line"
                                                                id="input-telepon"
                                                                required="">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-12">Email</label>
                                                            <div class="col-md-12">
                                                                <input  type="text"
                                                                name="email"
                                                                placeholder="Email"
                                                                class="form-control form-control-line"
                                                                id="input-email"
                                                                required="">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-12">Tanggal Lahir</label>
                                                            <div class="col-md-12">
                                                                <input type="date" name="tanggal-lahir" class="form-control" id="tgl-lahir-picker" placeholder="dd/mm/yyyy" required="">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="col-md-12">Provinsi Tempat Lahir</label>
                                                                    <div class="col-md-12">
                                                                        <select class="form-control" id="select-provinsi-lahir" onchange="setWilayah('#select-provinsi-lahir','#select-wilayah-lahir')" required="">
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="col-md-12">Kota / Kabupaten Tempat Lahir</label>
                                                                    <div class="col-md-12">
                                                                        <select name="wilayah-lahir" class="form-control" id="select-wilayah-lahir" required="">
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-12">Status Perkawinan</label>
                                                            <div class="col-md-12">
                                                                <select name="status-perkawinan" class="form-control" id="select-status-perkawinan" required="">
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-md-12">Alamat</label>
                                                            <div class="col-md-12">
                                                                <textarea name="alamat" placeholder="Alamat" id="textarea-alamat" class="form-control" rows="3" maxlength="225" required=""></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="col-md-12">Provinsi</label>
                                                                    <div class="col-md-12">
                                                                        <select class="form-control" id="select-provinsi-alamat" onchange="setWilayah('#select-provinsi-alamat','#select-wilayah-alamat')" required="">
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="col-md-12">Kota / Kabupaten</label>
                                                                    <div class="col-md-12">
                                                                        <select name="wilayah-alamat" class="form-control" id="select-wilayah-alamat" required="">
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <button type="button" class="btn btn-success" onclick="editProfil()">Perbarui Profil</button>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                </form>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="pendidikan-tab" role="tabpanel">
                                            <div class="card-body">
                                                <br>
                                                <div class="table-responsive">
                                                    <table id="pendidikan-table" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Institusi</th>
                                                                <th>Jenjang</th>
                                                                <th>Lampiran</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="pekerjaan-tab" role="tabpanel">
                                            <div class="card-body">
                                                <br>
                                                <div class="table-responsive">
                                                    <table id="pekerjaan-table" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Instansi</th>
                                                                <th>Posisi</th>
                                                                <th>Tanggal Masuk</th>
                                                                <th>Tanggal Keluar</th>
                                                                <th>Lampiran</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                    </div>
                    <!-- Row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="card-title">Log Aktivitas Akun</h3>
                                    <div class="table-responsive">
                                      <table id="log-table" class="table table-bordered table-hover">
                                        <thead>
                                          <tr>
                                            <th>No</th>
                                            <th>Keterangan</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page Content -->
            <!-- ============================================================== -->
            <div class="modal fade" id="modal-edit-pendidikan">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Riwayat Pendidikan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-material" id="edit-pendidikan-form" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="id-pendidikan-edit" name="id" disabled="">
                                <div class="form-group">
                                    <label>Nama Institusi</label>
                                    <input type="text" name="institusi" class="form-control form-control-line" id="institusi-pendidikan-edit" disabled="">
                                </div>
                                <div class="form-group">
                                    <label>Jenjang</label>
                                    <select class="form-control" name="jenjang" id="jenjang-pendidikan-edit" disabled="">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>File upload</label>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">Pilih Lampiran</span>
                                            <span class="fileinput-exists">Ganti</span>
                                            <input type="hidden">
                                            <input type="file" name="lampiran" id="lampiran-pendidikan-edit" required="">
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" id="hapus-edit-pendidikan" data-dismiss="fileinput">Hapus</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-success" onclick="editPendidikan()">Kirim</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            <div class="modal fade" id="modal-edit-pekerjaan">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Riwayat Pekerjaan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-material" id="edit-pekerjaan-form">
                                <input type="hidden" id="id-pekerjaan-edit" name="id">
                                <div class="form-group">
                                    <label>Nama Instansi</label>
                                    <input type="text" name="instansi" class="form-control form-control-line" id="instansi-pekerjaan-edit" disabled="">
                                </div>
                                <div class="form-group">
                                    <label>Posisi</label>
                                    <input type="text" name="posisi" class="form-control form-control-line" id="posisi-pekerjaan-edit" disabled="">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Masuk</label>
                                    <input type="date" name="masuk" class="form-control form-control-line" id="masuk-pekerjaan-edit" placeholder="dd/mm/yyyy" disabled="">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Keluar</label>
                                    <input type="date" name="keluar" class="form-control form-control-line" id="keluar-pekerjaan-edit" placeholder="dd/mm/yyyy" disabled="">
                                </div>
                                <div class="form-group">
                                    <label>File upload</label>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">Pilih Lampiran</span>
                                            <span class="fileinput-exists">Ganti</span>
                                            <input type="hidden">
                                            <input type="file" name="lampiran" id="lampiran-pekerjaan-edit" required="">
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" id="hapus-edit-pekerjaan" data-dismiss="fileinput">Hapus</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-success" onclick="editPekerjaan()">Kirim</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            <div class="modal fade" id="modal-edit-sertifikasi">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Riwayat Sertifikasi</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-material" id="edit-sertifikasi-form">
                                <input type="hidden" id="id-sertifikasi-edit" name="id">
                                <div class="form-group">
                                    <label>Nama Sertifikat</label>
                                    <input type="text" name="sertifikat" class="form-control form-control-line" id="sertifikat-sertifikasi-edit" disabled="">
                                </div>
                                <div class="form-group">
                                    <label>File upload</label>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">Pilih Lampiran</span>
                                            <span class="fileinput-exists">Ganti</span>
                                            <input type="hidden">
                                            <input type="file" name="lampiran" id="lampiran-sertifikasi-edit" required="">
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" id="hapus-edit-sertifikasi" data-dismiss="fileinput">Hapus</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-success" onclick="editSertifikasi()">Kirim</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
            <!-- ============================================================== -->