<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Pegawai extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index(){
		$this->load->view('templates/material_pro/head');

		//insert custom header here
		$this->load->view('header-custom');

		$this->load->view('templates/material_pro/header');
		$this->load->view('templates/material_pro/sidebar');

		//insert view here
		$this->load->view('v_pegawai');

		$this->load->view('templates/material_pro/footer-1');

		// insert custom footer here
		$this->load->view('footer-custom');

		$this->load->view('templates/material_pro/footer-2');
	}
}

