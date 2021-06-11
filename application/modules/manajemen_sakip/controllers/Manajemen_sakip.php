<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manajemen_sakip extends CI_Controller {

	function __construct() {
		parent::__construct();

	}
	
	public function index()
	{
		$this->load->view('templates/material_pro/head');

		// insert custom header here
		$this->load->view('header-custom');

		$this->load->view('templates/material_pro/header');
		$this->load->view('templates/material_pro/sidebar');

		//insert view here
		$this->load->view('v_manajemen_sakip');

		$this->load->view('templates/material_pro/footer-1');

		// insert custom footer here
		$this->load->view('footer-custom');

		$this->load->view('templates/material_pro/footer-2');
	}

	public function tambah_sakip(){
		$this->load->view('templates/material_pro/head');

		// insert custom header here
		$this->load->view('header-custom');

		$this->load->view('templates/material_pro/header');
		$this->load->view('templates/material_pro/sidebar');

		//insert view here
		$this->load->view('v_form_sakip_kaban');

		$this->load->view('templates/material_pro/footer-1');

		// insert custom footer here
		$this->load->view('footer-custom');

		$this->load->view('templates/material_pro/footer-2');
	}


	public function detail_sakip(){
		$this->load->view('templates/material_pro/head');

		// insert custom header here
		$this->load->view('header-custom');

		$this->load->view('templates/material_pro/header');
		$this->load->view('templates/material_pro/sidebar');

		//insert view here
		$this->load->view('v_detail_sakip');

		$this->load->view('templates/material_pro/footer-1');

		// insert custom footer here
		$this->load->view('footer-custom');

		$this->load->view('templates/material_pro/footer-2');
	}

}
