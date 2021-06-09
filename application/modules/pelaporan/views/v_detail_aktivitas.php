          <!-- ============================================================== -->
          <!-- Container fluid  -->
          <!-- ============================================================== -->
          <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
              <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Detail Aktivitas Harian</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                  <li class="breadcrumb-item"><a href="<?=site_url()?>Pelaporan">Pelaporan</a></li>
                  <li class="breadcrumb-item active">Detail Aktivitas Harian</li>
                </ol>
              </div>
              <div class="col-md-7 col-4 align-self-center">
                <!-- <div class="d-flex m-t-10 justify-content-end">
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
                     <div class="card-title" style="background-color: #1976d2; padding: 1em; color: white; font-size: 1.5em;">
                      Identitas Pegawai
                    </div>
                    <div>
                    </div>
                    <div class="card-body"> 
                      <?php foreach ($identitas as $data) {?>
                        <!-- start row  -->
                        <div class="row">
                          <div class="col-md-12">
                            <small class="text-muted">SKPD</small>
                            <h6><?php echo $data->des_skpd;?></h6> 
                          </div>
                          <div class="col-md-12">
                            <small class="text-muted  p-t-20 db">Nama Pegawai</small>
                            <h6><?php echo $data->nama;?></h6> 

                          </div>
                        </div>
                        <!-- end row -->
                        <!-- start row  -->
                        <div class="row">
                          <div class="col-md-6">
                            <small class="text-muted p-t-20 db">Profesi </small>
                            <h6><?php echo $data->des_profesi;?></h6> 
                            
                          </div>
                          <div class="col-md-6">
                            <small class="text-muted p-t-20 db">Bidang</small>
                            <h6><?php echo $data->des_bidang;?></h6> 

                          </div>
                        </div>
                        <!-- end row -->
                        <!-- start row  -->
                        <div class="row">
                          <div class="col-md-6">
                           <small class="text-muted p-t-20 db">Tanggal Aktivitas</small>
                           <h6><?php echo date('d-m-Y',strtotime($data->tgl_laporan));?></h6>
                           

                         </div>
                         <div class="col-md-6">
                           <small class="text-muted p-t-20 db">Total Waktu (Menit)</small>
                           <h6 id="total_waktu"><b><?php echo $data->jml_waktu;?></b></h6>

                         </div>
                       </div>
                       <!-- end row -->
                       
                       <small class="text-muted p-t-20 db">Pemberi Persetujuan</small>
                       <h6>Kabid : <?php echo $data->kabid;?></h6>
                       <h6>Kasie : <?php echo $data->kasie;?></h6>
                       <br/>

                       <div class="row">
                        <div class="col-md-12">
                          <small class="text-muted">Verifikasi</small>
                          <h6><span class="badge <?= ($data->flag_migration == 1)?'badge-secondary':'badge-warning' ?>"><?= $data->migration ?></span></h6> 
                        </div>
                      </div>
                      <?php }?>
                      <!--                      <button type="button" class="btn btn-block btn-danger" onclick="update_aktivitas()">Update Aktivitas</button> -->
                      <!--   <div class="total-sales" style="height: 365px;"></div> -->
                    </div>
                  </div>
                </div>

                <div class="col-lg-8 col-xlg-9 col-md-7">
                  <div class="card">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs profile-tab" role="tablist">
                      <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#kegiatan" role="tab">Aktivitas Kegiatan</a>
                      </li>
                      <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#lain" role="tab">Aktivitas Lainnya</a>
                      </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div class="tab-pane active" id="kegiatan" role="tabpanel">
                        <div class="card-body">
                          <button type="button" class="btn waves-effect waves-light btn-info" style="float: right; margin-bottom: 1.3em;" onclick="add_aktivitas_kegiatan()"><i class="fa fa-plus"></i>  Aktivitas Kegiatan</button>
                          <div class="col-md-12">
                           <div class="table-responsive">
                            <table id="detail_tabel" class="table display table-bordered table-striped no-wrap" width="100%">
                              <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Kegiatan</th>
                                  <th>Uraian</th>
                                  <th>Waktu (Menit)</th>
                                  <th>Status</th>
                                  <th>Lampiran</th> 
                                  <th>Verifikasi</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--second tab-->
                    <div class="tab-pane" id="lain" role="tabpanel">
                      <div class="card-body">
                        <button type="button" class="btn waves-effect waves-light btn-info" style="float: right; margin-bottom: 1.3em;" onclick="add_aktivitas_lain()"><i class="fa fa-plus"></i>  Aktivitas Lain</button>
                        <div class="col-md-12">
                         <div class="table-responsive">
                          <table id="laporan_lainnya" class="table display table-bordered table-striped no-wrap" width="100%">
                            <thead>
                              <tr>
                                <th>No</th>
                                <th>Uraian</th>
                                <th>Waktu (Menit)</th>
                                <th>Status</th>
                                <th>Lampiran</th>
                                <th>Verifikasi</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                          </table>
                        </div>

                      </div>
                    </div>
                  </div>
                  <!-- end tab - pand tabel lainnya -->

                </div>
              </div>
            </div>
          </div>




          <!-- end modal -->

          <!-- start modal -->

          <div class="modal bs-example-modal-lg" id="add_aktivitas" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; ">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header" style="background-color: #2196f3">
                  <h4 class="modal-title" id="myLargeModalLabel" style="color:white;"><b>Tambah Aktivitas Harian</b></h4>

                  <!--                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #b0003a;float: right;">×</button> -->
                  <button class="btn waves-effect waves-light btn-outline-primary btn_laporan text-right" type="button" onclick="kegiatan_fields();" style="float: right;"><i class="fa fa-plus"></i>   Tambah Aktivitas</button>
                </div>
                <div class="modal-body">
                 <div class="row">
                  <div class="col-12">

                    <form action="#" enctype="multipart/form-data" id="form_kegiatan">
                      <!-- start of row p-t-20 -->

                      <!-- end row p-t-20 -->
                      <?php foreach ($identitas as $data) {?>
                        <input type="text" id="kode_target_thl" name="kode_target_thl" value="<?php echo $data->kode_target_thl;?>" hidden>
                        <input type="text" id="kode_laporan_thl" name="kode_laporan_thl" value="<?php echo $data->kode_laporan_thl;?>" hidden>
                      <?php }?>
                      <div id="kegiatan_fields"></div>
                      <div class="col-md-12">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-warning waves-effect text-right" style="float: left;"> Batal </button>
                        <button type="submit" class="btn btn-success waves-effect text-right" style="float: right;"> Simpan </button>
                      </div>
                    </form>

                  </div> <!-- end row 12 -->
                </div>

              </div> <!-- end modal body part 1 -->
              <div class="modal-footer" style="background-color: #2196f3">
                <!--   <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button> -->
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- end modal -->

        <!-- start modal -->

        <div class="modal bs-example-modal-lg" id="add_aktivitas_lain" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; ">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header" style="background-color: #2196f3">
                <h4 class="modal-title" id="myLargeModalLabel" style="color:white;"><b>Tambah Aktivitas Lain</b></h4>
                <button class="btn waves-effect waves-light btn-outline-primary btn_laporan text-right" type="button" onclick="lainnya_fields();" style="float: right;"><i class="fa fa-plus"></i>   Tambah Aktivitas Lain</button>
              </div>
              <div class="modal-body">
               <div class="row">
                <div class="col-12">

                  <form action="#" enctype="multipart/form-data" id="form_lain">
                    <!-- start of row p-t-20 -->

                    <!-- end row p-t-20 -->
                    <?php foreach ($identitas as $data) {?>
                      <!-- <input type="hidden" name="kode_thl" value="<?php echo $data->kode_thl;?>"> -->
                      <input type="hidden" name="kode_laporan_thl" value="<?php echo $data->kode_laporan_thl;?>">
                    <?php }?>
                    <div id="lainnya_fields"></div>
                    <div class="col-md-12">
                      <button type="button" data-dismiss="modal" aria-hidden="true" class="btn btn-warning waves-effect text-right" style="float: left;"> Batal </button>
                      <button type="submit" class="btn btn-success waves-effect text-right" style="float: right;"> Simpan </button>
                    </div>
                  </form>

                </div> <!-- end row 12 -->
              </div>

            </div> <!-- end modal body part 1 -->
            <div class="modal-footer" style="background-color: #2196f3">
              <!--   <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button> -->
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <!-- end modal -->

      <!-- ============================================================== -->
      <!-- End Page Content -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
