<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_admin extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_skpd()
    {
        $this->db->select('id, deskripsi');
		$this->db->from('m_skpd');
		return $this->db->get()->result_array();
    } 

    public function get_skpdon_admin(){
        return $this->db->query("CALL get_skpdon_admin()")->result_array();
    }


    public function getbidang_basedskpd($data){
		$this->db->select('*');
		$this->db->from('m_bidang_skpd');
		$this->db->where('skpd_id', $data);
		return $this->db->get()->result_array();
	}
	public function get_admin()
    {
        $this->db->select('
        	m_skpd.deskripsi as nama_skpd,
        	m_skpd.id as skpd_id,
        	t_spv.nama,
        	t_akun.kode,
        	t_akun.username,
        	t_akun.stat_akun_id,
        	t_akun.username,
        	m_bidang_skpd.deskripsi as bidang,
        	m_jabatan_spv.deskripsi as jabatan,
        	m_stat_akun.deskripsi as status'
        );
        
		$this->db->from('t_spv');
		$this->db->join('t_akun','t_akun.kode = t_spv.kode_spv');
		$this->db->join('m_bidang_skpd','t_spv.bidang_skpd_id = m_bidang_skpd.id');
		$this->db->join('m_jabatan_spv','t_spv.jabatan_spv_id = m_jabatan_spv.id');
		$this->db->join('m_skpd','t_spv.skpd_id = m_skpd.id');
		$this->db->join('m_stat_akun','t_akun.stat_akun_id = m_stat_akun.id');
		$this->db->where('m_jabatan_spv.id', 2);
		return $this->db->get()->result_array();
    } 


    public function get_det_admin($data)
    {
        $this->db->select('
        	m_skpd.id as skpd_id,
        	t_spv.nama,
        	t_spv.nip,
        	t_akun.kode,
        	m_bidang_skpd.id as bidang_id,'
        );
        
		$this->db->from('t_spv');
		$this->db->join('t_akun','t_akun.kode = t_spv.kode_spv');
		$this->db->join('m_bidang_skpd','t_spv.bidang_skpd_id = m_bidang_skpd.id');
		$this->db->join('m_jabatan_spv','t_spv.jabatan_spv_id = m_jabatan_spv.id');
		$this->db->join('m_skpd','t_spv.skpd_id = m_skpd.id');
		$this->db->where('m_jabatan_spv.id', 2);
		$this->db->where('t_akun.kode', $data);
		return $this->db->get()->result_array();
    } 

    public function check_username($data){
    	$this->db->select('username');
		$this->db->from('t_akun');
		$this->db->where('username', $data);
		return $this->db->get()->row_array();	
    }

    // FUNCITON GET AKUN
    public function getDetailAkun($data){
        $this->db->select('
            author_id,
            username');
        $this->db->from('t_akun');
        $this->db->where('kode', $data);
        return $this->db->get()->result_array();
    }

}
