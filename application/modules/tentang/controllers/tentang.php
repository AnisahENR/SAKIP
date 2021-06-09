<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tentang extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_login')) {
            redirect('login');
        }
    }
	
	public function index()
	{
		$this->load->view('templates/material_pro/head');

		// insert custom header here
		// $this->load->view('header-custom');

		$this->load->view('templates/material_pro/header');
		$this->load->view('templates/material_pro/sidebar');

		//insert view here
		$this->load->view('v-tentang');

		$this->load->view('templates/material_pro/footer-1');

		// insert custom footer here
		// $this->load->view('footer-custom');

		$this->load->view('templates/material_pro/footer-2');
	}

	public function goTo500()
	{
		show_error("Pesan", 500, "Judul");
	}
}
