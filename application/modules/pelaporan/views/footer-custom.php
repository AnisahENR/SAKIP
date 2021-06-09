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
  $( document ).ready(function() {

      $(document).ajaxStart(function() {
          $( "#request-loader" ).show();
      });

      $( document ).ajaxStop(function() {
          $( "#request-loader" ).hide();
      });
  });
  
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
   var kode_laporan_thl = "<?php echo $this->uri->segment(2);?>";
   
   $('#laporan_harian').DataTable({
     ajax : {
      url : "<?php echo base_url('pelaporan/get_laporan')?>",
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
      url : "<?php echo base_url('pelaporan/tabel_aktivitas/')?>"+kode_laporan_thl, 
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
    responsive: true,
    ajax     : {
      url : "<?php echo base_url('pelaporan/tabel_aktivitas_lain/')?>"+kode_laporan_thl, 
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
          url  : "<?php echo site_url('pelaporan/hapus_laporan/')?>"+kode_laporan_thl,
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
          url  : "<?php echo site_url('pelaporan/hapus_detail_aktivitas/')?>"+id,
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
  }

  function  hapus_aktivitas_lain(id){

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
          url  : "<?php echo site_url('pelaporan/hapus_aktivitas_lain/')?>"+id,
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

    <!-- TAMBAH LAPORAN AKTIVITAS -->
    <script type="text/javascript">

      $(document).on('submit', '#formtambah', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Warning Message !',
          text: 'Apakah anda yakin menambah laporan harian ini?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
        }).then((result) => {
          if (result.value) {
            url = "<?php echo base_url('pelaporan/tambah_aktivitas')?>";
            var formData = new FormData(this);
            $.ajax({
              url : url,
              type: "POST",
              data: formData,
              contentType: false,
              cache: false,
              processData:false,
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
          }
        });
      });

    </script>

    <!-- TAMBAH BARU LAPORAN AKTIVITAS -->
    <script type="text/javascript">
      var room_kegiatan = room_lainnya = 0;
      function kegiatan_fields() {
        room_kegiatan++;
        $.ajax({
          url: "<?php echo site_url('pelaporan/addform_kegiatan')?>" ,
          type: "POST",
          data : {room_kegiatan: room_kegiatan,kode_target_thl:$('#kode_target_thl').val() },
          dataType: "JSON",
          success: function(data){
            $("#kegiatan_fields").append(data.html);
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






      // ================ tambah kegiatan===============
      $(document).on('submit', '#form_kegiatan', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Warning Message !',
          text: 'Apakah anda yakin menambah kegiatan laporan harian ini ?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
        }).then((result) => {
          if (result.value) {
            url = "<?php echo base_url('pelaporan/tambah_aktivitas_kegiatan')?>";
            var formData = new FormData(this);
            $.ajax({
              url : url,
              type: "POST",
              data: formData,
              contentType: false,
              cache: false,
              processData:false,
              dataType : 'JSON',
              success: function(data)
              {
                console.log(data);
                $('#total_waktu').text(data.jml_waktu);
                $('#detail_tabel').DataTable().ajax.reload();
                Swal('Berhasil', 'Tambah Laporan Kegiatan Berhasil!!','success');
                $('#add_aktivitas').modal('hide'); 
              },
              error: function (jqXHR, textStatus, errorThrown) {
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
          }
        });
      });


      // ================ tambah kegiatan lainnya ===============
      $(document).on('submit', '#form_lain', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Warning Message !',
          text: 'Apakah anda yakin menambah kegiatan lain laporan harian ini ?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
        }).then((result) => {
          if (result.value) {
            url = "<?php echo base_url('pelaporan/tambah_aktivitas_lain')?>";
            var formData = new FormData(this);
            $.ajax({
              url : url,
              type: "POST",
              data: formData,
              contentType: false,
              cache: false,
              processData:false,
              dataType : 'JSON',
              success: function(data)
              {
                console.log(data);
                $('#total_waktu').text(data.jml_waktu);
                $('#laporan_lainnya').DataTable().ajax.reload();
                Swal('Berhasil', 'Tambah Laporan Kegiatan Lain Berhasil!!','success');
                $('#add_aktivitas_lain').modal('hide'); 
              },
              error: function (jqXHR, textStatus, errorThrown) {
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
          }
        });
      });

      function lainnya_fields() {
        room_lainnya++;
        $.ajax({
          url: "<?php echo site_url('pelaporan/addform_lainnya')?>" ,
          type: "POST",
          data : {room_lainnya: room_lainnya},
          dataType: "JSON",
          success: function(data){
            $("#lainnya_fields").append(data.html);
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error get data from ajax');

          }
        });
      }

      function remove_lainnya_fields(rid) {
        $('.removeclasslainnya' + rid).remove();
        total_nilai();
      }

      function add_aktivitas_kegiatan()
      {
        // init_kegiatan();
        $('.modal-title').text('Tambah Aktivitas Kegiatan'); 
        $('#add_aktivitas').modal('show'); 
      }


      function add_aktivitas_lain()
      {
        $('.modal-title').text('Tambah Aktivitas Lainnya'); 
        $('#add_aktivitas_lain').modal('show'); 
      }

      $('#add_aktivitas').on('hidden.bs.modal', function () {
        $('#kegiatan_fields').empty();
      })

      $('#add_aktivitas_lain').on('hidden.bs.modal', function () {
        $('#lainnya_fields').empty();
      })
    </script>




