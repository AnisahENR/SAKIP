<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_kegiatan extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    
    public function get_list_profesi()
    {
        $this->db->select('id,deskripsi');
        $this->db->from('m_profesi_thl');
        $this->db->where('flag', 1);
        return $this->db->get()->result_array();
    } 

    public function get_masterkegiatan()
    {
        $this->db->select('a.deskripsi as keg_deskripsi');
        $this->db->select('a.keterangan as keg_keterangan');
        $this->db->select('profesi_thl_id');
        $this->db->select('a.id as id');
        $this->db->select('b.deskripsi as prof_deskripsi');
        $this->db->from('m_kegiatan_thl a');
        $this->db->join('m_profesi_thl b', 'a.profesi_thl_id = b.id');
        $this->db->where('a.flag', 1);
        return $this->db->get()->result_array();
    } 

    public function insert_masterkegiatan($data){
        return $this->db->insert('m_kegiatan_thl', $data);
    }

    public function detail_masterkegiatan($id){
        $this->db->select('*');
        $this->db->from('m_kegiatan_thl');
        $this->db->where('id', $id);
        return $this->db->get()->result_array();
    }

    public function update_masterkegiatan($data){
        $this->db->where('id', $data['id']);
        return $this->db->update('m_kegiatan_thl', $data);
    }

    public function delete_masterkegiatan($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->delete('m_kegiatan_thl');
    }


    public function get_checkavailable($data)
    {
        $this->db->select('deskripsi');
        $this->db->from('m_kegiatan_thl');
        $this->db->where('deskripsi', $data['deskripsi']);
        $this->db->where('flag', 1);
        return $this->db->get()->row_array();
    }

    public function insert_trx_log_akun($data){
        return $this->db->insert('trx_log_akun', $data);
    }


    public function get_checkrelation($data)
    {
        $this->db->select('kegiatan_thl_id');
        $this->db->from('t_laporan_kegiatan_thl');
        $this->db->where('kegiatan_thl_id', $data['id']);
        return $this->db->get()->row_array();
    }

    public function update_masterdetaillaporan($flag, $id){
        $this->db->where('kegiatan_thl_id', $id);
        return $this->db->update('t_detail_laporan_thl', $flag);
    }

   
    
}
