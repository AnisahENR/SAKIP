<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('m_admin');
		$this->load->model('m_skpd');
		// if (!$this->session->userdata('is_login') || $this->session->userdata('author_id') != 1) {
		// 	redirect('login');
		// }
	}

	function index() {
		
		$this->load->view('templates/material_pro/head');
		$this->load->view('header-custom');
		$this->load->view('templates/material_pro/header');
		$this->load->view('templates/material_pro/sidebar');

		//insert view here
		$this->load->view('v_admin');
		$this->load->view('templates/material_pro/footer-1');
		$this->load->view('footer-custom');
		$this->load->view('templates/material_pro/footer-2');
	}

	// FUNCTION GET LIST SKPD
	function get_listSkpd(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		};

		$temp = $this->m_admin->get_skpdon_admin();
 		mysqli_next_result( $this->db->conn_id );

		$output['data'] = array();
		$nomor_urut=1;

		foreach ($temp as $key) {
			$output['data'][] 	= array(
				'nomor_urut' => $nomor_urut,
				'id'  		 => $key['id'],
				'deskripsi'  => $key['deskripsi']
			);
			$nomor_urut++;
		};

		$this->output->set_content_type('json', 'utf-8');
        $this->output->set_output(json_encode($output));
	}

	// FUNCTION SHOW LIST DATA AUTHOR
	function get_admin(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		};

		$temp = $this->m_admin->get_admin();

		$output['data'] = array();
		$nomor_urut=1;
		foreach ($temp as $key) {
			$label = label_stat_akun($key['stat_akun_id']);
			$output['data'][] 	= array(
				'nomor_urut' => $nomor_urut,
				'nama_skpd'  => $key['nama_skpd'],
				'nama'  	 => $key['nama'],
				'username'   => $key['username'],
				'bidang' 	 => $key['bidang'],
				'jabatan'  	 => $key['jabatan'],
				'status_akun'=> '<button class="btn-status btn btn-sm waves-effect waves-light btn-'.$label.'" data-username="'.$key['username'].'"  data-kode="'.$key['kode'].'" data-status-id="'.$key['stat_akun_id'].'">'.$key['status'].'</button>',
				'opsi' 		 => '<div class="btn-group"><a href="'.site_url("mgmt_spv/detail_spv/detail_spv/".$key['kode']).'" class="btn btn-info" title="Detail"><i class="fas fa-info-circle"></i></a><button class="btn btn-warning btn-detail-author" data-kode="'.$key['kode'].'" data-skpd-id="'.$key['skpd_id'].'"><i class="fas fa-pencil-alt"></i>  </button> <button class="btn btn-danger delete_admin"  data-kode="'.$key['kode'].'"><i class="fas fa-trash-alt"></i></button></div>',

			);
			$nomor_urut++;
		};

		$this->output->set_content_type('json', 'utf-8');
        $this->output->set_output(json_encode($output));
	}

	// FUNCTION GET DETAIL ADMIN 

	function get_det_admin(){
		$output['detail'] = $this->m_admin->get_det_admin(xss_filter($this->input->post('kode')));

		$temp = $this->m_admin->getbidang_basedskpd(xss_filter($this->input->post('skpd_id')));
		$output['data'] = array();
		$nomor_urut=1;
		foreach ($temp as $key) {
			$output['data'][] 	= array(
				'nomor_urut' => $nomor_urut,
				'deskripsi'  => $key['deskripsi'],
				'id'  	 	 => $key['id'],
				'skpd_id'  	 => $key['skpd_id'],
			);
			$nomor_urut++;
		};
		header('Content-Type:application/json');
		echo json_encode($output);

	}

	// FUNCTION GET BIDANG WHERE SKPD
	function getbidang_basedskpd(){
		$temp = $this->m_admin->getbidang_basedskpd(xss_filter($this->input->post('skpd_id')));
		$output['data'] = array();
		$nomor_urut=1;
		foreach ($temp as $key) {
			$output['data'][] 	= array(
				'nomor_urut' => $nomor_urut,
				'deskripsi'  => $key['deskripsi'],
				'id'  	 	 => $key['id'],
				'skpd_id'  	 => $key['skpd_id']
			);
			$nomor_urut++;
		};

		header('Content-Type:application/json');
		echo json_encode($output);
	}

	// FUNCTION ADD BIDANG
	function add_admin(){
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		};
		$kode_spv = create_kode();
		$data_spv = array(
			'kode_spv'  		=> $kode_spv,
			'nama' 				=> xss_filter($this->input->post('nama')),
			'nip'	 			=> xss_filter($this->input->post('nip')),
			'jabatan_spv_id' 	=> 2, // value ini sudah diset di db dengan manual dengan author 2
			'skpd_id' 			=> xss_filter($this->input->post('skpd_id')),
			'bidang_skpd_id' 	=> xss_filter($this->input->post('bidang_skpd_id')),
			'created_by'		=> $this->session->userdata('kode_spv'),
		);

		$akun_spv = array(
			'kode'  		=> $kode_spv,
			'author_id' 	=> 2,
			'username' 		=> xss_filter($this->input->post('username')),
			'password' 		=> xss_filter($this->bcrypt->hash_password($this->input->post('password'))),
		);

		$this->db->trans_begin();
		insert_data('t_spv',$data_spv);
		insert_data('t_akun',$akun_spv);
		$insert_log = array(
			'kode'			=> $this->session->userdata('kode_spv'),
			'keterangan'	=> 'Registrasi SPV (admin) Baru '.$data_spv['nama'],
		);

		insert_data('trx_log_akun',$insert_log);
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$result['error'] = true;
			$result['pesan'] = 'Gagal '.$insert_log['keterangan'];
		}else{
			$this->db->trans_commit();
			$result['error'] = false;
			$result['pesan'] = 'Berhasil '.$insert_log['keterangan'];
		};	

		header('Content-Type:application/json');
		echo json_encode($result);
	}

	// FUNCTION UPDATE ADMIN
	function update_admin(){
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		};

		$data = array(
			'kode_spv'  		=> xss_filter($this->input->post('kode_spv')),
			'nama' 				=> xss_filter($this->input->post('nama')),
			'nip'	 			=> xss_filter($this->input->post('nip')),
			'bidang_skpd_id' 	=> xss_filter($this->input->post('bidang_skpd_id')),
		);

		$detailAkun = $this->m_admin->getDetailAkun($data['kode_spv']);

		$this->db->trans_begin();
		update_data('t_spv',$data);
		$insert_log = array(
			'kode'			=> $this->session->userdata('kode_spv'),
			'keterangan'	=> 'Update data admin '.ucfirst($detailAkun[0]['username']),
		);

		insert_data('trx_log_akun',$insert_log);
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$result['error'] = true;
			$result['pesan'] = 'Gagal,'.$insert_log['keterangan'];
		}else{
			$this->db->trans_commit();
			$result['error'] = false;
			// $result['kode']  = 'tambah_spv';
			$result['pesan'] = 'Berhasil,'.$insert_log['keterangan'];
		};	

		header('Content-Type:application/json');
		echo json_encode($result);
	}


	// FUNCTION UPDATE STATUS
	function update_status(){
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		};

		$status_id='';$status='';
		$this->db->trans_begin();
		switch (xss_filter($this->input->post('status_id'))) {
			case 1:
			$status_id = 2;
			$status = 'Non-Aktif';
			break;
			case 2:
			$status_id = 1;
			$status = 'Aktif';
			break;
		}
		$update = array(
			'kode'			=> xss_filter($this->input->post('kode')),
			'stat_akun_id'	=> $status_id,
		);

		update_data('t_akun',$update);
		$insert = array(
			'kode'			=> $this->session->userdata('kode_spv'),
			'keterangan'	=> 'Perubahan Status username '.ucfirst(xss_filter($this->input->post('username'))).' Menjadi '.$status,
		);
		insert_data('trx_log_akun',$insert);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$result['error'] = true;
			$result['pesan'] = 'Gagal,'.$insert['keterangan'];
		}else{
			$this->db->trans_commit();
			$result['error'] = false;
			$result['pesan'] = 'Berhasil,'.$insert['keterangan'];
		};
		header('Content-Type:application/json');
		echo json_encode($result);
	}

	// FUNCTION CHCK USERNAME
	function check_username(){
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		};

		$temp = $this->m_admin->check_username(xss_filter($this->input->post('username')));

		if (empty($temp)) {
			$result['error'] = false;
			$result['pesan'] = 'Username tersedia';
		}else{
			$result['error'] = true;
			$result['pesan'] = 'Username tidak tersedia';
		}

		header('Content-Type:application/json');
		echo json_encode($result);

	}


	function delete_admin(){
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		};

		$data = array('kode' => xss_filter($this->input->post('kode')));

		$detailAkun = $this->m_admin->getDetailAkun($data['kode']);

		$this->db->trans_begin();
			delete_data('t_akun',['kode' => $data['kode']]);
			delete_data('t_spv',['kode_spv' => $data['kode']]);

		$insert_log = array(
			'kode'			=> $this->session->userdata('kode_spv'),
			'keterangan'	=> 'Menghapus akun admin  '.ucfirst($detailAkun[0]['username']),
		);

		insert_data('trx_log_akun',$insert_log);
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$result['error'] = true;
			$result['pesan'] = 'Gagal '.$insert_log['keterangan'];
		}else{
			$this->db->trans_commit();
			$result['error'] = false;
			$result['pesan'] = 'Berhasil '.$insert_log['keterangan'];
		};
		header('Content-Type:application/json');
		echo json_encode($result);
	}

	
}