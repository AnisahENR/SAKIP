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
                        <div class="d-flex m-t-10 justify-content-end">
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
                                </div>
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
                             <div class='input-group mb-3'>
                                <input type='text' class="form-control daterange" />
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="ti-search"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a href="<?=base_url();?>pelaporan/view_tambah_aktivitas"> <button type="button" class="btn waves-effect waves-light btn-info" style="float: right; margin-bottom: 1.3em;"><i class="fa fa-plus"></i> Tambah Aktivitas Harian</button></a>
                        </div>

                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title"><b>Daftar Aktivitas Harian </b></h2>
                            <div class="table-responsive m-t-40">
                                <table id="config_table" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kode Laporan</th>
                                            <th>Pemberi Persetujuan</th>
                                            <th>Tanggal Aktivitas</th>
                                            <th>CreatedAt</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- sample modal content -->
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
                                        <form method="POST" action="<?=base_url()?>pelaporan/tambah_aktivitas" enctype="multipart/form-data" id="formtambah">
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
                                                        <select class="form-control custom-select" id="kode_spv" name="kode_spv" required="">
                                                            <?php foreach ($spv as $key) {?>
                                                                <option value="<?php echo $key->kode_spv;?>"><?php echo $key->nama;?></option>

                                                            <?php  }?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div> <!-- end class row -->

                                            <!-- start of row p-t-20 -->
                                            <div class="row p-t-20">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Tanggal Aktivitas</label>
                                                        <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="tgl_laporan" id="tgl_laporan" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Total Waktu</label>
                                                        <input type="number" class="form-control" id="nilai" name="nilai" value="0" min="330">
                                                    </div><br>
                                                </div> 

                                            </div> 
                                            <!-- end of row p-t-20 -->
                                            <!-- start of row p-t-20 -->
                                            <div class="row p-t-20">

                                                    <!-- <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">KODE THL</label>
                                                            <input type="text" class="form-control" name="kode_thl" placeholder="kode_thl">
                                                        </div>
                                                    </div> -->

                                                  <!--   <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">CREATED BY</label>
                                                            <input type="text" class="form-control" name="created_by" placeholder="Created by">
                                                        </div>
                                                    </div>
                                                -->

                                            </div> 
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
                                                            <div class="row">
                                                                <div class="col-sm-6 nopadding">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Kegiatan</label>
                                                                        <select name="subkategori" class="subkategori form-control">
                                                                            <option value="0">-PILIH-</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6 nopadding">
                                                                    <div class="form-group">
                                                                     <label class="control-label">Waktu</label>
                                                                     <input type="number" class="form-control waktu" id="waktu" name="waktu[]" value="" onchange="total_nilai()" placeholder="waktu">
                                                                 </div>
                                                             </div>
                                                         </div> <!-- end row -->
                                                         <div class="row">
                                                            <div class="col-sm-6 nopadding">
                                                                <div class="form-group">
                                                                    <label class="control-label">Uraian Kegiatan</label>
                                                                    <div class="input-group">
                                                                        <textarea class="form-control" rows="3" name="uraian[]" placeholder="Uraian Kegiatan"></textarea>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Default file upload</label>
                                                                    <input type="file" class="form-control"  name="lampiran[]">
                                                                </div>
                                                            </div>

                                                        </div> <!-- end row -->

                                                        <div id="education_fields"></div>
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
            function resetfunction() {
                document.getElementById("formtambah").reset();

            }

            function tambah_aktivitas()
            {
             // save_method = 'update';
             // $('#updateLaporan').reset();
             resetfunction();
             $('#updateLaporan').modal('show');

         }

        //  function total(){
        //     var nilai =0
        //     $('#waktu').each(function(){
        //         nilai +=parseInt($(this).val());
        //     });
        //     $('#nilai').val(nilai);
        // }

    </script>

    <!-- TAMBAH LAPORAN AKTIVITAS HARIAN MODAL -->

    <script type="text/javascript">
        var room = 1;
        function education_fields() {

          room++;
          var objTo = document.getElementById('education_fields')
          var divtest = document.createElement("div");
          divtest.setAttribute("class", "form-group removeclass" + room);
          var rdiv = 'removeclass' + room;
          divtest.innerHTML = '<div class="row">'+
          '<div class="col-sm-6 nopadding">'+
          '<div class="form-group">'+
          '<label class="control-label">Kegiatan</label>'+
          '<select class="form-control custom-select" id="kegiatan_thl_id" name="kegiatan_thl_id[]">'+
          <?php foreach ($kegiatan as $key ) {?>
            '<option value="<?php echo $key->id;?>"><?php echo $key->deskripsi;?></option>'+
        <?php } ?>
        '</select>'+
        '</div>'+
        '</div>'+
        '<div class="col-sm-6 nopadding">'+
        '<div class="form-group">'+
        '<label class="control-label">Waktu</label>'+
        '<input type="number" class="form-control waktu" id="waktu" name="waktu[]" value="" onchange="total_nilai()" placeholder="waktu">'+
        '</div>'+
        '</div>'+
        '</div> <!-- end row -->'+
        '<div class="row">'+
        '<div class="col-sm-6 nopadding">'+
        '<div class="form-group">'+
        '<label class="control-label">Uraian Kegiatan</label>'+
        '<div class="input-group">'+
        '<textarea class="form-control" rows="3" name="uraian[]" placeholder="Uraian Kegiatan"></textarea>'+
        '</div>'+
        '</div>'+
        '</div>'+

        '<div class="col-sm-6">'+
        '<div class="form-group">'+
        '<label>Default file upload</label>'+
        '<input type="file" class="form-control" name="lampiran[]">'+
        '</div>'+
        '</div>'+
        '</div><button class="btn btn-danger" type="button" onclick="remove_education_fields(' + room + ');">    Hapus row</button></div></div></div></div><div class="clear"></div></row>';

        objTo.appendChild(divtest)
    }

    function remove_education_fields(rid) {
      $('.removeclass' + rid).remove();
      total_nilai();
  }

  function total_nilai(){
      var nilai = 0;
      $('.waktu').each(function(){
        nilai +=parseInt($(this).val());
    });
      $('#nilai').val(nilai);
      console.log(nilai);
  }
</script>



        <!--  <script type="text/javascript">
           $(document).ready(function() {
               var nilai =0
               $('input[name^="waktu"]').each(function(){
                nilai +=parseInt($(this).val());
            });
               $('#nilai').val(nilai);
           });
       </script> -->


       <script type="text/javascript">
          var room = 1;
          function education_fields() {

            room++;
            var objTo = document.getElementById('education_fields')
            var divtest = document.createElement("div");
            divtest.setAttribute("class", "form-group removeclass" + room);
            var rdiv = 'removeclass' + room;
            divtest.innerHTML = '<div class="row">'+
            '<div class="col-sm-6 nopadding">'+
            '<div class="form-group">'+
            '<label class="control-label">Kegiatan</label>'+
            '<select class="form-control custom-select" id="kegiatan_thl_id" name="kegiatan_thl_id[]">'+
            <?php foreach ($kegiatan as $key =>$row) {?>
              '<option value="<?php echo $row['id'];?>"><?php echo $row['deskripsi'];?></option>'+
          <?php } ?>
          '</select>'+
          '</div>'+
          '</div>'+
          '<div class="col-sm-6 nopadding">'+
          '<div class="form-group">'+
          '<label class="control-label">Waktu</label>'+
          '<input type="text" class="form-control waktu" id="waktu" name="waktu[]" value="" onchange="total_nilai()" placeholder="waktu">'+
          '</div>'+
          '</div>'+
          '</div> <!-- end row -->'+
          '<div class="row">'+
          '<div class="col-sm-6 nopadding">'+
          '<div class="form-group">'+
          '<label class="control-label">Uraian Kegiatan</label>'+
          '<div class="input-group">'+
          '<textarea class="form-control" rows="3" name="uraian[]" placeholder="Uraian Kegiatan"></textarea>'+
          '</div>'+
          '</div>'+
          '</div>'+

          '<div class="col-sm-6">'+
          '<div class="form-group">'+
          '<label>Default file upload</label>'+
          '<input type="file" class="form-control" name="lampiran[]">'+
          '</div>'+
          '</div>'+
          '</div><button class="btn btn-danger" type="button" onclick="remove_education_fields(' + room + ');">    Hapus row</button></div></div></div></div><div class="clear"></div></row>';

          objTo.appendChild(divtest)
      }

      function remove_education_fields(rid) {
        $('.removeclass' + rid).remove();
        total_nilai();
    }

    function total_nilai(){
        var nilai =0
        $('.waktu').each(function(){
          nilai +=parseInt($(this).val());
      });
        $('#nilai').val(nilai);
        console.log(nilai);
    }
</script>
