<!-- SELECT2 -->
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
<!-- Sweet-Alert  -->
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/sweetalert/sweetalert.min.js"></script>
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/sweetalert/jquery.sweet-alert.custom.js"></script>
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/moment/moment.js"></script>
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js"></script>

<!-- Date Picker Plugin JavaScript -->
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/dff/dff.js" type="text/javascript"></script>
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/daterangepicker/daterangepicker.js"></script>



<!-- MENAMPILKAN TABEL AKTIVITAS DAN DETAIL AKTIVITAS -->
<script>
  $(function() {
    $("#btn_filter").click(function(){
      $('#laporan_harian').DataTable().ajax.reload();
    });

    $('#kode_target_thl').on('change', function() {
      if(this.value){
        $('.btn_laporan').show();
      }else{
        $('#kegiatan_fields').empty();
        $('#lainnya_fields').empty();
        $('.btn_laporan').hide();
      }
      // alert( this.value );
    });

    $('.daterange').daterangepicker({
      locale: {
        format: 'DD/MM/YYYY'
      }
    });


    $(".select2").select2();
   // alert('datatable');
   var kode_laporan_thl = "<?php echo $this->uri->segment(3);?>";
   
   $('#laporan_harian').DataTable({
     ajax : {
      url : "<?php echo base_url('Pelaporan/get_laporan')?>",
      type : "POST",
      "data": function (d) {
        var frm_data = $('#filter_submit').serializeArray();
        $.each(frm_data, function(key, val) {
          d[val.name] = val.value;
        });
      }
    },
    pageLength : 10,
    columns  :  [
    { "data" : "nomor_urut"},   
    { "data" : "nama"},    
    { "data" : "kode_spv"},
    { "data" : "status"},
    { "data" : "tgl_laporan"}, 
    { "data" : "action"}
    ],
    success : function(data)
    {
      console.log(data);
    }

  });


   $('#detail_tabel').DataTable( {
    // "scrollX": true,
    responsive: true,
    ajax     : {
      url : "<?php echo base_url('Pelaporan/tabel_aktivitas/')?>"+kode_laporan_thl, 
      type : "GET",
      dataType : "json" 
    },
    columns  :  [
    { "data" : "nomor_urut"},     
    { "data" : "kegiatan_thl_id"}, 
    { "data" : "uraian"},  
    { "data" : "waktu"},
    { "data" : "status"},
    { "data" : "lampiran"},
    { "data" : "verifikasi"},
    { "data" : "action"}

    ],
    success : function(data)
    {
      console.log(data);
    }

  });

   $('#laporan_lainnya').DataTable( {
    ajax     : {
      url : "<?php echo base_url('Pelaporan/tabel_aktivitas_lain/')?>"+kode_laporan_thl, 
      type : "GET",
      dataType : "json" 
    },
    columns  :  [
    { "data" : "nomor_urut"},
    { "data" : "uraian"},       
    { "data" : "waktu"},
    { "data" : "status"},
    { "data" : "lampiran"},
    { "data" : "verifikasi"},
    { "data" : "action"}
    ],
    success : function(data)
    {
      console.log(data);
    }

  });

 });

  // $('.daterange').daterangepicker();
  //    // Date Picker
  //    jQuery('.mydatepicker, #datepicker').datepicker();
  //    jQuery('#datepicker-autoclose').datepicker({
  //     autoclose: true,
  //     todayHighlight: true
  //   });

  $('.datetime').daterangepicker({
    timePicker: true,
    timePickerIncrement: 30,
    locale: {
      format: 'MM/DD/YYYY h:mm A'
    }
  });

</script>

<!-- HAPUS LAPORAN AKTIVITAS -->
<script type="text/javascript">

  function hapus(kode_laporan_thl){
      // if(label != 1){

      //   swal("Warning message!", "Anda tidak dapat menghapus laporan ini!!!.");

      // }else{
        Swal.fire({
          title: 'Warning Message !',
          text: 'Apakah anda yakin untuk menghapus laporan ini?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url  : "<?php echo site_url('Pelaporan/hapus_laporan/')?>"+kode_laporan_thl,
              type : "POST",
              dataType : 'JSON',
              success: function(data)
              {
                if (data.error) {
                  Swal('Gagal', 'Data gagal dihapus.','error');
                }else{
                  Swal('Berhasil', 'Data berhasil di hapus.','success');
                  $('#laporan_harian').DataTable().ajax.reload();
                }
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
                alert(textStatus,errorThrown);
                console.log(textStatus);
              }
            });
          }
        });
      }

      function hapus_detail_aktivitas(id){
        // if(label == 3){

        //   swal("Warning message!", "Anda tidak dapat menghapus laporan ini!!!.");

        // }else{
          Swal.fire({
            title: 'Warning Message !',
            text: 'Apakah anda yakin untuk menghapus aktivitas in?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
          }).then((result) => {
            if (result.value) {
              $.ajax({
                url  : "<?php echo site_url('Pelaporan/hapus_detail_aktivitas/')?>"+id,
                type : "POST",
                dataType : 'JSON',
                success: function(data)
                {
                  if (data.error) {
                    Swal('Gagal', 'Data gagal dihapus.','error');
                  }else{
                    Swal('Berhasil', 'Data berhasil di hapus.','success');
                    $('#total_waktu').text(data.jml_waktu);
                    $('#detail_tabel').DataTable().ajax.reload();
                   //  setTimeout(function(){
                   //   window.location.reload();
                   // },1000);
                 // redirect('pelaporan/detail_aktivitas/'.$kode_laporan_thl);
               }

             }
             ,
             error: function (jqXHR, textStatus, errorThrown)
             {
              alert(textStatus,errorThrown);
              console.log(textStatus);
            }
          });
            }
          });
        // }
      }

      function  hapus_aktivitas_lain(id){
        // if(label == 3){

        //   swal("Warning message!", "Anda tidak dapat menghapus laporan ini!!!.");

        // }else{
          Swal.fire({
            title: 'Warning Message !',
            text: 'Apakah anda yakin untuk menghapus aktivitas lain ini?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
          }).then((result) => {
            if (result.value) {
              $.ajax({
                url  : "<?php echo site_url('Pelaporan/hapus_aktivitas_lain/')?>"+id,
                type : "POST",
                dataType : 'JSON',
                success: function(data)
                {
                  if (data.error) {
                    Swal('Gagal', 'Data gagal dihapus.','error');
                  }else{
                    Swal('Berhasil', 'Data berhasil di hapus.','success');
                    $('#total_waktu').text(data.jml_waktu);
                    $('#laporan_lainnya').DataTable().ajax.reload();
                   //  setTimeout(function(){
                   //   window.location.reload();
                   // },1000);
                  }

                }
                ,
                error: function (jqXHR, textStatus, errorThrown)
                {
                  alert(textStatus,errorThrown);
                  console.log(textStatus);
                }
              });
            }
          });
        // }
      }

    </script>

    <!-- UPDATE LAPORAN AKTIVITAS -->

    <script type="text/javascript">

      function update_aktivitas(){
        $.ajax({
          url: "<?php echo site_url('pelaporan/det_identitas_pelapor_andtabel')?>" ,
          type: "POST",
          data : {kode_laporan_thl: "<?php echo $this->uri->segment(3);?>" },
          dataType: "JSON",
          success: function(data){
            $('#nama_pegawai').val(data[0].nama);
            // $('#kode_spv').val(data[0].kode_spv);
            $('#tgl_laporan').val(data[0].tgl_laporan);
            var html='';
            $.each(data.data, function(k, b) {
              html +='<div class="row">'+
              '<div class="col-sm-6 nopadding">'+
              '<div class="form-group">'+
              '<label class="control-label">Kegiatan</label>'+
              '<select class="form-control custom-select" id="kegiatan_thl_id" name="kegiatan_thl_id[]">'+
              '<option value="1">Analisis Kebutuhan</option>'+
              '<option value="2">Perancangan Database</option>'+
              '<option value="3">Pembuatan Database</option>'+
              '<option value="4">Implementasi Kode</option>'+
              '<option value="5">Pengujian Antarmuka</option>'+
              '<option value="6">Pengujian Aplikasi</option>'+
              '</select>'+
              '</div>'+
              '</div>'+
              '<div class="col-sm-6 nopadding">'+
              '<div class="form-group">'+
              '<label class="control-label">Waktu</label>'+
              '<input type="text" class="form-control waktu" id="waktu" value="'+b.waktu+'" name="waktu[]" value="" onchange="total()" placeholder="waktu">'+
              '</div>'+
              '</div>'+
              '</div> '+
              '<div class="row">'+
              '<div class="col-sm-6 nopadding">'+
              '<div class="form-group">'+
              '<label class="control-label">Uraian Kegiatan</label>'+
              '<div class="input-group">'+
              '<textarea class="form-control" rows="3" name="uraian[]"  placeholder="Uraian Kegiatan">'+b.uraian+'</textarea>'+
              '</div>'+
              '</div>'+
              '</div>'+
              '<div class="col-sm-6">'+
              '<div class="form-group">'+
              '<label>Default file upload</label>'+
              '<input type="file" class="form-control" name="lampiran2[]" value="'+b.lampiran+'">'+
              '<input type="file" class="form-control" name="lampiran[]">'+
              '</div>'+
              '</div>'+
              '</div>';
            });

            $('#form-kegiatan').html(html);
            $('#updateLaporan').modal('show');
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error get data from ajax');

          }
        });
      }

      function remove_education_fields(rid) {
        $('.removeclass' + rid).remove();
        total_nilai();
      }

      function total_nilai(){
        var nilai = 0;
        $('.waktu_kegiatan').each(function(){
          nilai +=parseInt($(this).val());
        });
        $('.waktu_lainnya').each(function(){
          nilai +=parseInt($(this).val());
        });
        $('#waktu').val(nilai);
        // console.log(nilai);
      }


    </script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('#tgl_laporan').change(function(){
     // $('#tgl_laporan').on('change', 'tgl_laporan' , function(){
      var id=$(this).val();
      $.ajax({
        url : "<?php echo base_url();?>pelaporan/get_kegiatan_thl",
        method : "POST",
        data : {id: id},
        async : false,
        dataType : 'json',
        success: function(data){
          var html = '';
          var i;
          for(i=0; i<data.length; i++){
           // html += '<option>'+data[i].subkategori_nama+'</option>';
           html += '<option value='+data[i].id+'>'+data[i].deskripsi+'</option>';
         }
         $('.subkategori').html(html);

       }
     });
    });
      });
    </script>

    <!-- TAMBAH LAPORAN AKTIVITAS -->
    <script type="text/javascript">

      $(document).on('submit', '#formtambah', function(e) {
        e.preventDefault();
        url = "<?php echo base_url('pelaporan/tambah_aktivitas')?>";
        var formData = new FormData(this);
        $.ajax({
          url : url,
          type: "POST",
          data: formData,
          contentType: false,
          cache: false,
          processData:false,
          type: 'POST', 
          success: function(data)
          {

            swal({
              title: 'Success Message',
              text: 'Tambah laporan berhasil!',
              icon: 'success',
              button: {
                text: "Lanjutkan",
                value: true,
                visible: true,
                className: "btn btn-primary"
              } 
            }).then(function() {
              window.location = "pelaporan";
            });
            
          },
          error: function (jqXHR, textStatus, errorThrown) {
         // alert(errorThrown);
         swal({
          title: jqXHR.responseText,
          icon: 'error',
          button: {
            text: "Tutup",
            value: true,
            visible: true,
            className: "btn btn-info"
          }
        });
       }
     });
      });

    </script>

    <!-- UPDATE LAPORAN AKTIVITAS -->
    <script type="text/javascript">
      function update_kegiatan(id)
      {
        save_method = 'update';
     // $('#form_update')[0].reset(); // reset form on modals

      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('pelaporan/data_update_kegiatan/')?>"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          $('[name="kode_laporan_thl"]').val(data.kode_laporan_thl);
          //$('[name="kegiatan_thl_id"]').val(data.kegiatan_thl_id);
          $('[name="stat_laporan_id"]').val(data.stat_laporan_id);
          $('[name="waktu"]').val(data.waktu);
          $('[name="uraian"]').val(data.uraian);
          $('[name="lampiran_lama"]').val(data.lampiran);
          $('#kegiatan_thl_id option:selected').val(data.kegiatan_thl_id);
          
          
            $('#update_aktivitas').modal('show'); // show bootstrap modal when complete loaded
            console.log(data);
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error get data from ajax');

          }
        });
    }

    function update_aktivitas_lain(id)
    {
      save_method = 'update';
     // $('#form_update')[0].reset(); // reset form on modals

      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('pelaporan/update_aktivitas_lain/')?>"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          $('[name="kode_laporan_thl"]').val(data.kode_laporan_thl);
          //$('[name="kegiatan_thl_id"]').val(data.kegiatan_thl_id);
          $('[name="stat_laporan_id"]').val(data.stat_laporan_id);
          $('[name="waktu"]').val(data.waktu);
          $('[name="uraian"]').val(data.uraian);
          $('[name="lampiran_lama"]').val(data.lampiran);
         // $('#kegiatan_thl_id option:selected').val(data.kegiatan_thl_id);
         
         
            $('#update_aktivitas_lain').modal('show'); // show bootstrap modal when complete loaded
            console.log(data);
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error get data from ajax');

          }
        });
    }
  </script>
  <!-- TAMBAH LAPORAN AKTIVITAS -->
  <script type="text/javascript">

    function add_aktivitas_lain()
    {
      save_method = 'add';
    $('#form_lain')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#add_aktivitas_lain').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Aktivitas Lain'); // Set Title to Bootstrap modal title
  }

  function add_aktivitas_kegiatan()
  {
    save_method = 'add';
    $('#form_kegiatan')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#add_aktivitas').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Aktivitas Lain'); // Set Title to Bootstrap modal title
  }
</script>




