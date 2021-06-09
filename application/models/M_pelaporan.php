<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_pelaporan extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function post_laporan_thl($data){
		return $this->db->insert('t_laporan_thl', $data);
	}

	public function getLaporanThl($kode_thl,$range_date = array()){
		$this->db->select('t_thl.nik, t_thl.nama,t_laporan_thl.tgl_laporan, t_laporan_thl.kode_laporan_thl,t_laporan_thl.kode_thl, t_laporan_thl.jml_waktu, kabid_spv.kode_spv as kabid_kode, kabid_spv.nama as kabid_nama,kasie_spv.kode_spv as kasie_kode, kasie_spv.nama as kasie_nama,v_status_laporan_thl.jumlah');
		$this->db->from('t_laporan_thl');
		$this->db->join('t_spv kabid_spv','t_laporan_thl.kode_spv_kabid = kabid_spv.kode_spv');
		$this->db->join('t_spv kasie_spv','t_laporan_thl.kode_spv_kasie = kasie_spv.kode_spv');
		$this->db->join('t_thl','t_laporan_thl.kode_thl = t_thl.kode_thl');        
		$this->db->join('v_status_laporan_thl', 'v_status_laporan_thl.kode_laporan = t_laporan_thl.kode_laporan_thl');
		$this->db->where('t_laporan_thl.kode_thl ="'.$kode_thl.'"');
		$this->db->where("t_laporan_thl.tgl_laporan BETWEEN '".$range_date[0]."' AND '".$range_date[1]."'", null, false);
		$this->db->order_by('v_status_laporan_thl.jumlah', 'DESC');
		$this->db->order_by('t_laporan_thl.tgl_laporan', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_spv($kode_thl){
		$this->db->select('t_thl.kode_thl, kabid_spv.kode_spv as kabid_kode, kabid_spv.nama as kabid_nama,
			kasie_spv.kode_spv as kasie_kode, kasie_spv.nama as kasie_nama');
		$this->db->from('t_thl');
		$this->db->join('t_spv kabid_spv','t_thl.kode_spv_kabid = kabid_spv.kode_spv');
		$this->db->join('t_spv kasie_spv','t_thl.kode_spv_kasie = kasie_spv.kode_spv');
		$this->db->where('t_thl.kode_thl',$kode_thl);
		$data = $this->db->get();
		return $data->result();
	}

	public function get_kode_target2($kode_laporan_thl){
		$this->db->select('kode_target_thl');
		$this->db->from('t_laporan_thl');
		$this->db->where('kode_laporan_thl = "'.$kode_laporan_thl.'"');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_kode_target($tgl_laporan){
		$this->db->select('t_target_thl.kode_target_thl');
		$this->db->from('t_target_thl');
		$this->db->where('t_target_thl.tgl_laporan = "'.$tgl_laporan.'"');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_jml_waktu($kode_laporan_thl){
		$this->db->select('t_laporan_thl.jml_waktu');
		$this->db->from('t_laporan_thl');
		$this->db->where('t_laporan_thl.kode_laporan_thl = "'.$kode_laporan_thl.'"');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function update_total_jml($where,$data){
		$this->db->update('t_laporan_thl', $data, $where);
		return $this->db->affected_rows();
	}

	public function get_kegiatan_target($kode_target_thl){
		$this->db->select('t_target_detail_thl.kode_target_thl, m_kegiatan_thl.*');
		$this->db->from('t_target_detail_thl');
		$this->db->join('m_kegiatan_thl','m_kegiatan_thl.id = t_target_detail_thl.kegiatan_thl_id');
		$this->db->where('t_target_detail_thl.kode_target_thl = "'.$kode_target_thl.'"');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_detail_laporan($kode_laporan_thl)
	{
		$this->db->select('t_laporan_thl.*, t_laporan_kegiatan_thl.*, m_kegiatan_thl.deskripsi as kegiatan_thl');
		$this->db->from('t_laporan_thl');
		$this->db->join('t_laporan_kegiatan_thl','t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl');
		$this->db->join('m_kegiatan_thl', 't_laporan_kegiatan_thl.kegiatan_thl_id = m_kegiatan_thl.id');
		$this->db->join('t_thl','t_laporan_thl.kode_thl = t_thl.kode_thl');
		$this->db->where('t_laporan_thl.kode_laporan_thl="'.$kode_laporan_thl.'"');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_detail_laporan_lain($kode_laporan_thl)
	{
		$this->db->select('t_laporan_thl.kode_thl,t_laporan_thl.kode_laporan_thl, t_laporan_lain_thl.*');
		$this->db->from('t_laporan_thl');
		$this->db->join('t_laporan_lain_thl','t_laporan_thl.kode_laporan_thl = t_laporan_lain_thl.kode_laporan_thl');
		$this->db->join('t_thl','t_laporan_thl.kode_thl = t_thl.kode_thl');
		$this->db->where('t_laporan_thl.kode_laporan_thl="'.$kode_laporan_thl.'"');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_identitas_pelapor($kode_laporan_thl){
		$this->db->select('t_laporan_thl.*, t_thl.nama, a_t_spv.nama as kabid, b_t_spv.nama as kasie');
		$this->db->select('m_profesi_thl.deskripsi as des_profesi');
		$this->db->select('m_bidang_skpd.deskripsi as des_bidang');
		$this->db->select('m_skpd.deskripsi as des_skpd');
		$this->db->select('t_laporan_thl.flag_migration,migration.deskripsi migration');
		$this->db->from('t_laporan_thl');
		$this->db->join('t_thl', 't_laporan_thl.kode_thl = t_thl.kode_thl');
		$this->db->join('m_profesi_thl', 't_laporan_thl.profesi_thl_id = m_profesi_thl.id');
		$this->db->join('m_bidang_skpd', 't_laporan_thl.bidang_skpd_id = m_bidang_skpd.id');
        $this->db->join('m_flag_migration migration', 'migration.id = t_laporan_thl.flag_migration');
		$this->db->join('m_skpd','t_laporan_thl.skpd_id = m_skpd.id');
		$this->db->join('t_spv a_t_spv', 't_laporan_thl.kode_spv_kasie = a_t_spv.kode_spv','left');
		$this->db->join('t_spv b_t_spv', 't_laporan_thl.kode_spv_kabid = b_t_spv.kode_spv','left');
		$this->db->where('t_laporan_thl.kode_laporan_thl="'.$kode_laporan_thl.'"');
		$query = $this->db->get();
		return $query->result();
	}

	public function post_detail_laporan_thl($data){
		return $this->db->insert('t_laporan_kegiatan_thl', $data);
	}

	public function hapus_pelaporan($kode_laporan_thl){
		$this->db->where('kode_laporan_thl', $kode_laporan_thl);
		$this->db->delete('t_laporan_thl');

	}

	public function hapus_detail_laporan ($kode_laporan_thl){
		$this->db->where('kode_laporan_thl',$kode_laporan_thl);
		$this->db->delete('t_laporan_kegiatan_thl');
	}

	public function hapus_detail_aktivitas ($id){
		$this->db->where('id',$id);
		$this->db->delete('t_laporan_kegiatan_thl');
	}

	public function hapus_aktivitas_lain($id){
		$this->db->where('id',$id);
		$this->db->delete('t_laporan_lain_thl');
	}

	public function get_nama_gambar($kode_laporan_thl)
	{
		$this->db->select('t_laporan_thl.kode_thl, t_laporan_kegiatan_thl.lampiran');
		$this->db->from('t_laporan_thl');
		$this->db->join('t_laporan_kegiatan_thl', 't_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl');
		$this->db->where('t_laporan_thl.kode_laporan_thl ="'.$kode_laporan_thl.'"');
		$query = $this->db->get();
		return $query->result();	  	
	}

	public function get_detail_kegiatan($id)
	{
		$this->db->select('t_laporan_thl.kode_laporan_thl,t_laporan_thl.jml_waktu,t_laporan_thl.tgl_laporan,t_laporan_kegiatan_thl.waktu,t_laporan_kegiatan_thl.uraian,t_laporan_kegiatan_thl.lampiran');
		$this->db->from('t_laporan_kegiatan_thl');
		$this->db->join('t_laporan_thl','t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl');
		$this->db->where('t_laporan_kegiatan_thl.id ="'.$id.'"');
		$query = $this->db->get();
		return $query->row();	  	
	}

	public function get_detail_lain($id)
	{
		$this->db->select('t_laporan_thl.kode_laporan_thl,t_laporan_thl.jml_waktu,t_laporan_thl.tgl_laporan,t_laporan_lain_thl.waktu,t_laporan_lain_thl.uraian,t_laporan_lain_thl.lampiran');
		$this->db->from('t_laporan_lain_thl');
		$this->db->join('t_laporan_thl','t_laporan_thl.kode_laporan_thl = t_laporan_lain_thl.kode_laporan_thl');
		$this->db->where('t_laporan_lain_thl.id ="'.$id.'"');
		$query = $this->db->get();
		return $query->row();	  	
	}
	
	public function cektgl($data)
	{

		$query = $this->db->get_where("t_laporan_thl", $data);
		return $query->result_array();
	}

	public function getThl($kode_thl){
		$this->db->select('t_thl.*');
		$this->db->select('m_profesi_thl.deskripsi as des_profesi');
		$this->db->select('m_bidang_skpd.deskripsi as des_bidang');
		$this->db->select('m_skpd.deskripsi as des_skpd');
		$this->db->from('t_thl');
		$this->db->join('m_profesi_thl', 't_thl.profesi_thl_id = m_profesi_thl.id');
		$this->db->join('m_bidang_skpd', 't_thl.bidang_skpd_id = m_bidang_skpd.id');
		$this->db->join('m_skpd','t_thl.skpd_id = m_skpd.id');
		$this->db->where('t_thl.kode_thl',$kode_thl);
		$data = $this->db->get();
		return $data->result_array();
	}

	public function getAvail_TglLaporan($data)
	{
		$stored_proc = "CALL get_avail_tgllaporan_thl(?)";
		$result = $this->db->query($stored_proc, $data);
		return $result->result_array();
	}

	public function post_aktivitas_lain($data){
		return $this->db->insert('t_laporan_lain_thl',$data);
	}

	public function get_data_kegiatan($id)
	{
		$this->db->from('t_laporan_kegiatan_thl');
		$this->db->where('t_laporan_kegiatan_thl.id',$id);
		$query = $this->db->get();
		return $query->row();	  	
	}

	public function get_laporan_lain($id){
		$this->db->from('t_laporan_lain_thl');
		$this->db->where('t_laporan_lain_thl.id',$id);
		$query = $this->db->get();
		return $query->row();
	}

	/* --------------------------   Target Mingguan    ----------------------------------*/

	function get_data_target($kode_thl,$range_date = array()){

		$this->db->select('t_thl.nama');
		$this->db->select('t_target_thl.*');
		$this->db->select('a_spv.nama as nama_spv_kasie');
		$this->db->select('b_spv.nama as nama_spv_kabid');
		$this->db->from('t_target_thl');
		$this->db->join('t_thl', 't_target_thl.kode_thl = t_thl.kode_thl');
		$this->db->join('t_spv a_spv','a_spv.kode_spv =  t_target_thl.kode_spv_kasie', 'left');
		$this->db->join('t_spv b_spv','b_spv.kode_spv =  t_target_thl.kode_spv_kabid', 'left');
		$this->db->where('t_target_thl.kode_thl="'.$kode_thl.'"');
		$this->db->where("t_target_thl.tgl_laporan BETWEEN '".$range_date[0]."' AND '".$range_date[1]."'", null, false);
		$this->db->order_by('t_target_thl.stat_laporan_id', 'ASC');
		$this->db->order_by('t_target_thl.tgl_laporan', 'ASC');
		$query = $this->db->get();
		return $query->result_array();

	}

	function get_kegiatan_thl($profesi_thl_id){
		$this->db->select('*');
		$this->db->from('m_kegiatan_thl');
		$this->db->where('profesi_thl_id ',$profesi_thl_id);
		$query = $this->db->get();
		return $query->result();
	}


	function post_target_detail($data){
		return $this->db->insert('t_target_detail_thl', $data);
	}

	function post_target($data){
		return $this->db->insert('t_target_thl',$data);
	}

	function get_target($kode_target_thl){
		$this->db->select('t_target_thl.*,t_thl.*');
		$this->db->select('a_spv.nama as nama_spv_kasie');
		$this->db->select('b_spv.nama as nama_spv_kabid');
		$this->db->select('m_profesi_thl.deskripsi as des_profesi');
		$this->db->select('m_bidang_skpd.deskripsi as des_bidang');
		$this->db->select('m_skpd.deskripsi as des_skpd');
		$this->db->select('t_target_thl.flag_migration,migration.deskripsi migration');
		$this->db->from('t_target_thl');
		$this->db->join('t_thl', 't_thl.kode_thl = t_target_thl.kode_thl');
        $this->db->join('m_flag_migration migration', 'migration.id = t_target_thl.flag_migration');
		$this->db->join('m_profesi_thl', 't_target_thl.profesi_thl_id = m_profesi_thl.id');
		$this->db->join('m_bidang_skpd', 't_target_thl.bidang_skpd_id = m_bidang_skpd.id');
		$this->db->join('m_skpd','t_target_thl.skpd_id = m_skpd.id');
		$this->db->join('t_spv a_spv','a_spv.kode_spv =  t_target_thl.kode_spv_kasie', 'left');
		$this->db->join('t_spv b_spv','b_spv.kode_spv =  t_target_thl.kode_spv_kabid', 'left');
		$this->db->where('t_target_thl.kode_target_thl="'.$kode_target_thl.'"');
		$query = $this->db->get();
		return $query->result();
	}

	function get_target_detail($kode_target_thl){
		$this->db->select('*');
		$this->db->from('t_target_detail_thl');
		$this->db->join('m_kegiatan_thl','t_target_detail_thl.kegiatan_thl_id = m_kegiatan_thl.id');
		$this->db->where('t_target_detail_thl.kode_target_thl="'.$kode_target_thl.'"');
		$query = $this->db->get();
		return $query->result();
	}

	function get_listkegiatan($kode_target_thl){
		$strSubQuery = $this->db
		->select("kegiatan_thl_id")
		->from("t_target_detail_thl")
		->where("kode_target_thl",$kode_target_thl)
		->get_compiled_select();

		$query = $this->db
		->select("id,deskripsi", false)
		->from('m_kegiatan_thl')
		->where_in('id', $strSubQuery, false)
		->get();
		return $query->result_array();
	}

	function hapus_target($kode_target_thl){
		$this->db->where('kode_target_thl', $kode_target_thl);
		$this->db->delete('t_target_thl');

	}

	function hapus_detail_target($kode_target_thl){
		$this->db->where('kode_target_thl',$kode_target_thl);
		$this->db->delete('t_target_detail_thl');
	}

	function get_update_target($kode_target_thl){


	}

}
