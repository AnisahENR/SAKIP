<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Templates extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function material_pro()
	{
		$this->load->view('templates/material_pro/head');

		// insert custom header here
		// $this->load->view('header-custom');

		$this->load->view('templates/material_pro/header');
		$this->load->view('templates/material_pro/sidebar');

		//insert view here
		$this->load->view('material_pro');

		$this->load->view('templates/material_pro/footer-1');

		// insert custom footer here
		// $this->load->view('footer-custom');

		$this->load->view('templates/material_pro/footer-2');
	}

	public function login_material_pro()
	{
		//insert view here
		$this->load->view('login_material_pro');
	}

	public function sendMail()
	{

		$config = Array(
		    'protocol' 		=> 'smtp',
		    'smtp_crypto' 	=> 'ssl',
		    'smtp_host' 	=> 'smtp.googlemail.com',
		    'smtp_port' 	=> 465,
		    'smtp_user' 	=> 'email.burner@gmail.com',
		    'smtp_pass' 	=> '12345678'
		);
		$this->load->library('email', $config);

		$this->email->set_newline("\r\n");

		$this->email->from('no-reply@bigcompany.com');
		$this->email->to('email@gmail.com');

		$this->email->subject('Subject is Important');
		$this->email->message('Message is also important.');

		$this->email->send();
		echo $this->email->print_debugger();
	}
}
