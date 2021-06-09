<!-- This is data table -->
<script src="<?=site_url();?>_assets/material_pro/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=site_url();?>_assets/material_pro/assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
<!-- This is tost plugin -->
<script src="<?=site_url();?>_assets/material_pro/assets/plugins/toast-master/js/jquery.toast.js"></script>
<!-- SELECT2 -->
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>


<!-- Sweet-Alert  -->
<script src="<?=site_url();?>_assets/material_pro/assets/plugins/sweetalert/sweetalert.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('.select2').select2();
		// $('#collapseadd').collapse('show');
		// $('#modal-detail-admin').modal('show');
	});

	// FUNCTION GET LIST DATA
	$('#tabel-admin').DataTable({
		ajax : {
			 url: '<?=site_url();?>admin/get_admin',  
			type : "GET",
			dataType : "json",
		},
		responsive: true,
		pageLength : 10,
		columns : [
		{ "data" : "nomor_urut"},
		{ "data" : "nama_skpd"},
		{ "data" : "nama"},
		{ "data" : "username"},
		{ "data" : "bidang"},
		{ "data" : "status_akun", className: "text-center"},
		{ "data" : "jabatan"},
		{ "data" : "opsi", className: "text-center", width:'100px'}
		]
	});

	// FUNCTION BATAL TAMBAH (RESET)
	$(document).on("click", "#btn-collapseAdd", function(){
		$("#form-add-admin")[0].reset();
		$("#small-check").html('');
	    $('#form-check-user').removeClass('has-success');
	    $('#input-check').removeClass('form-control-success');
	    $('.select2').val('').trigger('change');
	    $("#skpd_id").empty();
		$('#collapseadd').collapse('show');
		getListSkpd();
		
	});

	function getListSkpd(){
		$.ajax({
			url : "<?php echo base_url('admin/get_listSkpd')?>",
			type : "GET",
			dataType : 'JSON',
			success : function(data){
			 	$("#skpd_id").append("<option value=''>Pilih SKPD</option>");
				$.each(data.data, function (a, b) {
		            $("#skpd_id").append("<option value='"+b.id+"' >"+b.deskripsi+"</option>");
		        });


			}
		});
	}

	// FUNCTION GET SKPD WHERE SKPD
	$('#skpd_id').on('change', function() {
		$("#bidang_skpd_id").empty();
		$.ajax({
			url : "<?php echo base_url('admin/getbidang_basedskpd')?>",
			type : "POST",
			data : {skpd_id:this.value},
			dataType : 'JSON',
			success : function(data){
			 	$("#bidang_skpd_id").append("<option value=''>Pilih Bidang</option>");
				$.each(data.data, function (a, b) {
		            $("#bidang_skpd_id").append("<option value='"+b.id+"' >"+b.deskripsi+"</option>");
		        });
			}
		});
	});

	// FUNCTION ADD admin
	$('#form-add-admin').on('submit', function(e){
	  e.preventDefault();
	  $.ajax({
	    url: '<?=site_url();?>admin/add_admin',              
	    type: 'POST',
	    data: $('#form-add-admin').serialize(),
	    dataType : 'json',
	    success : function(data){
	      	if (!data.error) {
		        $.toast({
		            heading: 'Berhasil',
		            text: data.pesan,
		            position: 'top-right',
		            loaderBg:'#ff6849',
		            icon: 'info',
		            hideAfter: 3000, 
		            stack: 6
		        });
		        $('#tabel-admin').DataTable().ajax.reload();
		        $("#form-add-admin")[0].reset();
				$("#small-check").html('');
			    $('#form-check-user').removeClass('has-success');
			    $('#input-check').removeClass('form-control-success');
			    $('.select2').val('').trigger('change');
				$('#collapseadd').collapse('hide');
		        
		    }else{
		    	$.toast({
		            heading: 'Terjadi Kesalahan',
		            text: data.pesan,
		            position: 'top-right',
		            loaderBg:'#ff6849',
		            icon: 'error',
		            hideAfter: 3000, 
		            stack: 6
	          	});
		    };
	    },
        error: function(jqXHR, textStatus, errorThrown){
        	$.toast({
	            heading: 'Periksa lagi !',
	            text: 'Terjadi kesalahan saat menambah data',
	            position: 'top-right',
	            loaderBg:'#ff6849',
	            icon: 'error',
	            hideAfter: 3000, 
	            stack: 6
	        });
        }
	  });                
	});

	// FUNCTION GET DATA IN MODAL EDIT
	$(document).on("click", ".btn-detail-author", function(e){
	  	e.preventDefault();
		$("#bidang_skpd").empty();
		$('#kode_spv').val($(this).attr('data-kode'));

	  	$.ajax({
			url : "<?php echo base_url('admin/get_det_admin')?>",
			type : "POST",
			data : {skpd_id:$(this).attr('data-skpd-id'), kode: $(this).attr('data-kode')},
			dataType : 'JSON',
			success : function(data){
				console.log(data);
			 	$("#bidang_skpd").append("<option value=''>Pilih Bidang</option>");
				$.each(data.data, function (a, b) {
		            $("#bidang_skpd").append("<option value='"+b.id+"' >"+b.deskripsi+"</option>");
		        });


		        $('#bidang_skpd').val(data.detail[0].bidang_id);

				$('#nama').val(data.detail[0].nama);
				$('#nip').val(data.detail[0].nip);
	  			$('#modal-detail-admin').modal('show');
			}
		});
	});

	// FUNCTION BATAL TAMBAH (RESET)
	$(document).on("click", "#btn-batal-tambah", function(){
		$("#form-add-admin")[0].reset();
		$("#small-check").html('');
	    $('#form-check-user').removeClass('has-success');
	    $('#input-check').removeClass('form-control-success');
	    $('.select2').val('').trigger('change');
		$('#collapseadd').collapse('hide');
	});
	

	// FUNCTION UPDATE DATA
	$('#form-update-admin').on('submit', function(e){
	  e.preventDefault();
	  $.ajax({
	    url: '<?=site_url();?>admin/update_admin',              
	    type: 'POST',
	    data: $('#form-update-admin').serialize(),
	    dataType : 'json',
	    success : function(data){
	      	if (!data.error) {
		        $.toast({
		            heading: 'Berhasil',
		            text: data.pesan,
		            position: 'top-right',
		            loaderBg:'#ff6849',
		            icon: 'info',
		            hideAfter: 3000, 
		            stack: 6
		        });
		        $('#tabel-admin').DataTable().ajax.reload();
		        $('#modal-detail-admin').modal('hide');
		        
		    }else{
		    	$.toast({
		            heading: 'Terjadi Kesalahan',
		            text: data.pesan,
		            position: 'top-right',
		            loaderBg:'#ff6849',
		            icon: 'error',
		            hideAfter: 3000, 
		            stack: 6
	          	});
		    };
	    },
        error: function(jqXHR, textStatus, errorThrown){
        	$.toast({
	            heading: 'Terjadi Kesalahan',
	            text: 'Terjadi kesalahan saat mengambil data',
	            position: 'top-right',
	            loaderBg:'#ff6849',
	            icon: 'error',
	            hideAfter: 3000, 
	            stack: 6
	        });
        }
	  });                
	});

	// FUNCTION UPDATE STATUS 
	$(document).on("click", ".btn-status", function () {
		Swal.fire({
			title: 'Yakin untuk merubah status data ini?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, lanjutkan',
			cancelButtonText: 'Batalkan',
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url : "<?=site_url();?>admin/update_status",
					type: "POST",
					data: {kode: $(this).attr('data-kode'), status_id: $(this).attr('data-status-id'), username: $(this).attr('data-username')},
					dataType: 'JSON',
					success: function(data){
						if (data.error) {
							Swal('Gagal', 'Status tidak dapat dirubah.','error');
						}else{
							Swal('Berhasil', 'Status berhasil di ubah.','success');
							$('#tabel-admin').DataTable().ajax.reload();
						}
					},
					error: function(jqXHR, textStatus, errorThrown){
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'error',
							title: ' Terjadi Kesalahan'
						});
					}
				});

			}
		})
	});

	// FUNCTION CEK HANDLING DELAY
	function delay(callback, ms) {
		var timer = 0;
		return function() {
			var context = this, args = arguments;
			clearTimeout(timer);
			timer = setTimeout(function () {
				callback.apply(context, args);
			}, ms || 0);
		};
	}

	// CEK KETERSEDIAAN USERNAME
	$("#username").keyup(delay(function (e) {
		if($("#username").val() == '' || $("#username").val() == null){
			$('#form-check-user').removeClass('has-success');
			$('#form-check-user').removeClass('has-danger');
			$('#input-check').removeClass('form-control-success');
			$('#input-check').removeClass('form-control-danger');
			$("#small-check").html('');
		}else{
			$.ajax({
				url: '<?=site_url();?>admin/check_username', 
				type: "POST",
				dataType: "JSON",
				data: {username:$("#username").val()},
				success:function(data){
					var valid = '';
					if (data.error) {
						var valid = '<small class="form-text text-danger">'+data.pesan+'</small>';
						$('#form-check-user').addClass('has-danger');
						$('#input-check').addClass('form-control-danger');
						$('#username').addClass('form-control-danger');
					}else{
						var valid = '<small class="form-text text-success">'+data.pesan+'</small>';
						$('#form-check-user').removeClass('has-danger');
						$('#form-check-user').addClass('has-success');
						
						$('#input-check').removeClass('form-control-danger');
						$('#input-check').addClass('form-control-success');

						$('#username').removeClass('form-control-danger');
						$('#username').addClass('form-control-success');
					};
					$("#small-check").html(valid);
				}
			});
		}  
	}, 500));

	// Hapus spv
	$(document).on("click", ".delete_admin", function () {
		Swal({
			title: 'Yakin untuk mengahapus data ini?',
			text: "Data secara permanen akan terhapus",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, lanjutkan',
			cancelButtonText: 'Batalkan',
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url :  "<?php echo base_url('admin/delete_admin')?>",
					type: "POST",
					data: {kode: $(this).attr('data-kode'), username: $(this).attr('data-username')},
					dataType: 'JSON',
					success: function(data){
						if (data.error) {
							Swal('Gagal', 'Data gagal dihapus.','error');
						}else{
							Swal('Berhasil', 'Data berhasil di hapus.','success');
							$('#tabel-admin').DataTable().ajax.reload();
							 $("#skpd_id").empty();
							getListSkpd();
						}
					},
					error: function(jqXHR, textStatus, errorThrown){
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'error',
							title: ' Terjadi Kesalahan'
						});
					}
				});

			}
		})
	});


</script>