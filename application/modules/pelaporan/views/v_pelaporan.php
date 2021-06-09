            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Pelaporan</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">
                    <!--     <div class="d-flex m-t-10 justify-content-end">
                            <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                <div class="chart-text m-r-10">
                                    <h6 class="m-b-0"><small>THIS MONTH</small></h6>
                                    <h4 class="m-t-0 text-info">$58,356</h4></div>
                                    <div class="spark-chart">
                                        <div id="monthchart"></div>
                                    </div>
                                </div>
                                <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                    <div class="chart-text m-r-10">
                                        <h6 class="m-b-0"><small>LAST MONTH</small></h6>
                                        <h4 class="m-t-0 text-primary">$48,356</h4></div>
                                        <div class="spark-chart">
                                            <div id="lastmonthchart"></div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <!-- End Bread crumb and right sidebar toggle -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Start Page Content -->
                        <!-- ============================================================== -->
                        <div class="row">
                            <div class="col-md-8">
                             <div id="notifications"><?php echo $this->session->flashdata('msg'); ?></div>
                             <?=form_open('#','id="filter_submit" class="needs-validation filter_submit"');?> 
                             <div class='input-group mb-3'>
                                <input type='text' id="tanggal" name="tanggal" class="form-control daterange" value="1/<?=date('m/Y')?> - <?=date('d/m/Y')?>" />
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="ti-search" id="btn_filter"></span>
                                    </span>
                                </div>
                            </div>                        
                            <?=form_close();?>
                        </div>
                        <div class="col-md-4">
                        <?php if($this->session->userdata('flag_migration') == 1){ ?>
                            <a href="<?=base_url();?>pelaporan/view_tambah_aktivitas"> <button type="button" class="btn waves-effect waves-light btn-info" style="float: right; margin-bottom: 1.3em;"><i class="fa fa-plus"></i> Tambah Laporan Harian</button></a>
                        <?php }else{ ?>
                            <a href="#"><button type="button" class="btn waves-effect waves-light btn-danger" style="float: right; margin-bottom: 1.3em;"><i class="fa fa-window-close"></i> Tambah Target ( <?= onetable_current_data('m_flag_migration',['id' => $this->session->userdata('flag_migration')])->deskripsi ?> )</button></a>
                        <?php } ?>
                        </div>

                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title"><b>Daftar Aktivitas Harian </b></h2>
                            <div class="table-responsive m-t-40">
                                <table id="laporan_harian" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Pemberi Persetujuan</th>
                                            <th>Status</th>
                                            <th>Tanggal Aktivitas</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================== -->
                    <!-- End Page Content -->
                    <!-- ============================================================== -->
                </div>
                <!-- ============================================================== -->
                <!-- End Container fluid  -->
                <!-- ============================================================== -->
