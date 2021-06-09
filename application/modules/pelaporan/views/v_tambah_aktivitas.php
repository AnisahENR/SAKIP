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
                    <li class="breadcrumb-item"><a href="<?=site_url()?>Pelaporan">Pelaporan</a></li>
                    <li class="breadcrumb-item active">Tambah Aktivitas Harian</li>
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
                  <!-- End Bread crumb and right sidebar toggle -->
                  <!-- ============================================================== -->
                  <!-- ============================================================== -->
                  <!-- Start Page Content -->
                  <!-- ============================================================== -->
                  <div class="row">
                    <div class="col-md-8">
                     <div id="notifications"><?php echo $this->session->flashdata('msg'); ?></div>
                   </div>

                 </div>

                 <div class="card">
                  <div class="card-body">
                    <h2 class="card-title"><b>Tambah Laporan Aktivitas Harian </b></h2>
                    <div class="row">
                      <div class="col-12">
                       <form  action="#" id="formtambah" enctype="multipart/form-data">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <?php foreach($identitas as $key =>$value){;?>
                                <label class="control-label">Nama Pegawai</label>
                                <input type="text" id="nama_pegawai" class="form-control" value="<?php echo $value['nama'];?>" readonly="">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label">Profesi</label>
                                <input type="text" class="form-control" value="<?php echo $value['des_profesi'];?>" readonly="">
                              </div>
                            </div>

                          </div> <!-- end class row -->
                          <!-- start of row p-t-20 -->
                          <div class="row">
                           <div class="col-md-6">
                            <div class="form-group">
                              <label class="control-label">SKPD</label>
                              <input type="text" class="form-control" value="<?php echo $value['des_skpd'];?>" readonly="">
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group ">
                              <label>Bidang</label>
                              <input type="text" class="form-control" value="<?php echo $value['des_bidang'];?>" readonly="">
                            </div><br>
                          </div> 
                        </div> 
                      <?php } ?>
                      <!-- end row p-t-20 -->

                      <?php foreach($spv as $key){?>
                        <div class="row">
                         <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">Kepala Bidang</label>
                            <input type="text" class="form-control" value="<?php echo $key->kabid_nama;?>" readonly="">
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group ">
                            <label>Kepala Seksi</label>
                            <input type="text" class="form-control" value="<?php echo $key->kasie_nama;?>" readonly="">
                          </div><br>
                        </div> 
                      </div> 
                      <!-- end row p-t-20 -->
                    <?php } ?>

                    <!-- start of row p-t-20 -->
                    <div class="row p-t-20">

                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label">Tanggal Aktivitas</label>
                          <select id="kode_target_thl" name="kode_target_thl" class="form-control" style="width: 100%" required>
                            <option value=""> - Pilih Tanggal - </option>
                            <?php foreach ($tgllaporan as $key => $value) {
                              echo '<option value="'.$value['kode_target_thl'].'"> '.date('d-m-Y',strtotime($value['tgl_laporan'])).' </option>';
                            } ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group ">
                          <label>Total Waktu (Menit)</label>
                          <input type="number" class="form-control" id="waktu" name="waktu" value="0" readonly>
                        </div><br>
                      </div> 

                    </div> 
                    <!-- end of row p-t-20 -->
                    <!-- start of row p-t-20 -->
                    <div class="row p-t-20">


                    </div> 
                    <!-- end row p-t-20 -->
                    <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="ribbon ribbon-bookmark  ribbon-warning">Tambah Aktivitas Kegiatan</div>
                            <div class="row" style="padding-bottom: 2em;">
                              <div class="col-md-8">

                                <!-- <h4 class="card-title"><b>Tambah Aktivitas Kegiatan</b></h4> -->
                              </div>
                              <div class="col-md-4">
                                <button class="btn waves-effect waves-light btn-outline-primary btn_laporan" type="button" onclick="kegiatan_fields();" style="float: right;display: none;"><i class="fa fa-plus"></i>   Tambah Aktivitas</button>
                              </div>
                            </div>
                            <div id="kegiatan_fields"></div>
                          </div> <!-- end card body -->
                        </div> <!-- end card -->
                      </div> 
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="ribbon ribbon-bookmark ribbon-warning">Tambah Aktivitas Lainnya</div>
                            <div class="row" style="padding-bottom: 2em;">
                              <div class="col-md-8">

                                <!-- <h4 class="card-title"><b>Tambah Aktivitas Kegiatan</b></h4> -->
                              </div>
                              <div class="col-md-4">
                                <button class="btn waves-effect waves-light btn-outline-primary btn_laporan" type="button" onclick="lainnya_fields();" style="float: right;display: none;"><i class="fa fa-plus"></i>   Tambah Lainnya</button>
                              </div>
                              
                            </div>
                            <div id="lainnya_fields"></div>
                          </div> <!-- end card body -->
                        </div> <!-- end card -->
                      </div> 
                    </div>

                    <button type="submit" class="btn btn-block btn-primary" style="float: right;"> Submit </button>
                  </form>
                </div> <!-- end row 12 -->
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


        <script type="text/javascript">

            // $(document).ready(function () {
            //     $('#waktu').change(function() {
            //         total();
            //     }); 
            // });
            // function resetfunction() {
            //   document.getElementById("formtambah").reset();

            // }

            // function tambah_aktivitas()
            // {
             // save_method = 'update';
             // $('#updateLaporan').reset();
           //   resetfunction();
           //   $('#updateLaporan').modal('show');

           // }

         </script>

         <!-- TAMBAH LAPORAN AKTIVITAS HARIAN MODAL -->

         <script type="text/javascript">
          // $(document).ready(function () {

          // });

          // var room_kegiatan = room_lainnya = 0;
          // function kegiatan_fields() {
          //   // alert('test');
          //   room_kegiatan++;
          //   $.ajax({
          //     url: "<?php echo site_url('pelaporan/addform_kegiatan')?>" ,
          //     type: "POST",
          //     data : {room_kegiatan: room_kegiatan,kode_target_thl:$('#kode_target_thl').val() },
          //     dataType: "JSON",
          //     success: function(data){
          //       $("#kegiatan_fields").append(data.html);
          //     },
          //     error: function (jqXHR, textStatus, errorThrown)
          //     {
          //       alert('Error get data from ajax');

          //     }
          //   });
          // }
          // 
          // function remove_education_fields(rid) {
          //   $('.removeclass' + rid).remove();
          //   total_nilai();
          // }      

          // function lainnya_fields() {
          //   // alert('test');
          //   room_lainnya++;
          //   $.ajax({
          //     url: "<?php echo site_url('pelaporan/addform_lainnya')?>" ,
          //     type: "POST",
          //     data : {room_lainnya: room_lainnya},
          //     dataType: "JSON",
          //     success: function(data){
          //       $("#lainnya_fields").append(data.html);
          //     },
          //     error: function (jqXHR, textStatus, errorThrown)
          //     {
          //       alert('Error get data from ajax');

          //     }
          //   });
          // }
    

          // function remove_lainnya_fields(rid) {
          //   $('.removeclasslainnya' + rid).remove();
          //   total_nilai();
          // }

          // function total_nilai(){
          //   var nilai = 0;
          //   $('.waktu_kegiatan').each(function(){
          //     nilai +=parseInt($(this).val());
          //   });
          //   $('.waktu_lainnya').each(function(){
          //     nilai +=parseInt($(this).val());
          //   });
          //   $('#nilai').val(nilai);
          //   console.log(nilai);
          // }
        </script>