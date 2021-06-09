<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

    function __construct() {
        parent::__construct();
        // if (!$this->session->userdata('is_login')) {
        //     if($this->input->is_ajax_request()) {    
        //         $this->output->set_status_header(401);
        //         $this->output->set_output(json_encode("Unauthorized!"));
        //         exit;
        //     }
        //     else
        //         redirect('login');
        // }
    }

	public function index()
	{
		$this->load->view('templates/material_pro/head');

		// insert custom header here
		$this->load->view('header-custom');

		$this->load->view('templates/material_pro/header');
		$this->load->view('templates/material_pro/sidebar');

		//insert view here
		$this->load->view('v-profil');

		$this->load->view('templates/material_pro/footer-1');

		// insert custom footer here
		$this->load->view('footer-custom');

		$this->load->view('templates/material_pro/footer-2');
	}

	public function getFormData()
    {
		$this->load->model('m_profil');

		$data  = array(	'status_perkawinan' => $this->m_profil->get_status_perkawinan_form(),
						'provinsi'			=> $this->m_profil->get_provinsi_form(),
						'wilayah'			=> $this->m_profil->get_wilayah_form(),
						'pendidikan'		=> $this->m_profil->get_pendidikan_form());

        $this->output->set_content_type('json', 'utf-8');
        $this->output->set_output(json_encode($data));
	}

	public function getProfil()
    {
		$author_id 	= $this->session->userdata('author_id');
		$kode		= NULL;
        if($author_id == 6)
            $kode   = $this->session->userdata('kode_thl');
        else
            $kode   = $this->session->userdata('kode_spv');

        $this->load->model('m_profil');
		$data = $this->m_profil->get_profil(array(	'param_author'	=> $author_id,
													'param_kode'	=> $kode));

		mysqli_next_result( $this->db->conn_id );

        $this->output->set_content_type('json', 'utf-8');
        $this->output->set_output(json_encode($data));
	}

	public function editProfil()
    {
        $this->load->model('m_profil');

		$author_id 	= $this->session->userdata('author_id');
		$kode		= $this->session->userdata('kode_thl');

		$telepon 			= xss_filter($this->input->post('telepon'));
		$email 				= xss_filter($this->input->post('email'));
		$tanggal_lahir 		= xss_filter($this->input->post('tanggal-lahir'));
		$wilayah_lahir 		= xss_filter($this->input->post('wilayah-lahir'));
		$status_perkawinan 	= xss_filter($this->input->post('status-perkawinan'));
		$alamat 			= xss_filter($this->input->post('alamat'));
		$wilayah_alamat 	= xss_filter($this->input->post('wilayah-alamat'));
        $this->output->set_content_type('json', 'utf-8');
        if (!empty($kode))
        {
        	$is_error = FALSE;
        	if ($author_id == 6)
		        if (empty($telepon) || empty($email) || empty($tanggal_lahir) || empty($wilayah_lahir) || empty($status_perkawinan) || empty($alamat) || empty($wilayah_alamat))
		    		$is_error = TRUE;
                else
                    $is_error = FALSE;
		    else
	    		$is_error = TRUE;
            
			if(!$is_error)
			{
	        	$result = $this->m_profil->edit_profil(array(	'param_kode' 				=> $kode,
	        													'param_author'				=> $author_id,
	        													'param_telepon' 			=> $telepon,
	        													'param_email'				=> $email,
	        													'param_tanggal_lahir' 		=> $tanggal_lahir,
	        													'param_wilayah_lahir' 		=> $wilayah_lahir,
	        													'param_status_perkawinan'	=> $status_perkawinan,
	        													'param_alamat' 				=> $alamat,
	        													'param_wilayah_alamat' 		=> $wilayah_alamat));
	        	mysqli_next_result( $this->db->conn_id );
	        	if($result->result == 0)
	        	{
	            	$this->output->set_output(json_encode("Edit profil berhasil!"));
				}
				else
				{
					$this->output->set_status_header(400);
	            	$this->output->set_output(json_encode("Edit profil gagal!"));
				}
			}
			else
			{				
            	$this->output->set_status_header(400);
            	$this->output->set_output(json_encode("Data input error!"));
			}
        }
        else
        {
            $this->output->set_status_header(400);
            $this->output->set_output(json_encode("Data input error!"));
        }
	}

	public function editPassword()
    {		
        $this->load->model('m_profil');

		$akun_id 	= $this->session->userdata('akun_id');
		$password 	= xss_filter($this->input->post('password'));
        $this->output->set_content_type('json', 'utf-8');
        if (!empty($akun_id) && !empty($password))
        {

        	$password = password_hash($password, PASSWORD_DEFAULT);

        	$result = $this->m_profil->edit_password(array(	'param_id' 			=> $akun_id,
        													'param_password' 	=> $password));
        	mysqli_next_result( $this->db->conn_id );
        	if($result->result == 0)
        	{
            	$this->output->set_output(json_encode("Edit password berhasil!"));
			}
			else
			{
				$this->output->set_status_header(400);
            	$this->output->set_output(json_encode("Edit profil gagal!"));
			}
        }
        else
        {
            $this->output->set_status_header(400);
            $this->output->set_output(json_encode("Data input error!"));
        }
	}

	public function getPendidikanTable() {
		$author_id 	  = $this->session->userdata('author_id');
		$kode         = $this->session->userdata('kode_thl');

        $this->load->model('m_profil');

		$list = $this->m_profil->get_pendidikan($kode);
        $data = array();
        $no = $_POST['start'];

        $path = base_url() . '_upload/' . $kode . '/';

        foreach ($list as $pendidikan) {
            $no++;
            $row = array();
            $row['no']          	= $no;
            $row['institusi'] 		= $pendidikan->institusi;
            $row['jenjang'] 		= $pendidikan->jenjang;
            $row['lampiran'] 		= '<button type="button" class="btn btn-info" onclick="window.open(\'' . $path . $pendidikan->lampiran . '\')"><i class="fas fa-file"></i></button>';
            $row['action'] 			= '<button type="button" class="btn btn-info"
                                            data-toggle="modal"
                                            data-target="#modal-edit-pendidikan"
                                            data-id="' . $pendidikan->id . '"
                                            data-institusi="' . $pendidikan->institusi . '"
                                            data-jenjang="' . $pendidikan->jenjang_id . '"><i class="fas fa-pencil-alt"></i></button>';
 
            $data[] = $row;
        }
 
        $output = array(
                "draw" 				=> $_POST['draw'],
                "recordsTotal" 		=> $this->m_profil->count_all_pendidikan($kode),
                "recordsFiltered" 	=> $this->m_profil->count_filtered_pendidikan($kode),
                "data" 				=> $data
        );

        $this->output->set_content_type('json', 'utf-8');
        $this->output->set_output(json_encode($output));
	}

    public function editPendidikan()
    {
        $this->load->model('m_profil');

        $akun_id    = $this->session->userdata('akun_id');
        $kode       = $this->session->userdata('kode_thl');

        if (isset($_FILES['lampiran']['name']))
            if (!empty($_FILES['lampiran']['name']))
                $filename   = $kode . '.' . pathinfo($_FILES['lampiran']['name'], PATHINFO_EXTENSION);
            else
                $filename   = null;
        else
            $filename   = null;

        $path = FCPATH . '_upload/' . $kode . '/';

        $this->output->set_content_type('json', 'utf-8');
        if (!empty($akun_id) && !empty($kode) && !is_null($filename))
        {
            if(!is_dir($path))
                mkdir($path, 0777, TRUE);

            $filepath = $path . $filename;

            $pendidikan = $this->m_profil->get_pendidikan($kode);

            if(file_exists($path . $pendidikan[0]->lampiran))
            {
                rename($path . $pendidikan[0]->lampiran, $path . $pendidikan[0]->lampiran . '_old');
            }

            move_uploaded_file($_FILES['lampiran']['tmp_name'] , $filepath);

            $result = $this->m_profil->edit_pendidikan(array(   'param_kode'        => $kode,
                                                                'param_lampiran'    => $filename));
            if($result->result == 0)
            {
                if(file_exists($path . $pendidikan[0]->lampiran . '_old'))
                {
                    unlink($path . $pendidikan[0]->lampiran . '_old');
                }
                $this->output->set_output(json_encode("Edit pendidikan berhasil!"));
            }
            else
            {
                if(file_exists($path . $pendidikan[0]->lampiran . '_old'))
                {
                    rename($path . $pendidikan[0]->lampiran . '_old', $path . $pendidikan[0]->lampiran);
                }

                if(file_exists($filepath))
                {
                    unlink($filepath);
                }
                $this->output->set_status_header(400);
                $this->output->set_output(json_encode("Edit pendidikan gagal!"));
            }
            mysqli_next_result( $this->db->conn_id );
        }
        else
        {
            $this->output->set_status_header(400);
            $this->output->set_output(json_encode("Data input error!"));
        }
    }

	public function getPekerjaanTable()
    {
		$author_id 	= $this->session->userdata('author_id');
        $kode       = $this->session->userdata('kode_thl');

        $this->load->model('m_profil');

		$list = $this->m_profil->get_pekerjaan($kode);
        $data = array();
        $no = $_POST['start'];

        $path = base_url() . '_upload/' . $kode . '/dok_kerja/';

        foreach ($list as $pekerjaan) {
            $timestamp  = strtotime($pekerjaan->tgl_masuk);
            $date_in    = date("d-m-Y", $timestamp);

            $timestamp  = strtotime($pekerjaan->tgl_keluar);
            $date_out   = date("d-m-Y", $timestamp);

            $no++;
            $row = array();
            $row['no']          	= $no;
            $row['instansi'] 		= $pekerjaan->instansi;
            $row['posisi'] 			= $pekerjaan->deskripsi;
            $row['masuk'] 			= $date_in;
            $row['keluar'] 			= $date_out;
            $row['lampiran'] 		= '<button type="button" class="btn btn-info" onclick="window.open(\'' . $path . $pekerjaan->dokumen . '\')"><i class="fas fa-file"></i></button>';
            $row['action'] 			= '<button type="button" class="btn btn-info"
                                            data-toggle="modal"
                                            data-target="#modal-edit-pekerjaan"
                                            data-id="' . $pekerjaan->id . '"
                                            data-posisi="' . $pekerjaan->deskripsi . '"
                                            data-instansi="' . $pekerjaan->instansi . '"
                                            data-masuk="' . $pekerjaan->tgl_masuk . '"
                                            data-keluar="' . $pekerjaan->tgl_keluar . '"><i class="fas fa-pencil-alt"></i></button>';
 
            $data[] = $row;
        }
 
        $output = array(
                "draw" 				=> $_POST['draw'],
                "recordsTotal" 		=> $this->m_profil->count_all_pekerjaan($kode),
                "recordsFiltered" 	=> $this->m_profil->count_filtered_pekerjaan($kode),
                "data" 				=> $data
        );

        $this->output->set_content_type('json', 'utf-8');
        $this->output->set_output(json_encode($output));
	}

    public function editPekerjaan()
    {
        $this->load->model('m_profil');

        $akun_id    = $this->session->userdata('akun_id');
        $kode       = $this->session->userdata('kode_thl');
        $id         = xss_filter($this->input->post('id'));

        $salt       = create_kode();

        if (isset($_FILES['lampiran']['name']))
            if (!empty($_FILES['lampiran']['name']))
                $filename   = 'dok_kerja_' . $salt . '.' . pathinfo($_FILES['lampiran']['name'], PATHINFO_EXTENSION);
            else
                $filename   = null;
        else
            $filename   = null;

        $path = FCPATH . '_upload/' . $kode . '/dok_kerja/';

        $this->output->set_content_type('json', 'utf-8');
        if (!empty($akun_id) && !empty($kode) && !empty($id))
        {
            if(!is_dir($path))
                mkdir($path, 0777, TRUE);

            $pekerjaan = $this->m_profil->get_pekerjaan($kode, $id);

            if(file_exists($path . $pekerjaan[0]->dokumen))
            {
                rename($path . $pekerjaan[0]->dokumen, $path . $pekerjaan[0]->dokumen . '_old');
            }

            while(file_exists($path . $filename))
            {
                $salt       = create_kode();
                $filename   = 'dok_kerja_' . $salt . '.' . pathinfo($_FILES['lampiran']['name'], PATHINFO_EXTENSION);
            }

            $filepath = $path . $filename;

            move_uploaded_file($_FILES['lampiran']['tmp_name'] , $filepath);

            $result = $this->m_profil->edit_pekerjaan(array(    'param_id'          => $id,
                                                                'param_kode'        => $kode,
                                                                'param_lampiran'    => $filename));
            if($result->result == 0)
            {
                if(file_exists($path . $pekerjaan[0]->dokumen . '_old'))
                {
                    unlink($path . $pekerjaan[0]->dokumen . '_old');
                }
                $this->output->set_output(json_encode("Edit pekerjaan berhasil!"));
            }
            else
            {
                if(file_exists($path . $pekerjaan[0]->dokumen . '_old'))
                {
                    rename($path . $pekerjaan[0]->dokumen . '_old', $path . $pekerjaan[0]->dokumen);
                }

                if(file_exists($filepath))
                {
                    unlink($filepath);
                }
                $this->output->set_status_header(400);
                $this->output->set_output(json_encode("Edit pekerjaan gagal!"));
            }
            mysqli_next_result( $this->db->conn_id );
        }
        else
        {
            $this->output->set_status_header(400);
            $this->output->set_output(json_encode("Data input error!"));
        }
    }

	public function getSertifikasiTable()
    {
		$author_id 	= $this->session->userdata('author_id');
        $kode       = $this->session->userdata('kode_thl');

        $this->load->model('m_profil');

		$list = $this->m_profil->get_sertifikasi($kode);
        $data = array();
        $no = $_POST['start'];

        $path = base_url() . '_upload/' . $kode . '/dok_sertifikat/';

        foreach ($list as $sertifikasi) {
            $no++;
            $row = array();
            $row['no']          	= $no;
            $row['sertifikat'] 		= $sertifikasi->deskripsi;
            $row['lampiran'] 		= '<button type="button" class="btn btn-info" onclick="window.open(\'' . $path . $sertifikasi->dokumen . '\')"><i class="fas fa-file"></i></button>';
            $row['action'] 			= '<button type="button" class="btn btn-info"
                                            data-toggle="modal"
                                            data-target="#modal-edit-sertifikasi"
                                            data-id="' . $sertifikasi->id . '"
                                            data-sertifikat="' . $sertifikasi->deskripsi . '"><i class="fas fa-pencil-alt"></i></button>';
 
            $data[] = $row;
        }
 
        $output = array(
                "draw" 				=> $_POST['draw'],
                "recordsTotal" 		=> $this->m_profil->count_all_sertifikasi($kode),
                "recordsFiltered" 	=> $this->m_profil->count_filtered_sertifikasi($kode),
                "data" 				=> $data
        );

        $this->output->set_content_type('json', 'utf-8');
        $this->output->set_output(json_encode($output));
	}

    public function editSertifikasi()
    {
        $this->load->model('m_profil');

        $akun_id    = $this->session->userdata('akun_id');
        $kode       = $this->session->userdata('kode_thl');
        $id         = xss_filter($this->input->post('id'));

        $salt       = create_kode();

        if (isset($_FILES['lampiran']['name']))
            if (!empty($_FILES['lampiran']['name']))
                $filename   = 'dok_sertifikat_' . $salt . '.' . pathinfo($_FILES['lampiran']['name'], PATHINFO_EXTENSION);
            else
                $filename   = null;
        else
            $filename   = null;

        $path = FCPATH . '/_upload/' . $kode . '/dok_sertifikat/';

        $this->output->set_content_type('json', 'utf-8');
        if (!empty($akun_id) && !empty($kode) && !empty($id))
        {
            if(!is_dir($path))
                mkdir($path, 0777, TRUE);

            $sertifikasi = $this->m_profil->get_sertifikasi($kode, $id);

            if(file_exists($path . $sertifikasi[0]->dokumen))
            {
                rename($path . $sertifikasi[0]->dokumen, $path . $sertifikasi[0]->dokumen . '_old');
            }

            while(file_exists($path . $filename))
            {
                $salt       = create_kode();
                $filename   = 'dok_sertifikat_' . $salt . '.' . pathinfo($_FILES['lampiran']['name'], PATHINFO_EXTENSION);
            }

            $filepath = $path . $filename;

            move_uploaded_file($_FILES['lampiran']['tmp_name'] , $filepath);

            $result = $this->m_profil->edit_sertifikasi(array(  'param_id'          => $id,
                                                                'param_kode'        => $kode,
                                                                'param_lampiran'    => $filename));
            if($result->result == 0)
            {
                if(file_exists($path . $sertifikasi[0]->dokumen . '_old'))
                {
                    unlink($path . $sertifikasi[0]->dokumen . '_old');
                }
                $this->output->set_output(json_encode("Edit sertifikasi berhasil!"));
            }
            else
            {
                if(file_exists($path . $sertifikasi[0]->dokumen . '_old'))
                {
                    rename($path . $sertifikasi[0]->dokumen . '_old', $path . $sertifikasi[0]->dokumen);
                }

                if(file_exists($filepath))
                {
                    unlink($filepath);
                }
                $this->output->set_status_header(400);
                $this->output->set_output(json_encode("Edit sertifikasi gagal!"));
            }
            mysqli_next_result( $this->db->conn_id );
        }
        else
        {
            $this->output->set_status_header(400);
            $this->output->set_output(json_encode("Data input error!"));
        }
    }

    public function getLogTable()
    {
        $kode       = NULL;
        $author_id  = $this->session->userdata('author_id');
        if($author_id == 6)
            $kode   = $this->session->userdata('kode_thl');
        else
            $kode   = $this->session->userdata('kode_spv');

        $this->load->model('m_profil');

        $list = $this->m_profil->get_log($kode);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $log) {
            $timestamp  = strtotime($log->created_at);
            $date       = date("d-m-Y H:m:s", $timestamp);

            $no++;
            $row = array();
            $row['no']          = $no;
            $row['keterangan']  = $log->keterangan;
            $row['waktu']       = $date;
 
            $data[] = $row;
        }
 
        $output = array(
                "draw"              => $_POST['draw'],
                "recordsTotal"      => $this->m_profil->count_all_log($kode),
                "recordsFiltered"   => $this->m_profil->count_filtered_log($kode),
                "data"              => $data
        );

        $this->output->set_content_type('json', 'utf-8');
        $this->output->set_output(json_encode($output));
    }
}
