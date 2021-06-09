
<!-- Sweet-Alert  -->
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/sweetalert/sweetalert.min.js"></script>

<script src="<?=base_url()?>_assets/material_pro/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>_assets/material_pro/assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
<script src="<?=base_url()?>_assets/material_pro/material/js/jasny-bootstrap.js"></script>

<script>

    <?php if ($this->session->userdata('is_login')): ?>
	<?php if ($this->session->userdata('author_id') == 6): ?>

	STATUSPERKAWINAN 	= [];
	PROVINSI			= [];
	WILAYAH				= [];
	PENDIDIKAN 			= [];

	<?php endif; ?>

	$( document ).ready(function() {

	    $(document).ajaxStart(function() {
	        $( "#request-loader" ).show();
	    });

	    $( document ).ajaxStop(function() {
	        $( "#request-loader" ).hide();
	    });

		$.fn.dataTable.ext.errMode = 'none';

		<?php if ($this->session->userdata('author_id') == 6): ?>

	    // For Custom File Input
		$('.custom-file-input').on('change',function(){
			//get the file name
			var fileName = $(this).val();
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		});

		$('#modal-edit-pendidikan').on('show.bs.modal', function (event) {
			var button  	= $(event.relatedTarget); /* Button that triggered the modal*/
			var id      	= button.data('id'); /* Extract info from data-* attributes*/
			var institusi	= button.data('institusi'); /* Extract info from data-* attributes*/
			var jenjang    	= button.data('jenjang'); /* Extract info from data-* attributes*/

			$('#id-pendidikan-edit').val(id);
			$('#institusi-pendidikan-edit').val(institusi);
			$('#jenjang-pendidikan-edit').val(jenjang);
		});

		$('#modal-edit-pendidikan').on('hidden.bs.modal', function() {
			$('#id-pendidikan-edit').val("");
			$('#institusi-pendidikan-edit').val("");
			$('#jenjang-pendidikan-edit').val("");
			$('#lampiran-pendidikan-edit').val("");
            document.getElementById('hapus-edit-pendidikan').click();
		});

		$('#modal-edit-pekerjaan').on('show.bs.modal', function (event) {
			var button  	= $(event.relatedTarget); /* Button that triggered the modal*/
			var id      	= button.data('id'); /* Extract info from data-* attributes*/
			var instansi	= button.data('instansi'); /* Extract info from data-* attributes*/
			var posisi		= button.data('posisi'); /* Extract info from data-* attributes*/
			var masuk    	= button.data('masuk'); /* Extract info from data-* attributes*/
			var keluar    	= button.data('keluar'); /* Extract info from data-* attributes*/

			$('#id-pekerjaan-edit').val(id);
			$('#instansi-pekerjaan-edit').val(instansi);
			$('#posisi-pekerjaan-edit').val(posisi);
			$('#masuk-pekerjaan-edit').val(masuk);
			$('#keluar-pekerjaan-edit').val(keluar);
		});

		$('#modal-edit-pekerjaan').on('hidden.bs.modal', function() {
			$('#id-pekerjaan-edit').val("");
			$('#instansi-pekerjaan-edit').val("");
			$('#posisi-pekerjaan-edit').val("");
			$('#masuk-pekerjaan-edit').val("");
			$('#keluar-pekerjaan-edit').val("");
			$('#lampiran-pekerjaan-edit').val("");
            document.getElementById('hapus-edit-pekerjaan').click();
		});

		$('#modal-edit-sertifikasi').on('show.bs.modal', function (event) {
			var button  	= $(event.relatedTarget); /* Button that triggered the modal*/
			var id      	= button.data('id'); /* Extract info from data-* attributes*/
			var sertifikat	= button.data('sertifikat'); /* Extract info from data-* attributes*/

			$('#id-sertifikasi-edit').val(id);
			$('#sertifikat-sertifikasi-edit').val(sertifikat);
		});

		$('#modal-edit-sertifikasi').on('hidden.bs.modal', function() {
			$('#id-sertifikasi-edit').val("");
			$('#sertifikat-sertifikasi-edit').val("");
            document.getElementById('hapus-edit-sertifikasi').click();
		});

        datatable = $('#pendidikan-table').on( 'error.dt', function ( e, settings, techNote, message ) {
			if(settings.jqXHR.status == 401)
	    		location.reload();
			console.log( 'An error has been reported by DataTables: ', message );
		}).DataTable({
        	"processing": true, /*Feature control the processing indicator.*/
        	"serverSide": true, /*Feature control DataTables' server-side processing mode.*/
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "drawCallback": function () {
                $('#pendidikan-table_wrapper').addClass('p-0');
            },

	        /* Load data for the table's content from an Ajax source*/
	        "ajax": {
	          "url": "<?php echo site_url('profil/getPendidikanTable')?>",
	          "type": "POST"
	        },
	        "columns": [
	          { "data": "no" },
	          { "data": "institusi" },
	          { "data": "jenjang" },
	          { "data": "lampiran" },
	          { "data": "action" }
	        ],

            /*Set column definition initialisation properties.*/
	        "columnDefs": [{
	          "targets": [1, 2],
	          "className": 'align-middle'
	        },{
	          "targets": [0, 3],
	          "className": 'align-middle text-center'
	        },{
	          "targets": [4],
	          "className": 'text-center align-middle text-nowrap'
	        }]
        });

        datatable1 = $('#pekerjaan-table').on( 'error.dt', function ( e, settings, techNote, message ) {
			if(settings.jqXHR.status == 401)
	    		location.reload();
			console.log( 'An error has been reported by DataTables: ', message );
		}).DataTable({
        	"processing": true, /*Feature control the processing indicator.*/
        	"serverSide": true, /*Feature control DataTables' server-side processing mode.*/
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "drawCallback": function () {
                $('#pekerjaan-table_wrapper').addClass('p-0');
            },

	        /* Load data for the table's content from an Ajax source*/
	        "ajax": {
	          "url": "<?php echo site_url('profil/getPekerjaanTable')?>",
	          "type": "POST"
	        },
	        "columns": [
	          { "data": "no" },
	          { "data": "instansi" },
	          { "data": "posisi" },
	          { "data": "masuk" },
	          { "data": "keluar" },
	          { "data": "lampiran" },
	          { "data": "action" }
	        ],

            /*Set column definition initialisation properties.*/
	        "columnDefs": [{
	          "targets": [1, 2],
	          "className": 'align-middle'
	        },{
	          "targets": [0, 3, 4, 5],
	          "className": 'align-middle text-center'
	        },{
	          "targets": [6],
	          "className": 'text-center align-middle text-nowrap'
	        }]
        });

        datatable2 = $('#sertifikasi-table').on( 'error.dt', function ( e, settings, techNote, message ) {
			if(settings.jqXHR.status == 401)
	    		location.reload();
			console.log( 'An error has been reported by DataTables: ', message );
		}).DataTable({
        	"processing": true, /*Feature control the processing indicator.*/
        	"serverSide": true, /*Feature control DataTables' server-side processing mode.*/
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "drawCallback": function () {
                $('#sertifikasi-table_wrapper').addClass('p-0');
            },

	        /* Load data for the table's content from an Ajax source*/
	        "ajax": {
	          "url": "<?php echo site_url('profil/getSertifikasiTable')?>",
	          "type": "POST"
	        },
	        "columns": [
	          { "data": "no" },
	          { "data": "sertifikat" },
	          { "data": "lampiran" },
	          { "data": "action" }
	        ],

            /*Set column definition initialisation properties.*/
	        "columnDefs": [{
	          "targets": [1],
	          "className": 'align-middle'
	        },{
	          "targets": [0, 2],
	          "className": 'align-middle text-center'
	        },{
	          "targets": [3],
	          "className": 'text-center align-middle text-nowrap'
	        }]
        });

        $('#pendidikan-table').on( 'draw.dt', function () {
            document.getElementById('pendidikan-nav').click();
        } );

        $('#pekerjaan-table').on( 'draw.dt', function () {
            document.getElementById('pekerjaan-nav').click();
        } );

        $('#sertifikasi-table').on( 'draw.dt', function () {
            document.getElementById('keahlian-nav').click();
        } );

    	<?php endif; ?>

        logtable = $('#log-table').on( 'error.dt', function ( e, settings, techNote, message ) {
			if(settings.jqXHR.status == 401)
	    		location.reload();
			console.log( 'An error has been reported by DataTables: ', message );
		}).DataTable({
        	"processing": true, /*Feature control the processing indicator.*/
        	"serverSide": true, /*Feature control DataTables' server-side processing mode.*/
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "drawCallback": function () {
                $('#log-table_wrapper').addClass('p-0');
            },

	        /* Load data for the table's content from an Ajax source*/
	        "ajax": {
	          "url": "<?php echo site_url('profil/getLogTable')?>",
	          "type": "POST"
	        },
	        "columns": [
	          { "data": "no" },
	          { "data": "keterangan" },
	          { "data": "waktu" }
	        ],

            /*Set column definition initialisation properties.*/
	        "columnDefs": [{
	          "targets": [1],
	          "className": 'align-middle'
	        },{
	          "targets": [0, 2],
	          "className": 'align-middle text-center'
	        }]
        });

    	$('#request-loader').hide();

		$.ajax({
			url: "<?php echo site_url('profil/getFormData');?>",
			method: "POST",
			dataType: 'json',
			success: function(data) {

				<?php if ($this->session->userdata('author_id') == 6): ?>

				STATUSPERKAWINAN 	= data.status_perkawinan;
				PROVINSI			= data.provinsi;
				PENDIDIKAN 			= data.pendidikan;

				$.each(PROVINSI, function(i, dProv) {
					WILAYAH[dProv.id] = [];
					$.each(data.wilayah, function(j, dWil) {
						if (dWil.provinsi_id == dProv.id)
							WILAYAH[dProv.id].push({id: dWil.id, deskripsi: dWil.deskripsi})
					});
				});

				$.each(STATUSPERKAWINAN, function(i, d) {
					$('#select-status-perkawinan').append('<option value=' + d.id + '>' + d.deskripsi + '</option>');
				});

				$.each(PROVINSI, function(i, d) {
					$('#select-provinsi-lahir').append('<option value=' + d.id + '>' + d.deskripsi + '</option>');
				});

				$.each(PROVINSI, function(i, d) {
					$('#select-provinsi-alamat').append('<option value=' + d.id + '>' + d.deskripsi + '</option>');
				});

				$.each(PENDIDIKAN, function(i, d) {
					$('#jenjang-pendidikan-edit').append('<option value=' + d.id + '>' + d.deskripsi + '</option>');
				});

				<?php endif; ?>

    			refreshProfil();

        		document.getElementById('password-nav').click();
			}
		});
	});

	function refreshProfil()
	{
		$.ajax({
			url: "<?php echo site_url('profil/getProfil');?>",
			method: "POST",
			dataType: 'json',
			success: function(data) {
				if(data) {
					$('#profil-skpd').text(data.nama_skpd);
					$('#profil-bidang').text(data.nama_bidang_skpd);

					<?php if ($this->session->userdata('author_id') == 6): ?>

					var date = new Date(data.tgl_lahir);
					const options = {year: 'numeric', month: 'long', day: 'numeric'};

					$('#profil-nama').after('<h6 class="card-subtitle">' + data.profesi + '</h6>');

					$('#profil-bidang').after('<h6>' + data.provinsi_alamat + '</h6>');
					$('#profil-bidang').after('<h6>' + data.wilayah_alamat + '</h6>');
					$('#profil-bidang').after('<h6>' + data.alamat + '</h6>');
					$('#profil-bidang').after('<small class="text-muted">Alamat </small>');
					$('#profil-bidang').after('<h6>' + data.status_perkawinan + '</h6>');
					$('#profil-bidang').after('<small class="text-muted">Status Perkawinan </small>');
					$('#profil-bidang').after('<h6>' + date.toLocaleDateString('id-ID', options) + '</h6>');
					$('#profil-bidang').after('<h6>' + data.provinsi_lahir + '</h6>');
					$('#profil-bidang').after('<h6>' + data.wilayah_lahir + '</h6>');
					$('#profil-bidang').after('<small class="text-muted">TTL </small>');
					$('#profil-bidang').after('<h6>' + data.email + '</h6>');
					$('#profil-bidang').after('<small class="text-muted">Email </small>');
					$('#profil-bidang').after('<h6>' + data.telepon + '</h6>');
					$('#profil-bidang').after('<small class="text-muted">Telepon </small>');
					$('#profil-bidang').after('<h6>' + data.nik + '</h6>');
					$('#profil-bidang').after('<small class="text-muted">NIK </small>');

					$("#input-telepon").val(data.telepon);
					$("#input-email").val(data.email);

					$('#select-status-perkawinan').val(data.status_perkawinan_id).trigger('change');

					$("#tgl-lahir-picker").val(data.tgl_lahir);
					$('#select-provinsi-lahir').val(data.provinsi_lahir_id).trigger('change');
					$('#select-wilayah-lahir').val(data.wilayah_lahir_id).trigger('change');

					$("#textarea-alamat").val(data.alamat);
					$('#select-provinsi-alamat').val(data.provinsi_alamat_id).trigger('change');
					$('#select-wilayah-alamat').val(data.wilayah_alamat_id).trigger('change');

					<?php else: ?>

					$('#profil-nama').after('<h6 class="card-subtitle">' + data.jabatan + '</h6>');
					$('#profil-bidang').after('<h6>' + data.nip + '</h6>');
					$('#profil-bidang').after('<small class="text-muted">NIP </small>');

					<?php endif; ?>

				}
			}
		});
	}

	function editPassword() {
		var inpObj = document.getElementById("edit-password-form");
		if(!inpObj.checkValidity())
			alert("Mohon cek isian kembali! Data tidak lengkap!");
		else {
			Swal.fire({
				title: 'Edit Password?',
				text: "Apakah anda yakin ingin mengubah password?",
				type: 'warning',
				showCancelButton: true,
				cancelButtonText: 'Batal',
				confirmButtonText: 'Edit'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: "<?php echo site_url('profil/editPassword');?>",
						method: "POST",
						data: $('#edit-password-form').serialize(),
						dataType: 'json',
						success: function(data) {
							Swal.fire({
							title: 'Berhasil!',
							type: 'success',
							text: data,
							confirmButtonText: 'Tutup'
							});
							inpObj.reset();
						},
						error: function (jqXHR, textStatus, errorThrown) {
							if(jqXHR.status == 401)
								location.reload();
							var responseText = getErrorTitle(jqXHR);
							Swal.fire({
							title: 'Error!',
							type: 'error',
							text: ((responseText) ? responseText : jqXHR.statusText),
							confirmButtonText: 'Tutup'
							});
						}
					});
				}
			});
		}
	}

	<?php if ($this->session->userdata('author_id') == 6): ?>

	function setWilayah(idProv, idWil) {
		var selectProvinsi;
		var selectWilayah;

		selectProvinsi 	= $(idProv);
		selectWilayah 	= $(idWil);

		selectWilayah.empty();
		$.each(WILAYAH[selectProvinsi[0].value], function(i, d) {
			selectWilayah.append('<option value=' + d.id + '>' + d.deskripsi + '</option>');
		});
	}

	function editProfil() {
		var inpObj = document.getElementById("edit-profil-form");
		if(!inpObj.checkValidity())
			alert("Mohon cek isian kembali! Data tidak lengkap!");
		else {
			$.ajax({
				url : "<?php echo base_url('profil/editProfil'); ?>",
				type: "POST",
				data: $('#edit-profil-form').serialize(),
				dataType: "JSON",
				success: function(data) {
					Swal.fire({
						title: 'Berhasil!',
						type: 'success',
						text: data,
						confirmButtonText: 'Tutup'
					}).then(function(){
						Swal.fire({
							type: 'warning',
							text: 'Halaman akan dimuat ulang!',
							confirmButtonText: 'Muat Ulang'
						}).then(function(){
							location.reload();
						});
					});
				},
				error: function (jqXHR, textStatus, errorThrown) {
					if(jqXHR.status == 401)
						location.reload();
					var responseText = getErrorTitle(jqXHR);
					Swal.fire({
						title: 'Error!',
						type: 'error',
						text: ((responseText) ? responseText : jqXHR.statusText),
						confirmButtonText: 'Tutup'
					});
				}
			});
		}
	}

	function createXMLRequest(form, modal, table, url)
	{
		var inpObj = document.getElementById(form);
		if(!inpObj.checkValidity())
			alert("Mohon cek isian kembali! Data tidak lengkap!");
		else {
			var formElement = inpObj;
			var request = new XMLHttpRequest();
			request.open("POST", url);
			request.setRequestHeader('X-Requested-With', ' XMLHttpRequest');
			request.onloadstart = function(oEvent) {
				$('#request-loader').show();
			};
			request.onload = function(oEvent) {
				$('#request-loader').hide();
			    if (request.status == 200) {
					Swal.fire({
						title: 'Berhasil!',
						type: 'success',
						text: request.responseText,
						confirmButtonText: 'Tutup'
					}).then(function(){
						table.ajax.reload();
						$(modal).modal('hide');
					});
				} else if(request.status == 401) {
					location.reload();
			    } else {
					Swal.fire({
						title: 'Error!',
						type: 'error',
						text: request.responseText,
						confirmButtonText: 'Tutup'
					});
			    }
			};
			request.send(new FormData(formElement));
		}
	}

	function editPendidikan() {
		var form 	= "edit-pendidikan-form";
		var modal 	= '#modal-edit-pendidikan';
		var table 	= datatable;
		var url 	= "<?php echo base_url('profil/editPendidikan'); ?>"

		createXMLRequest(form, modal, table, url);
	}

	function editPekerjaan() {
		var form 	= "edit-pekerjaan-form";
		var modal 	= '#modal-edit-pekerjaan';
		var table 	= datatable1;
		var url 	= "<?php echo base_url('profil/editPekerjaan'); ?>"

		createXMLRequest(form, modal, table, url);
	}

	function editSertifikasi() {
		var form 	= "edit-sertifikasi-form";
		var modal 	= '#modal-edit-sertifikasi';
		var table 	= datatable2;
		var url 	= "<?php echo base_url('profil/editSertifikasi'); ?>"

		createXMLRequest(form, modal, table, url);
	}

	function getErrorTitle(jqXHR) {
		var titleText       = "title";
        var response        = $.parseHTML(jqXHR.responseText);
        var responseText    = jqXHR.responseText;
        if ($.isArray(response))
            $.each(response, function( index, value ) {
                if (titleText.toLowerCase() === value.nodeName.toLowerCase())
                {
                    responseText = value.text;
                    return false;
                }
            });
        return responseText;
	}
	
    <?php endif; ?>
    <?php endif; ?>

</script>