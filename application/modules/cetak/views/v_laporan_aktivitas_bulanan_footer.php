
    <script src="<?=base_url()?>_assets/material_pro/assets/plugins/select2/dist/js/select2.full.min.js"></script>
	<script src="<?=base_url()?>_assets/material_pro/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
    	SKPD 	= [];
    	SPV 	= [];

	    $(function() {

		    $(document).ajaxStart(function() {
		        $( "#request-loader" ).show();
		    });

		    $( document ).ajaxStop(function() {
		        $( "#request-loader" ).hide();
		    });

    		$.ajax({
				url: "<?php echo site_url('cetak/get_laporan_bulanan_select');?>",
				method: "POST",
				dataType: 'json',
				success: function(data) {
					SKPD 	= data.thl;
					SPV		= data.spv;

		    		var skpdSelect 		= $("#skpd-select");
		    		var bidangSelect 	= $("#bidang-select");
		    		var thlSelect 		= $("#thl-select");
		    		var kadisSelect 	= $("#kadis-select");
		    		var kabidSelect 	= $("#kabid-select");

		    		$.each(SKPD, function(i, d) {
						skpdSelect.append('<option value=' + d.skpd_id + '>' + d.nama_skpd + '</option>');
					});

		    		skpdSelect.select2();
		    		bidangSelect.select2();
		    		thlSelect.select2();
    				kadisSelect.select2();
    				kabidSelect.select2();
		    		
		    		$('.select2-selection').addClass("form-control");
		    		$('.select2-selection__rendered').css("padding", "0");
		    		$('.select2-selection__rendered').css("color", "#67757c");
		    		$('.select2-selection__arrow').css("min-height", "38px");
		    		
		    		bidangSelect.on("change", function (e) {
		    			thlSelect.empty();
		    			$.each(SKPD[skpdSelect[0].value]['bidang'][bidangSelect[0].value]['thl'], function(i, d) {
		    				if(d.kode_thl != null && d.nama_thl !== null)
								thlSelect.append('<option value=' + d.kode_thl + '>' + d.nama_thl + '</option>');
						});
		    		});

		    		skpdSelect.on("change", function (e) {
		    			bidangSelect.empty();
		    			thlSelect.empty();
		    			$.each(SKPD[skpdSelect[0].value]['bidang'], function(i, d) {
							bidangSelect.append('<option value=' + d.bidang_skpd_id + '>' + d.nama_bidang_skpd + '</option>');
						});
		    			bidangSelect.trigger('change');
		    		});

		    		skpdSelect.trigger('change');
		    		bidangSelect.trigger('change');
				}
			});
	    });

   		function cariLaporanTHL() {
			var inpObj = document.getElementById("laporan-thl-form");
			if(!inpObj.checkValidity())
				alert("Mohon cek isian kembali! Data tidak lengkap!");
			else {
				$.ajax({
					url : "<?php echo base_url('cetak/get_laporan_aktivitas_bulanan_thl'); ?>",
					type: "POST",
					data: $('#laporan-thl-form').serialize(),
					dataType: "JSON",
					success: function(data) {
						if(jQuery.isEmptyObject(data.detail_thl)) {
							alert("Data THL Error!");
						} else {
							$('#skpd-label')[0].value 		= data.detail_thl.nama_skpd;
							$('#bidang-label')[0].value 	= data.detail_thl.nama_bidang;
							$('#profesi-label')[0].value 	= data.detail_thl.nama_profesi;
							$('#nama-label')[0].value 		= data.detail_thl.nama_thl;

	                        $('#aktivitas-table-body').empty();
	                        var num 		= 1;
	                        var row 		= '';
	                        var aktivitas	= '';
	                        var status 		= '';
	                        var last_kabid 	= '';

							var options 	= { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

			    			$.each(data.laporan_thl, function(i, d) {
			    				aktivitas 	= '<ul>';
			    				status 		= '<ul>';
			    				$.each(d.aktivitas, function(ix, dx) {
			    					aktivitas 	+= ('<li>' + dx.aktivitas + '</li>');
			    					status 		+= ('<li>' + dx.status_aktivitas + '</li>');
	                        		if(dx.order == 0 && dx.kode_kabid)
	                        			last_kabid = dx.kode_kabid;
			    				});
			    				aktivitas 	+= '</ul>';
			    				status 		+= '</ul>';

			    				var tanggal = new Date(d.tanggal_laporan);

	                                                
	                            <?php if ($this->session->userdata('author_id') == 1 || $this->session->userdata('author_id') == 2): ?>

	                            row =	'<tr>' +
	                                        '<td class="text-center align-middle">' + num  + '</td>' +
	                                        '<td class="align-middle">' + tanggal.toLocaleDateString('id-ID', options) + '</td>' +
	                                        '<td>' + aktivitas + '</td>' +
	                                        '<td>' + status + '</td>' +
	                                	'</tr>';

	                            <?php else: ?>

	                                row =	'<tr>' +
	                                        '<td class="text-center align-middle">' + num  + '</td>' +
	                                        '<td class="align-middle">' + tanggal.toLocaleDateString('id-ID', options) + '</td>' +
	                                        '<td>' + aktivitas + '</td>' +
	                                	'</tr>';

	                            <?php endif; ?>

		    					$('#aktivitas-table-body').append(row);
			    				num++;
	                        	aktivitas 	= '';
	                        	status 		= '';
							});

				    		var kadisSelect 	= $("#kadis-select");
				    		var kabidSelect 	= $("#kabid-select");
				    		var tempSekretariat;

				    		kadisSelect.empty();
							$.each(SPV[data.detail_thl.skpd_id].bidang, function(j, dj) {
								$.each(dj.spv[3], function(k, dk) {
									tempSekretariat = dj.bidang_skpd_id;
								});
								$.each(dj.spv[4], function(k, dk) {
									if(dj.bidang_skpd_id == tempSekretariat)
									{
										kadisSelect.append('<optgroup label="' + dk.jabatan + '">');
										kadisSelect.append('<option value=' + JSON.stringify({ skpd: data.detail_thl.skpd_id, kadis: dk.kode_spv }) + '>' + dk.nama_spv + '</option>');
										kadisSelect.append('</optgroup>');
									}
								});
								$.each(dj.spv[3], function(k, dk) {
									kadisSelect.append('<optgroup label="' + dk.jabatan + '">');
									kadisSelect.append('<option value=' + JSON.stringify({ skpd: data.detail_thl.skpd_id, kadis: dk.kode_spv }) + '>' + dk.nama_spv + '</option>');
									kadisSelect.append('</optgroup>');
									tempSekretariat = dj.bidang_skpd_id;
								});
							});

				    		kadisSelect.on("change", function (e) {
				    			var kabidVal = JSON.parse(kadisSelect[0].value);
				    			kabidSelect.empty();
				    			$.each(SPV[kabidVal.skpd].bidang, function(i, di) {
									$.each(di.spv[4], function(j, dj) {
			    						if(dj.kode_spv == last_kabid) {
				    						kabidSelect.append('<optgroup label="' + di.nama_bidang_skpd + '">');
											kabidSelect.append('<option value=' + dj.kode_spv + '>' + dj.nama_spv + '</option>');
											kadisSelect.append('</optgroup>');
			    						}
									});
				    			});
				    		});

							$('#thl-cetak-download')[0].value = $('#thl-select')[0].value;
							$('#bulan-cetak-download')[0].value = $('#bulan-select')[0].value;
		    				$('#kadis-select').trigger('change');
							$('#kabid-select')[0].value = last_kabid;
		    				$('#kabid-select').trigger('change');
							$('#kadis-select').prop("disabled", false);
							$('#kabid-select').prop("disabled", false);
							$('#jenis-select').prop("disabled", false);
							$('.button-download').prop("disabled", false);

							if(data.laporan_thl.length === 0)
								$('#aktivitas-table-body').append('<tr><td colspan="4" class="text-center">Tabel Kosong</td></tr>');
						}
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
    </script>