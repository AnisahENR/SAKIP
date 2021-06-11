<?php defined('BASEPATH')  OR exit('No direct script access allowed');

/**
 * 
 */
class Cetak extends MX_Controller
{
	
	function __construct(){
		parent::__construct();
        // if (!$this->session->userdata('is_login')) {
        //     redirect('login');
        // }
	}

	function laporan_aktivitas_bulanan(){
		$this->load->view('templates/material_pro/head');
		$this->load->view('templates/material_pro/header');
		$this->load->view('v_laporan_aktivitas_bulanan_header');
		$this->load->view('templates/material_pro/sidebar');

		$this->load->view('v_laporan_aktivitas_bulanan');
		
		$this->load->view('templates/material_pro/footer-1');
		$this->load->view('v_laporan_aktivitas_bulanan_footer');
		$this->load->view('templates/material_pro/footer-2');
	}

	function laporan_target_bulanan(){
		$this->load->view('templates/material_pro/head');
		$this->load->view('templates/material_pro/header');
		$this->load->view('v_laporan_target_bulanan_header');
		$this->load->view('templates/material_pro/sidebar');

		$this->load->view('v_laporan_target_bulanan');
		
		$this->load->view('templates/material_pro/footer-1');
		$this->load->view('v_laporan_target_bulanan_footer');
		$this->load->view('templates/material_pro/footer-2');
	}

	function get_laporan_bulanan_select()
	{
		$this->load->model('m_laporan_bulanan');
		$author_id 	= $this->session->userdata('author_id');
		$kode		= NULL;
		if($author_id == 6)
			$kode	= $this->session->userdata('kode_thl');
		else
			$kode	= $this->session->userdata('kode_spv');

		$thl  		= $this->m_laporan_bulanan->get_thl_list(array(	'param_author'	=> $author_id,
																	'param_kode'	=> $kode));
		mysqli_next_result( $this->db->conn_id );

		$spv 		= $this->m_laporan_bulanan->get_spv_list(array(	'param_author'	=> $author_id,
																	'param_kode'	=> $kode));
		mysqli_next_result( $this->db->conn_id );

		$data = array(	'thl' 	=> $thl,
						'spv'	=> $spv);

        $this->output->set_content_type('json', 'utf-8');
        $this->output->set_output(json_encode($data));
	}

	private function get_detail_thl($kode_thl)
	{
		$this->load->model('m_laporan_bulanan');
		if (!empty($kode_thl))
		{
			$data = $this->m_laporan_bulanan->get_detail_thl(array('param_kode'	=> $kode_thl));
			mysqli_next_result( $this->db->conn_id );

	        return $data;
		}
		else
        {
            return null;
        }
	}

	private function get_laporan_aktivitas_thl($kode_thl, $bulan)
	{
		$author_id 	= $this->session->userdata('author_id');
		$this->load->model('m_laporan_bulanan');
		if (!empty($kode_thl) || !empty($bulan))
		{
			$data = $this->m_laporan_bulanan->get_laporan_aktivitas_thl(array(	'param_kode'	=> $kode_thl,
																				'param_month'	=> $bulan,
																				'param_author'	=> $author_id));
			mysqli_next_result( $this->db->conn_id );

	        return $data;
		}
		else
        {
            return null;
        }
	}

	private function get_laporan_target_thl($kode_thl, $bulan)
	{
		$author_id 	= $this->session->userdata('author_id');
		$this->load->model('m_laporan_bulanan');
		if (!empty($kode_thl) || !empty($bulan))
		{
			$data = $this->m_laporan_bulanan->get_laporan_target_thl(array(	'param_kode'	=> $kode_thl,
																			'param_month'	=> $bulan,
																			'param_author'	=> $author_id));
			mysqli_next_result( $this->db->conn_id );

	        return $data;
		}
		else
        {
            return null;
        }
	}

	private function get_mengetahui($kode_kadis, $kode_kabid)
	{
		$this->load->model('m_laporan_bulanan');
		if (!empty($kode_kadis) || !empty($kode_kabid))
		{
			$data = $this->m_laporan_bulanan->get_mengetahui(array(	'param_kode_kadis'	=> $kode_kadis,
																	'param_kode_kabid'	=> $kode_kabid));
			mysqli_next_result( $this->db->conn_id );

	        return $data;
		}
		else
        {
            return null;
        }
	}

	function get_laporan_aktivitas_bulanan_thl($output = TRUE)
	{
        $kode_thl   = xss_filter($this->input->post('thl'));
        $bulan      = xss_filter($this->input->post('bulan'));
        $kadis      = xss_filter($this->input->post('kadis'));
        $kabid      = xss_filter($this->input->post('kabid'));
		if($output)
		{
			if (!empty($kode_thl) && !empty($bulan))
			{
				$data = array(	'detail_thl'  => $this->get_detail_thl($kode_thl),
								'laporan_thl' => $this->get_laporan_aktivitas_thl($kode_thl, $bulan . '-01'));

		        $this->output->set_content_type('json', 'utf-8');
		        $this->output->set_output(json_encode($data));
			}
			else
	        {
	            $this->output->set_status_header(400);
	            $this->output->set_output(json_encode("Data input error!"));
	        }
	    }
	    else
	    {
	    	$data = array(	'detail_thl'  => $this->get_detail_thl($kode_thl),
							'laporan_thl' => $this->get_laporan_aktivitas_thl($kode_thl, $bulan . '-01'),
							'mengetahui'  => $this->get_mengetahui($kadis, $kabid));
	    	return $data;
	    }
	}

	function get_laporan_target_bulanan_thl($output = TRUE)
	{
        $kode_thl   = xss_filter($this->input->post('thl'));
        $bulan      = xss_filter($this->input->post('bulan'));
        $kadis      = xss_filter($this->input->post('kadis'));
        $kabid      = xss_filter($this->input->post('kabid'));
		if($output)
		{
			if (!empty($kode_thl) && !empty($bulan))
			{
				$data = array(	'detail_thl'  => $this->get_detail_thl($kode_thl),
								'laporan_thl' => $this->get_laporan_target_thl($kode_thl, $bulan . '-01'));

		        $this->output->set_content_type('json', 'utf-8');
		        $this->output->set_output(json_encode($data));
			}
			else
	        {
	            $this->output->set_status_header(400);
	            $this->output->set_output(json_encode("Data input error!"));
	        }
	    }
	    else
	    {
	    	$data = array(	'detail_thl'  => $this->get_detail_thl($kode_thl),
							'laporan_thl' => $this->get_laporan_target_thl($kode_thl, $bulan . '-01'),
							'mengetahui'  => $this->get_mengetahui($kadis, $kabid));
	    	return $data;
	    }
	}
}