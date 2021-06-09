<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_skpd extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
// LIST MASTER SKPD
	public function read_skpd(){
		$this->db->select('*');
		$this->db->from('m_skpd');
		return $this->db->get()->result_array();
	}
// TAMBAH MASTER SKPD
	public function insert_skpd($data){
		$this->db->insert('m_skpd', $data);
	}
// DETAIL MASTER SKPD
	public function detail_skpd($idm_skpd){
		$this->db->select('*');
		$this->db->from('m_skpd');
		$this->db->where('id', $idm_skpd);
		return $this->db->get()->result_array();
	}
// UPDATE MASTER SKPD
	public function update_skpd($data){
		$this->db->where('id', $data['id']);
		$this->db->update('m_skpd', $data);
	}
// HAPUS MASTER SKPD
	public function delete_skpd($data){
		$this->db->where('id', $data['id']);
		return $this->db->delete('m_skpd');
	}
// CHECK SKPD
	public function check_availskpd($deskripsi, $where = array()){
		$this->db->select('id');
		$this->db->from('m_skpd');
		$this->db->where('deskripsi',$deskripsi);
		return $this->db->get()->row_array();
	}

	public function check_spv($data){
		$this->db->select('skpd_id', $data['id']);
		$this->db->from('t_spv');
		$this->db->where('skpd_id', $data['id']);
		return $this->db->get()->row_array();
	}
	public function check_thl($data){
		$this->db->select('skpd_id', $data['id']);
		$this->db->from('t_thl');
		$this->db->where('skpd_id', $data['id']);
		return $this->db->get()->row_array();
	}
	public function check_laporan($data){
		$this->db->select('skpd_id', $data['id']);
		$this->db->from('t_laporan_thl');
		$this->db->where('skpd_id', $data['id']);
		return $this->db->get()->row_array();
	}


// ------------------------------------------BIDANG--------------------------------------------------
	// MASTER BIDANG
	public function detail_bidang($skpd_id){
		$this->db->select('*');
		$this->db->from('m_bidang_skpd');
		$this->db->where('skpd_id', $skpd_id);
		return $this->db->get()->result_array();
	}
	// TAMBAH MASTER BIDANG
	public function insert_bidang($data){
		return $this->db->insert('m_bidang_skpd', $data);
	}
	// UPDATE MASTER BIDANG
	public function update_bidang($data){
		$this->db->where('id', $data['id']);
		$this->db->update('m_bidang_skpd', $data);
	}
	// DELETE MASTER BIDANG
	public function delete_bidang($idm_bidang){
		$this->db->where('id', $idm_bidang);
		$this->db->delete('m_bidang_skpd');
	}

	// MASTER JABATAN
	public function detail_jabatan($skpd_id){
		$this->db->select('
			j.id, 
			j.bidang_skpd_id, 
			j.deskripsi as jabatan, 
			b.skpd_id, 
			b.deskripsi as bidang');
		$this->db->select('a.deskripsi as author');
		$this->db->from('m_jabatan_spv j');
		$this->db->join('m_bidang_skpd b', 'j.bidang_skpd_id = b.id');
		$this->db->join('m_author a', 'j.author_id = a.id');
		$this->db->where('b.skpd_id', $skpd_id);
		$this->db->where('j.author_id !=', 1);
		return $this->db->get()->result_array();
	}

	public function insert_jabatan($data){
		return $this->db->insert('m_jabatan_spv', $data);
	}
	// UPDATE MASTER BIDANG
	public function update_jabatan($data){
		$this->db->where('id', $data['id']);
		$this->db->update('m_jabatan_spv', $data);
	}
	// DELETE MASTER BIDANG
	public function delete_jabatan($idm_jabatan){
		$this->db->where('id', $idm_jabatan);
		$this->db->delete('m_jabatan_spv');
	}

	public function edit_jabatan($id){
		$this->db->select('*');
		$this->db->from('m_jabatan_spv');
		$this->db->where('id', $id);
		return $this->db->get()->result_array();
	}

	// DATA SPV TIAP SKPD
	public function read_spv_skpd($skpd_id){
		$this->db->select('t_spv.jabatan_spv_id, t_spv.nama, t_spv.nip, t_spv.kode_spv, t_akun.username, t_akun.stat_akun_id, m_stat_akun.deskripsi AS status, m_jabatan_spv.id, m_jabatan_spv.deskripsi AS jabatan, m_skpd.deskripsi AS skpd, m_skpd.alamat, m_bidang_skpd.deskripsi AS bidang');
		$this->db->from('t_spv');
		$this->db->join('m_jabatan_spv', 'm_jabatan_spv.id = t_spv.jabatan_spv_id');
		$this->db->join('m_skpd', 'm_skpd.id = t_spv.skpd_id');
		$this->db->join('m_bidang_skpd', ' m_bidang_skpd.id = t_spv.bidang_skpd_id');
		$this->db->join('t_akun', 't_akun.kode = t_spv.kode_spv');
		$this->db->join('m_stat_akun', 'm_stat_akun.id = t_akun.stat_akun_id');
		$this->db->where('t_akun.author_id !=',1 );
		$this->db->where('t_akun.author_id !=',2 );
		$this->db->where('t_spv.skpd_id', $skpd_id);
		return $this->db->get()->result_array();
	}

	public function insert_spv_skpd($data){
		return $this->db->insert('t_spv', $data);
	}

	// LIST AUTHOR
	public function listauthor(){
		$this->db->select('*');
		$this->db->from('m_author');
		$this->db->where('id != 1');
		$this->db->where('id != 2');
		$this->db->where('id != 6');
		return $this->db->get()->result_array();
	}

	// LIST CHECK ADMIN DAN KEPALA DINAS SETIAP SKPD
	// public function cek_admin_kadis($data){
	// 	$this->db->select(
	// 		'm_bidang_skpd.skpd_id',
	// 		'm_bidang_skpd.deskripsi',
	// 		'm_jabatan_spv.deskripsi',
	// 		'm_jabatan_spv.author_id'
	// 	);
	// 	$this->db->from('m_bidang_skpd');
	// 	$this->db->join('m_jabatan_spv', 'm_jabatan_spv.bidang_skpd_id = m_bidang_skpd.id');
	// 	$this->db->join('m_author', 'm_author.id = m_jabatan_spv.author_id');
	// 	$this->db->where('m_bidang_skpd.skpd_id ', $data['skpd_id']);
	// 	$this->db->where('m_jabatan_spv.author_id  ', $data['author_id']);
	// 	return $this->db->get()->result_array();
	// }


	// CHECK RELASI BIDANG PADA JABATAN
	public function check_relasibidang($data){
		$this->db->select('DISTINCT(bidang_skpd_id)');
		$this->db->from('m_jabatan_spv');
		$this->db->where('bidang_skpd_id', $data);
		return $this->db->get()->result_array();
	}

	public function check_bidangwhere_skpd($data){
		$this->db->select('deskripsi');
		$this->db->from('m_bidang_skpd');
		$this->db->where('skpd_id', $data['skpd_id']);
		$this->db->where('deskripsi', $data['deskripsi']);
		return $this->db->get()->result_array();
	}


}
