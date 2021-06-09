<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_profil extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    public function get_profil($data)
    {
        $stored_proc = "CALL get_profil(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->row();
    }

    public function edit_profil($data)
    {
        $stored_proc = "CALL update_profil(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->row();
    }

    public function edit_password($data)
    {
        $stored_proc = "CALL update_password(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->row();
    }

    public function get_status_perkawinan_form()
    {
		$this->db->from('m_stat_perkawinan');
		return $this->db->get()->result_array();
    } 

    public function get_provinsi_form()
    {
		$this->db->from('m_provinsi');
		return $this->db->get()->result_array();
    } 

    public function get_wilayah_form()
    {
		$this->db->from('m_wilayah');
		return $this->db->get()->result_array();
    } 

    public function get_pendidikan_form()
    {
		$this->db->from('m_pendidikan');
		return $this->db->get()->result_array();
    }
 
    public function get_pendidikan($kode)
    {
        $this->get_pendidikan_query($kode);
        if (isset($_POST['length']))
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function get_pendidikan_query($kode)
    {
    	$this->db->select(	'm_pendidikan.id        AS id,
                             t_thl.tmt_pendidikan 	AS institusi,
                             m_pendidikan.id        AS jenjang_id,
    						 m_pendidikan.deskripsi	AS jenjang,
                             t_thl.ijazah           AS lampiran');
        $this->db->from('t_thl');
        $this->db->join('m_pendidikan', 't_thl.pendidikan_id = m_pendidikan.id', 'left');
        $this->db->where('t_thl.kode_thl', $kode);
    }
 
    public function count_all_pendidikan($kode)
    {
    	$this->db->select(	't_thl.tmt_pendidikan 	AS institusi,
    						 m_pendidikan.deskripsi	AS jenjang');
        $this->db->from('t_thl');
        $this->db->join('m_pendidikan', 't_thl.pendidikan_id = m_pendidikan.id', 'left');
        $this->db->where('t_thl.kode_thl', $kode);
        return $this->db->count_all_results();
    }

    public function count_filtered_pendidikan($kode)
    {
        $this->get_pendidikan_query($kode);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function get_pekerjaan($kode, $id = null)
    {
        $this->get_pekerjaan_query($kode, $id);
        if (isset($_POST['length']))
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    
    private function get_pekerjaan_query($kode, $id = null)
    {
        $this->db->from('trx_thl_pengalaman_kerja');
        if(is_null($id))
            $this->db->where('kode_thl', $kode);
        else
            $this->db->where('id', $id);
        $this->db->order_by('tgl_masuk', 'ASC');
    }
 
    public function count_all_pekerjaan($kode)
    {
        $this->db->from('trx_thl_pengalaman_kerja');
        $this->db->where('kode_thl', $kode);
        return $this->db->count_all_results();
    }

    public function count_filtered_pekerjaan($kode)
    {
        $this->get_pekerjaan_query($kode);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function get_sertifikasi($kode, $id = null)
    {
        $this->get_sertifikasi_query($kode, $id);
        if (isset($_POST['length']))
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    
    private function get_sertifikasi_query($kode, $id = null)
    {
        $this->db->from('trx_thl_sertfikat');
        if(is_null($id))
            $this->db->where('kode_thl', $kode);
        else
            $this->db->where('id', $id);
        $this->db->order_by('id', 'ASC');
    }
 
    public function count_all_sertifikasi($kode)
    {
        $this->db->from('trx_thl_sertfikat');
        $this->db->where('kode_thl', $kode);
        return $this->db->count_all_results();
    }

    public function count_filtered_sertifikasi($kode)
    {
        $this->get_sertifikasi_query($kode);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function edit_pendidikan($data)
    {
        $stored_proc = "CALL update_pendidikan(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->row();
    }

    public function edit_pekerjaan($data)
    {
        $stored_proc = "CALL update_pekerjaan(?, ?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->row();
    }

    public function edit_sertifikasi($data)
    {
        $stored_proc = "CALL update_sertifikasi(?, ?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->row();
    }

    private function get_log_query($data)
    {
        $this->db->select("keterangan, created_at");
        $this->db->from('trx_log_akun');
        $this->db->where('kode', $data);
        $this->db->order_by('created_at', 'DESC');
    }
 
    public function get_log($data)
    {
        $this->get_log_query($data);
        if (isset($_POST['length']))
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_log($data)
    {
        $this->get_log_query($data);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all_log($data)
    {
        $this->db->select("keterangan, created_at");
        $this->db->from('trx_log_akun');
        $this->db->where('kode', $data);

        return $this->db->count_all_results();
    }
}
