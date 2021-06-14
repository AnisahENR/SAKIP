<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_data extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		// $this->load->model('m_admin');
		// $this->load->model('m_skpd');
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
		$this->load->view('v_master_perangkat_daerah');
		$this->load->view('templates/material_pro/footer-1');
		$this->load->view('footer-custom');
		$this->load->view('templates/material_pro/footer-2');
	}

	function view_master_golongan(){


		$this->load->view('templates/material_pro/head');
		$this->load->view('header-custom');
		$this->load->view('templates/material_pro/header');
		$this->load->view('templates/material_pro/sidebar');

		//insert view here
		$this->load->view('v_master_golongan');
		$this->load->view('templates/material_pro/footer-1');
		$this->load->view('footer-custom');
		$this->load->view('templates/material_pro/footer-2');



	}

	function view_master_jabatan(){


		$this->load->view('templates/material_pro/head');
		$this->load->view('header-custom');
		$this->load->view('templates/material_pro/header');
		$this->load->view('templates/material_pro/sidebar');

		//insert view here
		$this->load->view('v_master_jabatan');
		$this->load->view('templates/material_pro/footer-1');
		$this->load->view('footer-custom');
		$this->load->view('templates/material_pro/footer-2');



	}

}