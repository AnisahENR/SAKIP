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
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->

                <!-- Row -->
                <div class="row">
                  <!-- Column -->
                  <div class="col-lg-3 col-xlg-3 col-md-5">
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
                          <div class="col-md-6">
                            <small class="text-muted">Nama Pegawai </small>
                            <h6><?php echo $data->nama;?></h6> 

                          </div>
                          <div class="col-md-6">
                            <small class="text-muted">Profesi</small>
                            <h6><?php echo $data->des_profesi;?></h6> 

                          </div>
                        </div>
                        <!-- end row -->
                        <!-- start row  -->
                        <div class="row">
                          <div class="col-md-6">
                            <small class="text-muted p-t-30 db">SKPD </small>
                            <h6><?php echo $data->des_skpd;?></h6> 
                            
                          </div>
                          <div class="col-md-6">
                            <small class="text-muted p-t-30 db">Bidang</small>
                            <h6><?php echo $data->des_bidang;?></h6> 

                          </div>
                        </div>
                        <!-- end row -->
                        <!-- start row  -->
                        <div class="row">
                          <div class="col-md-6">
                           <small class="text-muted p-t-30 db">Tanggal Aktivitas</small>
                           <h6><?php echo date('d-m-Y',strtotime($data->tgl_laporan));?></h6>
                           

                         </div>
                         <div class="col-md-6">
                           <small class="text-muted p-t-30 db">Total Waktu</small>
                           <h6 id="total_waktu"><b><?php echo $data->jml_waktu;?></b></h6>

                         </div>
                       </div>
                       <!-- end row -->
                       
                       <small class="text-muted">Pemberi Persetujuan</small>
                       <h6>Kabid : <?php echo $data->kabid;?></h6>
                       <h6>Kasie : <?php echo $data->kasie;?></h6>
                       <br/>
                     <?php }?>
                     <!-- <button type="button" class="btn btn-block btn-danger" onclick="update_aktivitas()">Update Aktivitas</button> -->
                     <!--   <div class="total-sales" style="height: 365px;"></div> -->
                   </div>
                 </div>
               </div>

               <div class="col-lg-9 col-xlg-9 col-md-7">
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
                                <th>Kegiatan THL</th>
                                <th>Uraian</th>
                                <th>Waktu</th>
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
                              <th>Waktu</th>
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



        <!-- start modal -->

        <div class="modal bs-example-modal-lg" id="updateLaporan" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; ">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header" style="background-color: #2196f3">
                <h4 class="modal-title" id="myLargeModalLabel" style="color:white;"><b>Tambah Aktivitas Harian</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #b0003a;">×</button>
              </div>
              <div class="modal-body">
               <div class="row">
                <div class="col-12">
                  <form method="POST" action="<?=base_url()?>pelaporan/tambah_aktivitas" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Nama Pegawai</label>
                          <input type="text" id="nama_pegawai" class="form-control" placeholder="Al Madinah Zahira Arinal Haq" readonly="">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Pemberi Persetujuan</label>
                          <select class="form-control custom-select" id="kode_spv" name="kode_spv">
                            <option value="131313">AAAAAAAAAAAAA</option>
                            <option value="141414">BBBBBBBBBBBBB</option>
                            <option value="151515">CCCCCCCCCCCCC</option>
                            <option value="161616">DDDDDDDDDDDDD</option>
                            <option value="171717">EEEEEEEEEEEEE</option>
                            <option value="181818">FFFFFFFFFFFFF</option>
                          </select>
                        </div>
                      </div>

                    </div> <!-- end class row -->

                    <!-- start of row p-t-20 -->
                    <div class="row p-t-20">

                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Tanggal Aktivitas</label>
                          <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="tgl_laporan" id="tgl_laporan" readonly="">
                        </div>
                      </div>

                    </div> 
                    <!-- end of row p-t-20 -->
                    <!-- start of row p-t-20 -->

                    <!-- end row p-t-20 -->
                    <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="row" style="padding-bottom: 2em;">
                              <div class="col-md-8">
                                <h4 class="card-title"><b>Tambah Aktivitas Kegiatan</b></h4>
                              </div>
                              <div class="col-md-4">
                                <button class="btn waves-effect waves-light btn-outline-primary" type="button" onclick="education_fields();" style="float: right;"><i class="fa fa-plus"></i>   Tambah Aktivitas</button>
                              </div>
                            </div>
                            <div id="form-kegiatan"></div>   
                            <div id="education_fields"></div>
                          </div> <!-- end card body -->
                        </div> <!-- end card -->
                      </div> 
                    </div>
                    <div class="form-group ">
                      <label>Total Waktu</label>
                      <input type="number" class="form-control" id="nilai" name="nilai" min="330">
                    </div><br>
                    <button type="submit" class="btn btn-danger waves-effect text-left" style="float: right;"> Submit </button>
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

      <div class="modal bs-example-modal-lg" id="add_aktivitas" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; ">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #2196f3">
              <h4 class="modal-title" id="myLargeModalLabel" style="color:white;"><b>Tambah Aktivitas Harian</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #b0003a;">×</button>
            </div>
            <div class="modal-body">
             <div class="row">
              <div class="col-12">

                <form method="POST" action="<?=base_url()?>pelaporan/tambah_aktivitas_kegiatan" enctype="multipart/form-data" id="form_kegiatan">
                  <!-- start of row p-t-20 -->

                  <!-- end row p-t-20 -->
                  <?php foreach ($identitas as $data) {?>
                    <input type="text" name="kode_thl" value="<?php echo $data->kode_thl;?>">
                    <input type="text" name="kode_laporan_thl" value="<?php echo $data->kode_laporan_thl;?>">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="row" style="padding-bottom: 2em;">
                              <div class="col-md-8">
                                <h4 class="card-title"><b>Tambah Aktivitas Kegiatan</b></h4>
                              </div>
                            </div>   
                            <div class="row">
                              <div class="col-sm-6 nopadding">
                                <div class="form-group">
                                  <label class="control-label">Kegiatan</label>
                                  <select class="subkategori form-control" id="kegiatan_thl_id" name="kegiatan_thl_id">
                                    <?php foreach ($kegiatan as $key => $row) {?>
                                      <option value="<?php echo $row['id'];?>"><?php echo $row['deskripsi'];?></option>
                                    <?php  }?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-sm-6 nopadding">
                                <div class="form-group">
                                  <label class="control-label">Uraian Kegiatan</label>
                                  <div class="input-group">
                                    <textarea class="form-control" rows="3" name="uraian" placeholder="Uraian Kegiatan"></textarea>
                                  </div>
                                </div>
                              </div>

                            </div> <!-- end row -->
                            <div class="row">
                             <div class="col-sm-6 nopadding">
                              <div class="form-group">
                               <label class="control-label">Waktu</label>
                               <input type="number" class="form-control waktu" id="waktu" name="waktu" value="" onchange="total_nilai()" placeholder="waktu">
                             </div>
                           </div>
                           <div class="col-sm-6 nopadding">
                            <div class="form-group">
                              <label>File upload</label>
                              <input type="file" class="form-control"  name="lampiran">
                            </div>
                          </div>

                        </div> <!-- end row -->
                      </div> <!-- end card body -->
                    </div> <!-- end card -->
                  </div> 
                </div>
              <?php }?>
              <button type="submit" class="btn btn-danger waves-effect text-left" style="float: right;"> Submit </button>
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

<div class="modal bs-example-modal-lg" id="update_aktivitas" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; ">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #2196f3">
        <h4 class="modal-title" id="myLargeModalLabel" style="color:white;"><b>Tambah Aktivitas Harian</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #b0003a;">×</button>
      </div>
      <div class="modal-body">
       <div class="row">
        <div class="col-12">
          <form action="<?=base_url()?>pelaporan/tambah_aktivitas_kegiatan" enctype="multipart/form-data" id="form_update">
            <input type="text" name="kode_thl">
            <input type="text" name="kode_laporan_thl">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="row" style="padding-bottom: 2em;">
                      <div class="col-md-8">
                        <h4 class="card-title"><b>Tambah Aktivitas Kegiatan</b></h4>
                      </div>
                    </div>   
                    <div class="row">
                      <div class="col-sm-6 nopadding">
                        <div class="form-group">
                          <label class="control-label">Kegiatan</label>
                          <select class="subkategori form-control" id="kegiatan_thl_id" name="kegiatan_thl_id">
                           <?php foreach ($kegiatan as $key => $row) {?>
                            <option value="<?php echo $row['id'];?>"><?php echo $row['deskripsi'];?></option>
                          <?php  }?>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-6 nopadding">
                      <div class="form-group">
                        <label class="control-label">Uraian Kegiatan</label>
                        <div class="input-group">
                          <textarea class="form-control" rows="3" name="uraian" placeholder="Uraian Kegiatan"></textarea>
                        </div>
                      </div>
                    </div>

                  </div> <!-- end row -->
                  <div class="row">
                   <div class="col-sm-6 nopadding">
                    <div class="form-group">
                     <label class="control-label">Waktu</label>
                     <input type="number" class="form-control waktu" id="waktu" name="waktu" placeholder="waktu">
                   </div>
                 </div>
                 <div class="col-sm-6 nopadding">
                  <div class="form-group">
                    <label>File upload</label>
                    <input type="text" class="form-control"  name="lampiran_lama">
                    <input type="file" class="form-control"  name="lampiran">
                  </div>
                </div>
              </div> <!-- end row -->
            </div> <!-- end card body -->
          </div> <!-- end card -->
        </div> 
      </div>

      <button type="submit" class="btn btn-danger waves-effect text-left" style="float: right;"> Submit </button>
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
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #b0003a;">×</button>
      </div>
      <div class="modal-body">
       <div class="row">
        <div class="col-12">

          <form method="POST" action="<?=base_url()?>pelaporan/tambah_aktivitas_lain" enctype="multipart/form-data" id="form_lain">
            <!-- start of row p-t-20 -->

            <!-- end row p-t-20 -->
            <?php foreach ($identitas as $data) {?>
              <input type="hidden" name="kode_thl" value="<?php echo $data->kode_thl;?>">
              <input type="hidden" name="kode_laporan_thl" value="<?php echo $data->kode_laporan_thl;?>">
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="row" style="padding-bottom: 2em;">
                        <div class="col-md-8">
                          <h4 class="card-title"><b>Tambah Aktivitas Lain</b></h4>
                        </div>
                      </div>   
                      <div class="row">
                        <div class="col-sm-6 nopadding">
                          <div class="form-group">
                            <label class="control-label">Uraian Kegiatan</label>
                            <div class="input-group">
                              <textarea class="form-control" rows="3" name="uraian" placeholder="Uraian Kegiatan"></textarea>

                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6 nopadding">
                          <div class="form-group">
                           <label class="control-label">Waktu</label>
                           <input type="number" class="form-control waktu" id="waktu" name="waktu" value="" placeholder="waktu">
                         </div>
                       </div>
                     </div> <!-- end row -->
                     <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>File upload</label>
                          <input type="text" class="form-control"  name="lampiran_lama">
                          <input type="file" class="form-control"  name="lampiran">
                        </div>
                      </div>

                    </div> <!-- end row -->
                  </div> <!-- end card body -->
                </div> <!-- end card -->
              </div> 
            </div>
          <?php }?>
          <button type="submit" class="btn btn-danger waves-effect text-left" style="float: right;"> Submit </button>
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

<div class="modal bs-example-modal-lg" id="update_aktivitas_lain" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; ">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #2196f3">
        <h4 class="modal-title" id="myLargeModalLabel" style="color:white;"><b>Tambah Aktivitas Lain</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #b0003a;">×</button>
      </div>
      <div class="modal-body">
       <div class="row">
        <div class="col-12">

          <form method="POST" action="<?=base_url()?>pelaporan/tambah_aktivitas_lain" enctype="multipart/form-data" id="form_lain">
            <!-- start of row p-t-20 -->

            <!-- end row p-t-20 -->
            <?php foreach ($identitas as $data) {?>
              <input type="hidden" name="kode_thl" value="<?php echo $data->kode_thl;?>">
              <input type="hidden" name="kode_laporan_thl" value="<?php echo $data->kode_laporan_thl;?>">
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="row" style="padding-bottom: 2em;">
                        <div class="col-md-8">
                          <h4 class="card-title"><b>Tambah Aktivitas Lain</b></h4>
                        </div>
                      </div>   
                      <div class="row">
                        <div class="col-sm-6 nopadding">
                          <div class="form-group">
                            <label class="control-label">Uraian Kegiatan</label>
                            <div class="input-group">
                              <textarea class="form-control" rows="3" name="uraian" placeholder="Uraian Kegiatan"></textarea>

                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6 nopadding">
                          <div class="form-group">
                           <label class="control-label">Waktu</label>
                           <input type="number" class="form-control waktu" id="waktu" name="waktu" value="" placeholder="waktu">
                         </div>
                       </div>
                     </div> <!-- end row -->
                     <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>File upload</label>
                          <input type="text" class="form-control"  name="lampiran_lama">
                          <input type="file" class="form-control"  name="lampiran">
                        </div>
                      </div>

                    </div> <!-- end row -->
                  </div> <!-- end card body -->
                </div> <!-- end card -->
              </div> 
            </div>
          <?php }?>
          <button type="submit" class="btn btn-danger waves-effect text-left" style="float: right;"> Submit </button>
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
