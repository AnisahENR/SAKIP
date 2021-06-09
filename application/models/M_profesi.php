<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_profesi extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_masterprofesi()
    {
        $this->db->select('*');
        $this->db->from('m_profesi_thl');
        $this->db->where('flag', 1);
        return $this->db->get()->result_array();
    } 

    public function insert_masterprofesi($data){
        return $this->db->insert('m_profesi_thl', $data);
    }

    public function detail_masterprofesi($id){
        $this->db->select('*');
        $this->db->from('m_profesi_thl');
        $this->db->where('id', $id);
        return $this->db->get()->result_array();
    }

    public function update_masterprofesi($data){
        $this->db->where('id', $data['id']);
        return $this->db->update('m_profesi_thl', $data);
    }

    public function delete_masterprofesi($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->delete('m_profesi_thl');
    }

    public function get_checkavailable($data)
    {
        $this->db->select('deskripsi','flag');
        $this->db->from('m_profesi_thl');
        $this->db->where('deskripsi', $data['deskripsi']);
        $this->db->where('flag', 1);
        return $this->db->get()->row_array();
    }

    public function insert_trx_log_akun($data){
        return $this->db->insert('trx_log_akun', $data);
    }

    public function get_checkrelation($data)
    {
        $this->db->select('profesi_thl_id');
        $this->db->from('t_thl');
        $this->db->where('profesi_thl_id', $data['id']);
        return $this->db->get()->row_array();
    }

    public function get_checkrelation_master($data)
    {
        $this->db->select('profesi_thl_id');
        $this->db->from('m_kegiatan_thl');
        $this->db->where('profesi_thl_id', $data['id']);
        return $this->db->get()->row_array();
    }

    public function update_masterkegiatan($flag, $id){
        $this->db->where('profesi_thl_id', $id);
        return $this->db->update('m_kegiatan_thl', $flag);
    }

  
}
