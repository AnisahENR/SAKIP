<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_spv extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
// ------------------------------------------------MASTER SPV-------------------------------------
// MASTER SPV
	public function read_mspv(){
		$this->db->select('*');
		$this->db->from('m_jabatan_spv');
		$this->db->where('author_id !=', 1);
		return $this->db->get()->result_array();
	}
	public function read_mbidang(){
		$this->db->select('*');
		$this->db->from('m_bidang_skpd');
		return $this->db->get()->result_array();
	}

	public function data_spv($data){
		$this->db->select('*');
		$this->db->from('t_spv');
		$this->db->where('kode_spv', $data);
		return $this->db->get()->result_array();
	}

	public function get_counterjabatanspv($data){
		$this->db->select('count(jabatan_spv_id) as length');
		$this->db->from('t_spv');
		$this->db->where('jabatan_spv_id', $data);
		return $this->db->get()->row_array();
	}

	public function getbidang_basedskpd($data){
		$this->db->select('*');
		$this->db->from('m_bidang_skpd');
		$this->db->where('skpd_id', $data);
		return $this->db->get()->result_array();
	}

	// public function getjabatan_basedbidang($data){
	// 	$this->db->select('*');
	// 	$this->db->from('m_jabatan_spv');
	// 	$this->db->where($data['bidang_skpd_id']);
	// 	$this->db->where('author_id != 1');
	// 	$this->db->where($data['author_id']);
	// 	return $this->db->get()->result_array();
	// }


	public function getjabatan_basedbidang($data){
		$this->db->select('
			b.deskripsi as nama_bidang,
			j.deskripsi as nama_jabatan,
			j.id as id_jabatan,
			s.jabatan_spv_id,
			j.author_id');
		$this->db->from('m_bidang_skpd b');
		$this->db->join('m_jabatan_spv j ', 'b.id = j.bidang_skpd_id');
		$this->db->join('t_spv s ', 'j.id = s.jabatan_spv_id', 'LEFT');
		$this->db->where('j.author_id != 2');
		$this->db->where('j.bidang_skpd_id', $data['bidang_skpd_id']);
		$this->db->where('b.skpd_id', $data['skpd_id']);
		$this->db->where('j.author_id !=1');
		$this->db->where('jabatan_spv_id is null');
		return $this->db->get()->result_array();
	}

	// public function getjabatan_basedbidang_edit($data){
	// 	$this->db->select('
	// 		b.deskripsi as nama_bidang,
	// 		j.deskripsi as nama_jabatan,
	// 		j.id as id_jabatan,
	// 		s.jabatan_spv_id,
	// 		j.author_id');
	// 	$this->db->from('m_bidang_skpd b');
	// 	$this->db->join('m_jabatan_spv j ', 'b.id = j.bidang_skpd_id');
	// 	$this->db->join('t_spv s ', 'j.id = s.jabatan_spv_id', 'LEFT');
	// 	$this->db->where('j.author_id != 2');
	// 	$this->db->where('j.bidang_skpd_id', $data['bidang_skpd_id']);
	// 	$this->db->where('b.skpd_id', $data['skpd_id']);
	// 	$this->db->where('j.author_id !=1');
	// 	$this->db->where('j.author_id !=3');
	// 	// $this->db->where('jabatan_spv_id is null');
	// 	return $this->db->get()->result_array();
	// }


	public function getjabatan_edit($data){
		$this->db->select('
			DISTINCT(s.jabatan_spv_id),
			b.deskripsi as nama_bidang,
			j.deskripsi as nama_jabatan,
			j.id as id_jabatan,
			s.jabatan_spv_id,
			j.author_id');
		$this->db->from('m_bidang_skpd b');
		$this->db->join('m_jabatan_spv j ', 'b.id = j.bidang_skpd_id');
		$this->db->join('t_spv s ', 'j.id = s.jabatan_spv_id', 'LEFT');
		// $this->db->join('t_akun a ', 's.kode_spv = a.kode', 'LEFT');
		$this->db->where('j.author_id != 2');
		$this->db->where('j.author_id != 1');
		$this->db->where('j.author_id != 3');
		// $this->db->where('a.stat_akun_id = 1');
		$this->db->where('j.bidang_skpd_id', $data['bidang_skpd_id']);
		$this->db->where('b.skpd_id', $data['skpd_id']);
		// $this->db->where('jabatan_spv_id is null');

		// $this->db->group_by('s.jabatan_spv_id'); 
		return $this->db->get()->result_array();
	}


	public function bidang_basedon_author($data){
		$this->db->select('*');
		$this->db->from('m_bidang_skpd');
		$this->db->where('id', $data);
		return $this->db->get()->result_array();
	}

	// GET ALL LIST SKPD
	public function read_spv($data){
		$this->db->select('
			t_spv.jabatan_spv_id, 
			t_spv.nama, 
			t_spv.nip, 
			t_spv.kode_spv, 
			t_akun.username, 
			t_akun.stat_akun_id, 
			m_stat_akun.deskripsi AS status, 
			m_jabatan_spv.id as jabatan_spv_id, 
			m_jabatan_spv.deskripsi AS jabatan, 
			m_skpd.deskripsi AS skpd, 
			m_skpd.alamat, 
			m_bidang_skpd.deskripsi AS bidang');
		$this->db->from('t_spv');
		$this->db->join('m_jabatan_spv', 'm_jabatan_spv.id = t_spv.jabatan_spv_id');
		$this->db->join('m_skpd', 'm_skpd.id = t_spv.skpd_id');
		$this->db->join('m_bidang_skpd', ' m_bidang_skpd.id = t_spv.bidang_skpd_id');
		$this->db->join('t_akun', 't_akun.kode = t_spv.kode_spv');
		$this->db->join('m_stat_akun', 'm_stat_akun.id = t_akun.stat_akun_id');
		$this->db->where('t_akun.author_id != 1' );
		$this->db->where('t_akun.author_id != 2' );
		$this->db->where('t_akun.kode !=', $data);
		return $this->db->get()->result_array();
	}
	// GET ALL LIST SKPD WHERE SKPD ID
	public function read_spv_byskpd($data){
		$this->db->select('
			t_spv.jabatan_spv_id, 
			t_spv.nama, 
			t_spv.nip, 
			t_spv.kode_spv, 
			t_akun.username, 
			t_akun.stat_akun_id, 
			m_stat_akun.deskripsi AS status,
			m_jabatan_spv.id jabatan_spv_id,
			m_jabatan_spv.deskripsi AS jabatan, 
			m_skpd.deskripsi AS skpd, 
			m_skpd.alamat, 
			m_bidang_skpd.deskripsi AS bidang');
		$this->db->from('t_spv');
		$this->db->join('m_jabatan_spv', 'm_jabatan_spv.id = t_spv.jabatan_spv_id');
		$this->db->join('m_skpd', 'm_skpd.id = t_spv.skpd_id');
		$this->db->join('m_bidang_skpd', ' m_bidang_skpd.id = t_spv.bidang_skpd_id');
		$this->db->join('t_akun', 't_akun.kode = t_spv.kode_spv');
		$this->db->join('m_stat_akun', 'm_stat_akun.id = t_akun.stat_akun_id');
		$this->db->where('t_akun.author_id != 1' );
		$this->db->where('t_akun.author_id != 2' );
		$this->db->where('t_spv.skpd_id', $data['skpd_id']);
		$this->db->where('t_akun.kode !=', $data['kode']);
		return $this->db->get()->result_array();
	}

	public function detail_spv($data){
		$this->db->select('s.id,s.kode_spv,s.nama,s.nip,s.skpd_id,s.jabatan_spv_id');
		$this->db->select('m.deskripsi AS skpd');
		$this->db->select('j.deskripsi AS jabatan');
		$this->db->select('b.deskripsi AS bidang,b.id AS bidang_id');
		$this->db->select('a.username,a.stat_akun_id');
		$this->db->select('t.deskripsi AS status');
		$this->db->select('a.author_id');
		$this->db->from('t_spv s');
		$this->db->join('m_skpd m', 's.skpd_id = m.id', 'LEFT');
		$this->db->join('m_jabatan_spv j', 's.jabatan_spv_id = j.id', 'LEFT');
		$this->db->join('m_bidang_skpd b', 's.bidang_skpd_id =  b.id', 'LEFT');
		$this->db->join('t_akun a', 's.kode_spv = a.kode', 'LEFT');
		$this->db->join('m_stat_akun t', 't.id = a.stat_akun_id', 'LEFT');
		$this->db->where('s.kode_spv', $data);
		return $this->db->get()->result_array();
	}

// CEK NIP
	public function check_availspv($nip, $where = array())
	{
		$this->db->select('t_spv.id');
		$this->db->from('t_spv');
		$this->db->join('t_akun', 't_akun.kode = t_spv.kode_spv');
		$this->db->where('t_akun.stat_akun_id !=',3);
		$this->db->where('t_spv.nip',$nip);
		return $this->db->get()->row_array();
	}

// Cek Jabatan
	public function check_jabatan($jabatan, $where = array()){
		$this->db->select('t_spv.jabatan_spv_id, t_spv.skpd_id, t_akun.stat_akun_id');
		$this->db->from('t_spv');
		$this->db->join('t_akun', 't_spv.kode_spv = t_akun.kode');
		$this->db->where('t_akun.stat_akun_id =',1);
		$this->db->where('t_spv.jabatan_spv_id', $jabatan);
		return $this->db->get()->row_array();
	}
	
	// CHECK STATUS KEPALA DINAS  DAN ADMIN SUDAH TERPAKAI ATAU TIDAK
	public function check_kadis_admin($skpd_id){
		$this->db->select('a.kode');
		$this->db->from('t_spv s');
		$this->db->join('m_jabatan_spv j', 'j.id = s.jabatan_spv_id');
		$this->db->join('t_akun a', 'a.kode = s.kode_spv');
		$this->db->where('s.skpd_id', $skpd_id);
		$this->db->where('a.stat_akun_id', 	1);
		$this->db->where('j.author_id = 3');
		return $this->db->get()->result_array();
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

	// FUNCITON GETK LENTH

	public function getRelation($db, $where){
		$this->db->select('count(id) as length');
		$this->db->from($db);
		$this->db->where($where);
		return $this->db->get()->row_array();
	}

	// FUNCTION GET KODE IN TSPV BY Jabatan
	public function getKodeSpvbyJabatan($data){
		$this->db->select('s.kode_spv, a.author_id, s.jabatan_spv_id');
		$this->db->from('t_spv s');
		$this->db->join('t_akun a', 'a.kode = s.kode_spv');
		$this->db->where('s.jabatan_spv_id', $data);
		return $this->db->get()->row_array();
	}

	// public function getDataMigrationTarget($where1, $where2){
	// 	$this->db->select('id, flag_migration');
	// 	$this->db->from('t_target_thl');
	// 	$this->db->where($where1);
	// 	$this->db->where($where2);
	// 	return $this->db->get()->result_array();
	// }

	public function getDataMigrationTarget($where2){
		$this->db->select('id, flag_migration');
		$this->db->from('t_target_thl');
		$this->db->where('(tgl_verifikasi_kabid is null or tgl_verifikasi_kasie is null)');
		$this->db->where($where2);
		return $this->db->get()->result_array();
	}

	public function getDataMigrationTargetIsNull($data){
		$this->db->select('
			ttt.kode_target_thl,
			ttt.id,
			ttt.kode_spv_kabid,
			ttt.kode_spv_kasie,
			ttt.flag_migration,
			ttt.tgl_verifikasi_kabid,
			ttt.tgl_verifikasi_kasie');
		$this->db->from('t_target_thl ttt');
		$this->db->join('m_jabatan_spv mj', 'ttt.bidang_skpd_id = mj.bidang_skpd_id');
		// $this->db->where('ttt.flag_migration',$data['flag_migration']);
		$this->db->where('mj.id',$data['jabatan_spv_id']);
		return $this->db->get()->result_array();
	}

	
	// public function getDataMigration($db, $where2){
	// 	$this->db->select('id,
	// 		kode_laporan_thl, 
	// 		flag_migration,
	// 		tgl_verifikasi_kabid,
	// 		tgl_verifikasi_kasie');
	// 	$this->db->from($db);
	// 	$this->db->where('(tgl_verifikasi_kabid is null or tgl_verifikasi_kasie is null)');
	// 	$this->db->where($where2);
	// 	return $this->db->get()->result_array();
	// }

	
	public function getDataMigrationIsNull($data){
		$this->db->select('
			lt.kode_laporan_thl,
			tbl.id,
			tbl.kode_spv_kabid,
			tbl.kode_spv_kasie,
			tbl.flag_migration,
			tbl.tgl_verifikasi_kabid,
			tbl.tgl_verifikasi_kasie');
		$this->db->from($data['tbl']);
		$this->db->join('t_laporan_thl lt', 'lt.kode_laporan_thl = tbl.kode_laporan_thl');
		$this->db->join('m_jabatan_spv mj', 'lt.bidang_skpd_id = mj.bidang_skpd_id');
		// $this->db->where('tbl.flag_migration',$data['flag_migration']);
		// $this->db->where($dataflag);
		$this->db->where('mj.id',$data['jabatan_spv_id']);
		return $this->db->get()->result_array();
	}


	// FUNCTION GET DATA THL BASED JABATAN SPV

	public function getDataThlByJabatan($data){
		$this->db->select('mj.id as id_jabatan_spv');
		$this->db->from('t_thl tt');
		$this->db->join('m_jabatan_spv mj', 'tt.bidang_skpd_id = mj.bidang_skpd_id');
		$this->db->where('mj.id',$data);
		return $this->db->get()->row_array();
	}



	// FUNCTION BATCH UPDATE 
	public function updateBatch($db, $data){
        return $this->db->update_batch($db['tbl'], $data, 'id'); 
        // return $this->db->update_batch(,$data, 'id'); 
	}

	// FUNCTION UPDATE DATA TARGET WHRE KODE

	public function updateLaporanThl($dataLaporan, $kode_laporan){
		$this->db->where('kode_laporan_thl', $kode_laporan);
        return $this->db->update('t_laporan_thl', $dataLaporan); 
	}

	// FUNCTION UPDATE T THL AND JOIN

	public function updateSpvThlMigration($dataThl){
		$sql = 'UPDATE t_thl AS t
			JOIN m_jabatan_spv AS mj ON mj.bidang_skpd_id = t.bidang_skpd_id
			SET '.$dataThl['data'].' WHERE  mj.id = '.$dataThl['jabatan_spv_id'].'';
		$this->db->query($sql);
	} 



	public function getAuthorbyJabatan($data){
		$this->db->select('author_id');
		$this->db->from('m_jabatan_spv');
		$this->db->where('id', $data);
		return $this->db->get()->row_array();
	}
}


