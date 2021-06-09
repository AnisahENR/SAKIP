<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_author extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_masterauthor()
    {
        $this->db->select('*');
		$this->db->from('m_author');
		return $this->db->get()->result_array();
    } 

	public function insert_masterauthor($data){
		return $this->db->insert('m_author', $data);
	}

	public function detail_masterauthor($id){
		$this->db->select('*');
		$this->db->from('m_author');
		$this->db->where('id', $id);
		return $this->db->get()->result_array();
	}

	public function update_masterauthor($data){
		$this->db->where('id', $data['id']);
		return $this->db->update('m_author', $data);
    }

    public function delete_masterauthor($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->delete('m_author');
    }


    public function get_checkavailable($data)
    {
        $this->db->select('deskripsi');
		$this->db->from('m_author');
		$this->db->where('deskripsi', $data['deskripsi']);
		return $this->db->get()->row_array();
    }

}
