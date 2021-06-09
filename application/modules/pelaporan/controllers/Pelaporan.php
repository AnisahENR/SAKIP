<?php defined('BASEPATH')  OR exit('No direct script access allowed');

/**
 * 
 */
class Pelaporan extends MX_Controller
{
	
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('common_helper');
		$this->load->model('M_pelaporan');
		if (!$this->session->userdata('is_login')) {
			redirect('login');
		}
	}

	// <!-- ============================================================== -->
	// <!-- HALAMAN PELAPORAN AKTIVITAS HARIAN  -->
	// <!-- ============================================================== -->

	function index(){
		//$data['spv'] = $this->M_pelaporan->get_spv();
		// $data['kegiatan'] = $this->M_pelaporan->get_kegiatan_thl('1');

		$this->load->view('templates/material_pro/head');
		$this->load->view('templates/material_pro/header');
		$this->load->view('header-custom');
		$this->load->view('templates/material_pro/sidebar');

		$this->load->view('v_pelaporan');
		
		$this->load->view('templates/material_pro/footer-1');
		$this->load->view('footer-custom');
		$this->load->view('templates/material_pro/footer-2');
	}

	// <!-- ============================================================== -->
	// <!-- GET DATA TABEL AKTIVITAS HARIAN  -->
	// <!-- ============================================================== -->

	function get_laporan(){
		// $kode_thl = "17bquA2p2c4";
		$kode_thl = $this->session->userdata('kode_thl');
		$filter_tanggal = explode('-',trim(xss_filter($this->input->post('tanggal'))));
		// $kode_thl = "17bquA2p2c4";
		$range_date = array(date('Y-m-d',strtotime(str_replace('/', '-', $filter_tanggal[0]))),date('Y-m-d',strtotime(str_replace('/', '-', $filter_tanggal[1]))));		
		$data['laporan_thl']= $this->M_pelaporan->getLaporanThl($kode_thl,$range_date);
		
		$output['data']=array();
		$nomor_urut=1;
		

		foreach ($data['laporan_thl'] as $key =>$row) {
			// $label = label_stat_laporan($row['stat_laporan_id']);
			$kode_laporan_thl = "'".$row['kode_laporan_thl']."'";
			$kode_thl = "'".$row['kode_thl']."'";
			$tgl_laporan = $row['tgl_laporan'];
			$newDate = date("d-m-Y", strtotime($tgl_laporan)); 
			$newTgl = date("Y-m-d", strtotime($tgl_laporan)); 
			
			$btn = ($row['jumlah'])?'<button type="button" class="btn btn-danger btn-circle btn-delete" onclick="hapus('.$kode_laporan_thl.')"><i class="fas fa-minus-circle"></i> </button>':'';

			$output['data'][]=array(
				'nomor_urut' 		=> $nomor_urut,
				'nama' 				=> $row['nama'],
				'kode_spv'			=> "Kabid : ".$row['kabid_nama']." <br> "."Kasie  : ".$row['kasie_nama'],
				'status' 			=> ($row['jumlah'])?'<span class="label label-warning">Menunggu Verifikasi</span>':'<span class="label label-success">Selesai Verifikasi</span>',
				'tgl_laporan'		=> '<button type="button" class="btn btn-rounded btn-outline-primary">'.$newDate.'</button>',
				'action' 			=> '<a href="'.base_url("/detail_laporan/".$row['kode_laporan_thl']).'" style="color:white;"><button type=."button" class="btn btn-primary btn-circle"><i class="fas fa-th-list"></i></button></a> '.$btn	
			);
			
			$nomor_urut++;		
		}

		echo json_encode($output);
	}

	// <!-- ============================================================== -->
	// <!-- MENDAPATKAN KEGIATAN THL BERDASARKAN TANGGAL  -->
	// <!-- ============================================================== -->

	function get_kegiatan_thl(){
		// $tgl_laporan = date("2020-12-21");
		$tgl_laporan = $this->input->post('id');
		$result = $this->M_pelaporan->get_kode_target($tgl_laporan);
		$kode_target_thl = null;

		foreach ($result as $key => $value) {
			$kode_target_thl	= $value['kode_target_thl'];
		}

		$data=$this->M_pelaporan->get_kegiatan_target($kode_target_thl);
		echo json_encode($data);

	}

	// <!-- ============================================================== -->
	// <!-- TAMBAH AKTIVITAS  -->
	// <!-- ============================================================== -->

	function view_tambah_aktivitas(){
		$kode_thl = $this->session->userdata('kode_thl');
		$data['spv'] = $this->M_pelaporan->get_spv($kode_thl);
		$data['identitas'] = $this->M_pelaporan->getThl($kode_thl);
		$data['tgllaporan']= $this->M_pelaporan->getAvail_TglLaporan(['param_kode_thl' =>$kode_thl]);
		mysqli_next_result( $this->db->conn_id );
		//$data['kegiatan'] = $this->M_pelaporan->get_kegiatan_thl('1');

		$this->load->view('templates/material_pro/head');
		$this->load->view('templates/material_pro/header');
		$this->load->view('header-custom');
		$this->load->view('templates/material_pro/sidebar',['page' => 'pelaporan','subpage' => 'laporan_harian']);

		$this->load->view('v_tambah_aktivitas',$data);
		
		$this->load->view('templates/material_pro/footer-1');
		$this->load->view('footer-custom');
		$this->load->view('templates/material_pro/footer-2');
	}

	function addform_kegiatan(){
		$kode_target_thl= xss_filter($this->input->post('kode_target_thl'));
		$room_kegiatan	= xss_filter($this->input->post('room_kegiatan'));
		$kegiatan = $this->M_pelaporan->get_listkegiatan($kode_target_thl);
		// echo "<pre>";
		// var_dump($kegiatan);die;
		
		$opsi = '<select id="kegiatan_thl_id'.$room_kegiatan.'" name="kegiatan_thl_id[]" class="form-control select2" style="width: 100%" required><option value=""> - Pilih Kegiatan - </option>';
		foreach ($kegiatan as $key => $value) {
			$opsi .= '<option value="'.$value['id'].'" > '.$value['deskripsi'].' </option>';
		}
		$opsi .= '</select>';

		$return['html'] = '<div class="form-group removeclass'.$room_kegiatan.'">
		<div class="row">
		<div class="col-sm-5 nopadding">
		<div class="form-group">
		<label class="control-label">Kegiatan</label>
		'.$opsi.'
		</div>
		</div>
		<div class="col-sm-2 nopadding">
		<div class="form-group">
		<label class="control-label">Waktu</label>
		<input type="number" class="form-control waktu_kegiatan" id="waktu_kegiatan'.$room_kegiatan.'" name="waktu_kegiatan[]" value="" onchange="total_nilai()" placeholder="waktu" required>
		</div>
		</div>
		<div class="col-sm-5">
		<div class="form-group">
		<label>Default file upload</label>
		<input type="file" class="form-control"  name="lampiran_kegiatan[]" accept="image/*,.pdf" max-file-size="5230" required>
		</div>
		</div>
		</div>
		<div class="row">
		<div class="col-sm-10 nopadding">
		<div class="form-group">
		<label class="control-label">Uraian Kegiatan</label>
		<div class="input-group">
		<textarea class="form-control" rows="2" name="uraian_kegiatan[]" placeholder="Uraian Kegiatan" required></textarea>
		</div>
		</div>
		</div>
		<div class="col-sm-2 mt-5">
		<div class="form-group">
		<button class="btn btn-danger" type="button" onclick="remove_education_fields('.$room_kegiatan.');">Hapus row</button>
		</div>
		</div>

		<div class="col-md-12"><hr></div>
		</div> <!-- end row --></div>';
		header('Content-Type:application/json');
		echo json_encode($return);
	}

	function addform_lainnya(){
		$room_lainnya	= xss_filter($this->input->post('room_lainnya'));
		$return['html'] = '<div class="form-group removeclasslainnya'.$room_lainnya.'">
		<div class="row">
		<div class="col-sm-7 nopadding">
		<div class="form-group">
		<label class="control-label">Uraian Kegiatan</label>
		<div class="input-group">
		<textarea class="form-control" rows="4" name="uraian_lainnya[]" placeholder="Uraian Kegiatan" required></textarea>
		</div>
		</div>
		</div>
		<div class="col-sm-5 nopadding">
		<div class="form-group">
		<label>Default file upload</label>
		<input type="file" class="form-control"  name="lampiran_lainnya[]" accept="image/*,.pdf" max-file-size="5230" required>
		<div class="row">
		<div class="col-md-6">                                    
		<label class="control-label">Waktu</label>
		<input type="number" class="form-control waktu_lainnya" id="waktu" name="waktu_lainnya[]" value="" onchange="total_nilai()" placeholder="waktu" required>
		</div>
		<div class="col-md-6 mt-4">
		<button class="btn btn-danger btn-block" type="button" onclick="remove_lainnya_fields('.$room_lainnya.');">Hapus row</button>
		</div>
		</div>
		</div>
		</div>
		<div class="col-md-12"><hr></div>
		</div> <!-- end row -->';
		header('Content-Type:application/json');
		echo json_encode($return);
	}

	function tambah_aktivitas() {
		$kode_laporan_thl = create_kode();
		$kode_thl = $this->session->userdata('kode_thl');
		$target = onetable_current_data('t_target_thl',['kode_target_thl' => xss_filter($this->input->post('kode_target_thl'))]);
		$daftar_kegiatan = array();

		$this->db->trans_begin();
		$daftar_laporan = array(
			'kode_laporan_thl' 		=> xss_filter($kode_laporan_thl),
			'kode_target_thl'		=> xss_filter($target->kode_target_thl),
			'skpd_id' 				=> xss_filter($target->skpd_id),
			'bidang_skpd_id'		=> xss_filter($target->bidang_skpd_id),
			'profesi_thl_id'		=> xss_filter($target->profesi_thl_id),
			'kode_thl' 				=> xss_filter($kode_thl),
			'kode_spv_kabid' 		=> xss_filter($target->kode_spv_kabid),
			'kode_spv_kasie'		=> xss_filter($target->kode_spv_kasie),
			'tgl_laporan' 			=> xss_filter($target->tgl_laporan),
			'jml_waktu'				=> xss_filter($this->input->post('waktu')),
			'created_by'			=> xss_filter($kode_thl)
		);

		$this->M_pelaporan->post_laporan_thl($daftar_laporan);

			// $data = [];

			// upload 
		$target_path = "_upload/".$daftar_laporan['created_by']."/".$daftar_laporan['kode_laporan_thl']."/";
		if (! is_dir($target_path)) {
			mkdir($target_path, 0777, TRUE);
		}

			// laporan kegiatan
		$temp_kegiatan = array();
		for($i=0; $i < count($_FILES['lampiran_kegiatan']['name']);$i++){
			if(!empty($_FILES['lampiran_kegiatan']['name'][$i])){
				$kode = create_kode();

				$temp_kegiatan[] = array(
					'kode_laporan_thl'	=> $daftar_laporan['kode_laporan_thl'],
					'kegiatan_thl_id'	=> xss_filter($this->input->post('kegiatan_thl_id')[$i]),
					'stat_laporan_id'	=> 1,
					'waktu' 			=> xss_filter($this->input->post('waktu_kegiatan')[$i]),
					'uraian' 			=> xss_filter($this->input->post('uraian_kegiatan')[$i]),
					'lampiran'			=> $kode.'.'.pathinfo($_FILES['lampiran_kegiatan']['name'][$i], PATHINFO_EXTENSION),
					'kode_spv_kabid' 	=> $daftar_laporan['kode_spv_kabid'],
					'kode_spv_kasie'	=> $daftar_laporan['kode_spv_kasie']
				);

				if (!move_uploaded_file($_FILES['lampiran_kegiatan']['tmp_name'][$i], $target_path .$temp_kegiatan[$i]['lampiran'])) {
					$this->db->trans_rollback();
					$this->output->set_content_type('json', 'utf-8');
					$this->output->set_output(array('error'=>TRUE, 'pesan'=>'could not move file'));
					die;
				}
				if(pathinfo($_FILES['lampiran_kegiatan']['name'][$i], PATHINFO_EXTENSION) != 'pdf'){
					re_image($target_path .$temp_kegiatan[$i]['lampiran']);						
				}
			}

		} 
		if (!empty($temp_kegiatan)) {
			$this->db->insert_batch('t_laporan_kegiatan_thl', $temp_kegiatan);
		}

			// laporan lainnya
		$temp_lainnya = array();
		for($i=0; $i < count($_FILES['lampiran_lainnya']['name']);$i++){
			if(!empty($_FILES['lampiran_lainnya']['name'][$i])){
				$kode = create_kode();

				$temp_lainnya[] = array(
					'kode_laporan_thl'	=> $daftar_laporan['kode_laporan_thl'],
					'stat_laporan_id'	=> 1,
					'waktu' 			=> xss_filter($this->input->post('waktu_lainnya')[$i]),
					'uraian' 			=> xss_filter($this->input->post('uraian_lainnya')[$i]),
					'lampiran'			=> $kode.'.'.pathinfo($_FILES['lampiran_lainnya']['name'][$i], PATHINFO_EXTENSION),
					'kode_spv_kabid' 	=> $daftar_laporan['kode_spv_kabid'],
					'kode_spv_kasie'	=> $daftar_laporan['kode_spv_kasie']
				);

				if (!move_uploaded_file($_FILES['lampiran_lainnya']['tmp_name'][$i], $target_path .$temp_lainnya[$i]['lampiran'])) {
					$this->db->trans_rollback();
					$this->output->set_content_type('json', 'utf-8');
					$this->output->set_output(array('error'=>TRUE, 'pesan'=>'could not move file'));
					die;
				}
				if(pathinfo($_FILES['lampiran_lainnya']['name'][$i], PATHINFO_EXTENSION) != 'pdf'){
					re_image($target_path .$temp_lainnya[$i]['lampiran']);						
				}
			}

		} 
		if (!empty($temp_lainnya)) {
			$this->db->insert_batch('t_laporan_lain_thl', $temp_lainnya); 
		}


		$insert_log = array(
			'kode'			=> $daftar_laporan['kode_thl'],
			'keterangan'	=> 'Menambah Laporan Aktivitas Harian Tanggal '.date("d-m-Y", strtotime($daftar_laporan['tgl_laporan'])),
		);
		insert_data('trx_log_akun',$insert_log);
		$this->db->trans_commit();

		echo json_encode(array("status" => TRUE));
		// }
	}

	// <!-- ============================================================== -->
	// <!-- HAPUS LAPORAN PADA TABEL AKTIVITAS  -->
	// <!-- ============================================================== -->

	function hapus_laporan($kode_laporan_thl){
		$laporan = onetable_current_data('t_laporan_thl',['kode_laporan_thl' => $kode_laporan_thl]);
		$url_folder = "_upload/".$laporan->kode_thl."/".$kode_laporan_thl;
		rrmdir($url_folder);
		$this->M_pelaporan->hapus_pelaporan($kode_laporan_thl);

		$insert_log = array(
			'kode'			=> $this->session->userdata('kode_thl'),
			'keterangan'	=> 'Menghapus Laporan Aktivitas Harian Tanggal '.date("d-m-Y", strtotime($laporan->tgl_laporan)),
		);
		insert_data('trx_log_akun',$insert_log);
		
		echo json_encode(array("status" => TRUE));

	}

	// <!-- ============================================================== -->
	// <!-- HALAMAN DETAIL AKTIVITAS  -->
	// <!-- ============================================================== -->

	function detail_aktivitas($kode_laporan_thl){
		$kode_thl = $this->session->userdata('kode_thl');
		$data ['identitas'] = $this->M_pelaporan->get_identitas_pelapor($kode_laporan_thl);
		$data['spv'] = $this->M_pelaporan->get_spv($kode_thl);

		$this->load->view('templates/material_pro/head');
		$this->load->view('templates/material_pro/header');
		$this->load->view('header-custom');
		$this->load->view('templates/material_pro/sidebar',['page' => 'persetujuan','subpage' => 'laporan_harian']);

		$this->load->view('v_detail_aktivitas', $data);

		$this->load->view('templates/material_pro/footer-1');
		$this->load->view('footer-custom', $data);
		$this->load->view('templates/material_pro/footer-2');
	}

	// <!-- ============================================================== -->
	// <!-- GET DATA TABEL DETAIL AKTIVITAS  -->
	// <!-- ============================================================== -->

	function tabel_aktivitas($kode_laporan_thl){
		$data['detail']= $this->M_pelaporan->get_detail_laporan($kode_laporan_thl);
		
		$output['data']=array();
		$nomor_urut=1;
		

		foreach ($data['detail'] as $key) {
			$kode_laporan_thl = "'".$key->kode_laporan_thl."'";
			$kode_thl = "'".$key->kode_thl."'";
			$label = label_stat_laporan($key->stat_laporan_id);

			$tgl_kasie = ($key->tgl_verifikasi_kasie)? date('d-m-Y H:i:s',strtotime($key->tgl_verifikasi_kasie)):' - ';
			$tgl_kabid = ($key->tgl_verifikasi_kabid)? date('d-m-Y H:i:s',strtotime($key->tgl_verifikasi_kabid)):' - ';

			$output['data'][]=array(
				'nomor_urut' 		=> $nomor_urut,
				'kegiatan_thl_id'	=> $key->kegiatan_thl,
				'uraian'			=> $key->uraian,
				'waktu'				=> $key->waktu,
				'status' 			=> '<label class="label label-'.$label[0].'">'.$label[1].'</label>',
				'lampiran' 			=> '<a href="'.site_url().'_upload/'.$key->kode_thl.'/'.$key->kode_laporan_thl.'/'.$key->lampiran.'" target="_blank"><button type="button" class="btn btn-sm btn-outline-success">preview</button></a>',
				'verifikasi'		=> 'Kasie :'.$tgl_kasie.'<br>Kabid :'.$tgl_kabid,
				'action' 			=> ($key->stat_laporan_id == 1)?'<button type="button" class="btn btn-danger btn-circle btn-delete" onclick="hapus_detail_aktivitas('.$key->id.')"><i class="fas fa-trash"></i> </button>':''
			);
			// <button type="button" class="btn btn-info btn-circle btn-warning" onclick="update_kegiatan('.$key->id.')"><i class="fas fa-pencil-alt"></i> </button>
			$nomor_urut++;		
		}
		echo json_encode($output);
	} 

	function tabel_aktivitas_lain($kode_laporan_thl){

		$data['detail']= $this->M_pelaporan->get_detail_laporan_lain($kode_laporan_thl);

		$output['data']=array();
		$nomor_urut=1;
		

		foreach ($data['detail'] as $key) {
			$kode_laporan_thl = "'".$key->kode_laporan_thl."'";
			$kode_thl = "'".$key->kode_thl."'";
			$label = label_stat_laporan($key->stat_laporan_id);
			$tgl_kasie = ($key->tgl_verifikasi_kasie)? date('d-m-Y H:i:s',strtotime($key->tgl_verifikasi_kasie)):' - ';
			$tgl_kabid = ($key->tgl_verifikasi_kabid)? date('d-m-Y H:i:s',strtotime($key->tgl_verifikasi_kabid)):' - ';
			
			$output['data'][]=array(
				'nomor_urut' 			=> $nomor_urut,
				'uraian'				=> $key->uraian,
				'waktu'					=> $key->waktu,
				'status' 				=> '<label class="label label-'.$label[0].'">'.$label[1].'</label>',
				'lampiran' 				=> '<a href="'.site_url().'_upload/'.$key->kode_thl.'/'.$key->kode_laporan_thl.'/'.$key->lampiran.'" target="_blank"><button type="button" class="btn btn-sm btn-outline-success">preview</button></a>',
				'verifikasi'		=> 'Kasie :'.$tgl_kasie.'<br>Kabid :'.$tgl_kabid,
				'action' 			=> ($key->stat_laporan_id == 1)?'<button type="button" class="btn btn-danger btn-circle btn-delete" onclick="hapus_aktivitas_lain('.$key->id.','.$key->stat_laporan_id.')"><i class="fas fa-trash"></i> </button>':''

			);
			// <button type="button" class="btn btn-info btn-circle btn-warning" onclick="update_aktivitas_lain('.$key->id.')"><i class="fas fa-pencil-alt"></i> </button>
			$nomor_urut++;		
		}
		echo json_encode($output);
	} 

	function tambah_aktivitas_kegiatan(){
		$kode_thl = $this->session->userdata('kode_thl');
		$kode_laporan_thl = xss_filter($this->input->post('kode_laporan_thl'));
		$laporan = onetable_current_data('t_laporan_thl',['kode_laporan_thl' => $kode_laporan_thl]);
		// echo "<pre>";
		// var_dump($laporan);die;
		$daftar_kegiatan = array();

		$this->db->trans_begin();

			// upload 
		$target_path = "_upload/".$kode_thl."/".$kode_laporan_thl."/";
		if (! is_dir($target_path)) {
			mkdir($target_path, 0777, TRUE);
		}

			// laporan kegiatan
		$temp_kegiatan = array();$waktu = 0;
		for($i=0; $i < count($_FILES['lampiran_kegiatan']['name']);$i++){
			if(!empty($_FILES['lampiran_kegiatan']['name'][$i])){
				$kode = create_kode();

				$temp_kegiatan[] = array(
					'kode_laporan_thl'	=> $kode_laporan_thl,
					'kegiatan_thl_id'	=> xss_filter($this->input->post('kegiatan_thl_id')[$i]),
					'stat_laporan_id'	=> 1,
					'waktu' 			=> xss_filter($this->input->post('waktu_kegiatan')[$i]),
					'uraian' 			=> xss_filter($this->input->post('uraian_kegiatan')[$i]),
					'lampiran'			=> $kode.'.'.pathinfo($_FILES['lampiran_kegiatan']['name'][$i], PATHINFO_EXTENSION),
					'kode_spv_kabid' 	=> $laporan->kode_spv_kabid,
					'kode_spv_kasie'	=> $laporan->kode_spv_kasie
				);
				$waktu += $temp_kegiatan[$i]['waktu'];

				if (!move_uploaded_file($_FILES['lampiran_kegiatan']['tmp_name'][$i], $target_path .$temp_kegiatan[$i]['lampiran'])) {
					$this->db->trans_rollback();
					$this->output->set_content_type('json', 'utf-8');
					$this->output->set_output(array('error'=>TRUE, 'pesan'=>'could not move file'));
					die;
				}
				if(pathinfo($_FILES['lampiran_kegiatan']['name'][$i], PATHINFO_EXTENSION) != 'pdf'){
					re_image($target_path .$temp_kegiatan[$i]['lampiran']);						
				}
			}

		} 
		if (!empty($temp_kegiatan)) {
			$this->db->insert_batch('t_laporan_kegiatan_thl', $temp_kegiatan);

			$update = array(
				'kode_laporan_thl' 	=> $kode_laporan_thl,
				'jml_waktu' 		=> $laporan->jml_waktu + $waktu,
			);
			update_data('t_laporan_thl',$update);
		}

		$insert_log = array(
			'kode'			=> $kode_thl,
			'keterangan'	=> 'Menambah Aktivitas Kegiatan Laporan Harian Tanggal '.date("d-m-Y", strtotime($laporan->tgl_laporan)),
		);
		insert_data('trx_log_akun',$insert_log);
		$this->db->trans_commit();

		echo json_encode(array("status" => TRUE,"jml_waktu" => $update['jml_waktu']));
	}

	function tambah_aktivitas_lain(){
		$kode_thl = $this->session->userdata('kode_thl');
		$kode_laporan_thl = xss_filter($this->input->post('kode_laporan_thl'));
		$laporan = onetable_current_data('t_laporan_thl',['kode_laporan_thl' => $kode_laporan_thl]);
		// echo "<pre>";
		// var_dump($laporan);die;
		$daftar_kegiatan = array();

		$this->db->trans_begin();

		// upload 
		$target_path = "_upload/".$kode_thl."/".$kode_laporan_thl."/";
		if (! is_dir($target_path)) {
			mkdir($target_path, 0777, TRUE);
		}

		// laporan lainnya
		$temp_lainnya = array();$waktu = 0;
		for($i=0; $i < count($_FILES['lampiran_lainnya']['name']);$i++){
			if(!empty($_FILES['lampiran_lainnya']['name'][$i])){
				$kode = create_kode();

				$temp_lainnya[] = array(
					'kode_laporan_thl'	=> $kode_laporan_thl,
					'stat_laporan_id'	=> 1,
					'waktu' 			=> xss_filter($this->input->post('waktu_lainnya')[$i]),
					'uraian' 			=> xss_filter($this->input->post('uraian_lainnya')[$i]),
					'lampiran'			=> $kode.'.'.pathinfo($_FILES['lampiran_lainnya']['name'][$i], PATHINFO_EXTENSION),
					'kode_spv_kabid' 	=> $laporan->kode_spv_kabid,
					'kode_spv_kasie'	=> $laporan->kode_spv_kasie
				);

				$waktu += $temp_lainnya[$i]['waktu'];

				if (!move_uploaded_file($_FILES['lampiran_lainnya']['tmp_name'][$i], $target_path .$temp_lainnya[$i]['lampiran'])) {
					$this->db->trans_rollback();
					$this->output->set_content_type('json', 'utf-8');
					$this->output->set_output(array('error'=>TRUE, 'pesan'=>'could not move file'));
					die;
				}
				if(pathinfo($_FILES['lampiran_lainnya']['name'][$i], PATHINFO_EXTENSION) != 'pdf'){
					re_image($target_path .$temp_lainnya[$i]['lampiran']);						
				}
			}

		} 
		if (!empty($temp_lainnya)) {
			$this->db->insert_batch('t_laporan_lain_thl', $temp_lainnya);

			$update = array(
				'kode_laporan_thl' 	=> $kode_laporan_thl,
				'jml_waktu' 		=> $laporan->jml_waktu + $waktu,
			);
			update_data('t_laporan_thl',$update); 
		}


		$insert_log = array(
			'kode'			=> $kode_thl,
			'keterangan'	=> 'Menambah Aktivitas Kegiatan Lain Laporan Harian Tanggal '.date("d-m-Y", strtotime($laporan->tgl_laporan)),
		);
		insert_data('trx_log_akun',$insert_log);
		$this->db->trans_commit();

		echo json_encode(array("status" => TRUE,"jml_waktu" => $update['jml_waktu']));
	}

	// <!-- ============================================================== -->
	// <!--HAPUS PER DETAIL AKTIVITAS  -->
	// <!-- ============================================================== -->

	function hapus_detail_aktivitas($id){
		// $laporan = onetable_current_data('t_laporan_thl',['kode_laporan_thl' => $kode_laporan_thl]);
		$kegiatan    = $this->M_pelaporan->get_detail_kegiatan($id);
		$jml_waktu = $kegiatan->jml_waktu - $kegiatan->waktu;
		$kode_thl = $this->session->userdata('kode_thl');

		$url ="_upload/".$kode_thl."/".$kegiatan->kode_laporan_thl."/".$kegiatan->lampiran;
		unlink($url);

		delete_data('t_laporan_kegiatan_thl',['id' => $id]);
		$update = array(
			'kode_laporan_thl' 	=> $kegiatan->kode_laporan_thl,
			'jml_waktu' 		=> $jml_waktu,
		);
		update_data('t_laporan_thl',$update);

		$insert_log = array(
			'kode'			=> $this->session->userdata('kode_thl'),
			'keterangan'	=> 'Menghapus Aktivitas "'.$kegiatan->uraian.'" Harian Tanggal '.date("d-m-Y", strtotime($kegiatan->tgl_laporan)),
		);
		insert_data('trx_log_akun',$insert_log);

		// $this->M_pelaporan->update_total_jml($where,$jml);
		// var_dump($kode_thl);
		
		echo json_encode(array("status" => TRUE,"jml_waktu" => $jml_waktu));
	}

	function hapus_aktivitas_lain($id){
		$kegiatan_lain  = $this->M_pelaporan->get_detail_lain($id);
		$jml_waktu 		= $kegiatan_lain->jml_waktu - $kegiatan_lain->waktu;
		$kode_thl 		= $this->session->userdata('kode_thl');

		$url ="_upload/".$kode_thl."/".$kegiatan_lain->kode_laporan_thl."/".$kegiatan_lain->lampiran;
		unlink($url);

		delete_data('t_laporan_lain_thl',['id' => $id]);
		$update = array(
			'kode_laporan_thl' 	=> $kegiatan_lain->kode_laporan_thl,
			'jml_waktu' 		=> $jml_waktu,
		);
		update_data('t_laporan_thl',$update);

		$insert_log = array(
			'kode'			=> $this->session->userdata('kode_thl'),
			'keterangan'	=> 'Menghapus Aktivitas Lain"'.$kegiatan_lain->uraian.'" Harian Tanggal '.date("d-m-Y", strtotime($kegiatan_lain->tgl_laporan)),
		);
		insert_data('trx_log_akun',$insert_log);
		
		echo json_encode(array("status" => TRUE,"jml_waktu" => $jml_waktu));
	}

	// <!-- ============================================================== -->
	// <!-- FUNGSI UNTUK DETAIL BEFORE UPDATE  -->
	// <!-- ============================================================== -->

	function det_identitas_pelapor_andtabel(){
		$kode_laporan_thl = xss_filter($this->input->post('kode_laporan_thl'));
		$data['detail']= $this->M_pelaporan->get_detail_laporan($kode_laporan_thl);
		
		$det_pelapor = $this->M_pelaporan->get_identitas_pelapor($kode_laporan_thl);

		$output['data']=array();
		$nomor_urut=1;
		foreach ($data['detail'] as $key) {
			$output['data'][]=array(
				'nomor_urut' 			=> $nomor_urut,
				'kode_laporan_thl' 		=> $key->kode_laporan_thl,
				'kegiatan_thl_id' 		=> $key->kegiatan_thl,
				'waktu'					=> $key->waktu,
				'uraian'				=> $key->uraian,
				'lampiran' 				=> '<a href="'.site_url().'_upload/'.$key->kode_thl.'/'.$key->kode_laporan_thl.'/'.$key->lampiran.'" target="_blank"><img src="'.site_url().'_upload/'.$key->kode_thl.'/'.$key->kode_laporan_thl.'/'.$key->lampiran.' " width="50px"></a>'

			);
			$nomor_urut++;		
		}

		header('Content-Type:application/json');
		echo json_encode($output +$det_pelapor);
	}


	function data_update_kegiatan($id){
		$kegiatan = $this->M_pelaporan->get_data_kegiatan($id);
		echo json_encode($kegiatan);
		//var_dump($kegiatan);
	}

	function update_aktivitas_lain($id){
		$kegiatan_lain = $this->M_pelaporan->get_laporan_lain($id);
		echo json_encode($kegiatan_lain);
	}

	
	function tambah_aktivitas_cc(){
		$kode_laporan_thl = create_kode();
		$kode_thl = "121212";

		$path = "_upload/".$kode_thl."/".$kode_laporan_thl;

		if(!is_dir($path)) 
		{
			mkdir($path,0755,TRUE);
		} 

		// $daftar_kegiatan = array();

		// $tgl_laporan = $this->input->post('tgl_laporan');  
		// $newDate = date("Y-m-d", strtotime($tgl_laporan)); 

		// $jml_waktu =0;
		
		// for ($i=0; $i <count($_POST['kegiatan_thl_id']) ; $i++) {

		// 	$jml_waktu += $_POST['waktu'][$i];
		// }

		// 	$daftar_laporan = array(
		// 		'kode_laporan_thl' 		=> xss_filter($kode_laporan_thl),
		// 		'skpd_id' 				=> 29,
		// 		'profesi_thl_id'		=> 1,
		// 		'kode_thl' 				=> 121212,
		// 		'kode_spv' 				=> $this->input->post('kode_spv'),
		// 		'stat_laporan_id' 		=> 1,
		// 		'jml_waktu'				=> $jml_waktu,
		// 		'tgl_laporan' 			=> $newDate,
		// 		'created_by'			=> 121212
		// 	);

		// 	$this->M_pelaporan->post_laporan_thl($daftar_laporan);
		// 	for ($i=0; $i <count($_POST['kegiatan_thl_id']) ; $i++) {
		// 		$temp = array(
		// 			'kode_laporan_thl'	=> $kode_laporan_thl,
		// 			'kegiatan_thl_id'	=> $_POST['kegiatan_thl_id'][$i],
		// 			'waktu' 			=> $_POST['waktu'][$i],
		// 			'uraian' 			=> $_POST['uraian'][$i],
		// 			'lampiran'			=> $_POST['uraian'][$i]

		// 		);

		// 		$this->M_pelaporan->post_detail_laporan_thl($temp);


		// 	}
		// 	redirect('Pelaporan');
		$countfiles = count($_FILES['files']['name']);
		var_dump(count($_POST['kegiatan_thl_id']));
		for ($i=0; $i <count($_POST['kegiatan_thl_id']); $i++) { 
			
		}

	}

	

	function cektgl(){
		$data = array(
			'tgl_laporan'   => $this->input->post('tgl_laporan'),
		);
		$this->M_pelaporan->cektgl($data);
	}

	function coba_session(){

		$kode_thl = $this->session->userdata('kode_thl');
		var_dump($kode_thl);
	}

}