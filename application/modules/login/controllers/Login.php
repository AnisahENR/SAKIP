<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
        // $this->load->model('m_login');
        // $this->load->helper('string');
	}

	function index() {
 //        $username = xss_filter($this->input->post('username'));
 //        $password = xss_filter($this->input->post('password'));
 //        $remember = xss_filter($this->input->post('remember'));

 //        if (empty($username) && empty($password) && empty($remember)) {	
	//         $cookie = get_cookie('user');

	//         if ($this->session->userdata('is_login')) {
	//             redirect('beranda');
	//         } else if($cookie <> '') {
	//             $row = $this->m_login->get_cookie($cookie)->row();
	//             mysqli_next_result( $this->db->conn_id );
	//             if ($row) {
	//                 $this->set_session($row);
	//             } else {
	//                 $this->load->view('v-login');
	//             }
	//         } else {
	//             $this->load->view('v-login');
	//         }
 //        }
 //        else {
 //        	$row = $this->m_login->login($username);
 //        	mysqli_next_result( $this->db->conn_id );
	//         if ($row) {
	//         	// Users is inactive
	//         	if ($row['status_akun'] != 1) {
	//             	$this->load->view('v-login', array('status' => 'error', 'message' => 'Akun anda tidak aktif!' ));

	//             // Check Password
	//         	} else if (password_verify($password, $row['password'])) {
	// 	            if ($remember) {
	// 	                $key = random_string('alnum', 64);
	// 	                set_cookie('user', $key, 3600*24*30); //30 days

	// 	                $update_key = array(
	// 	                    'cookie' => $key
	// 	                );
	// 	                $this->m_login->update_cookie($update_key, $row['id_akun']);
	// 	                mysqli_next_result( $this->db->conn_id );
	// 	            }
	// 	            $this->set_session($row);
	// 	        } else {
	            	$this->load->view('v-login');
	// 	        }
	//         } else {
	//             $this->load->view('v-login', array('status' => 'error', 'message' => 'Username tidak ditemukan!' ));
	//         }
 //        }
    }

 //    function set_session($row) {
 //        $session = $row;
 //        $session['is_login'] = TRUE;
 //        unset($session['password']);
 //        $this->session->set_userdata($session);

 //        redirect('beranda');
 //    }

	// function logout()
 //    {
 //        delete_cookie('user');
 //        $this->session->sess_destroy();
 //        redirect('login');
 //    }
}
