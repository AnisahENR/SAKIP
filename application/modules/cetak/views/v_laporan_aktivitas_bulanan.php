            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Laporan Aktivitas Bulanan</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= site_url('beranda') ?>">Beranda</a></li>
                            <li class="breadcrumb-item active">Laporan Aktivitas Bulanan</li>
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Cari Laporan Aktivitas Tenaga Harian Lepas</h4>
                                <form id="laporan-thl-form">
                                    <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="control-label">SKPD</label>
                                                    <select id="skpd-select" class="select2 form-control custom-select" style="width: 100%">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="control-label">Bidang</label>
                                                    <select id="bidang-select" class="select2 form-control custom-select" style="width: 100%">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="control-label">THL</label>
                                                    <select name="thl" id="thl-select" class="select2 form-control custom-select" style="width: 100%" required="">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="control-label">Bulan</label>
                                                    <input id="bulan-select" name="bulan" type="month" class="form-control" id="bulan-picker" value="<?php echo date("Y-m"); ?>" required="">
                                                </div>
                                            </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="control-label"> </label>
                                            <button class="btn btn-success form-control text-white" onclick="cariLaporanTHL()"> <i class="fa fa-check"></i> Cari</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Laporan Aktivitas Tenaga Harian Lepas</h4>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="control-label">SKPD</label>
                                            <input type="text" id="skpd-label" class="form-control" disabled="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="control-label">Bidang</label>
                                            <input type="text" id="bidang-label" class="form-control" disabled="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="control-label">Nama</label>
                                            <input type="text" id="nama-label" class="form-control" disabled="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="control-label">Profesi</label>
                                            <input type="text" id="profesi-label" class="form-control" disabled="">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form action="<?=site_url('export/laporan_aktivitas_bulanan_thl')?>" method="post">
                                            <input id="thl-cetak-download" type="hidden" name="thl" required="">
                                            <input id="bulan-cetak-download" type="hidden" name="bulan" required="">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Mengetahui Kolom 1</label>
                                                        <select name="kadis" id="kadis-select" class="select2 form-control custom-select" style="width: 100%" required="" disabled="">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Mengetahui Kolom 2</label>
                                                        <select name="kabid" id="kabid-select" class="select2 form-control custom-select" style="width: 100%" required="" disabled="">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Jenis Cetak</label>
                                                        <select name="jenis" id="jenis-select" class="form-control custom-select" disabled="">
                                                            <option value="0">PDF</option>
                                                            <option value="1">Word</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="control-label"> </label>
                                                        <button type="submit" class="btn btn-success form-control text-white button-download" disabled=""> <i class="fa fa-print"></i> Cetak</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Hari / Tanggal</th>
                                                <th>Aktivitas</th>

                                                <?php if ($this->session->userdata('author_id') == 1 || $this->session->userdata('author_id') == 2): ?>
                                                <th>Status</th>
                                                <?php endif; ?>

                                            </tr>
                                        </thead>
                                        <tbody id="aktivitas-table-body">
                                            <tr>

                                                <?php if ($this->session->userdata('author_id') == 1 || $this->session->userdata('author_id') == 2): ?>
                                                <td colspan="4" class="text-center">Tabel Kosong</td>
                                                <?php else: ?>
                                                    <td colspan="3" class="text-center">Tabel Kosong</td>
                                                <?php endif; ?>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->