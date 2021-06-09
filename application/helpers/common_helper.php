<?php
function re_image($path){
	$curr_height = 1024;
	list($width, $height) = getimagesize($path);
	$newwidth = abs($width * $curr_height /$height);
	$newheight = $curr_height ;

// Load
	$source = imagecreatefromjpeg($path);
	$dst = imagecreatetruecolor($newwidth, $newheight);

// Resize
	imagecopyresized($dst, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	imageresolution($dst, 75, 75);
	imagejpeg($dst,$path);

	return true;
}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
                // if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
	return true;
}

  // filter xss
function xss_filter($value){
	return strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '-', htmlspecialchars_decode($value)));
}

function create_kode(){
	return preg_replace('/[^\p{L}\p{N}\s]/u','', crypt(date('YmdHis'),rand()));
}

function base64url_encode($plainText)
{
	return strtr(base64_encode($plainText), '+/=', '-_paket');
}

function base64url_decode($b64Text)
{
	return base64_decode(strtr($b64Text, '-_paket', '+/='));
}

// 
function label_stat_akun($stat_akun_id){
	switch ($stat_akun_id) {
		case 1: $label = 'success';break;
		case 2: $label = 'danger';break;
		case 3: $label = 'secondary';break;
	}
	return $label;
}
// 
function label_stat_laporan($stat_laporan_id){
	switch ($stat_laporan_id) {
		case 1: $label = ['warning','Verifikasi Kasie'];break;
		case 2: $label = ['warning','Verifikasi Kabid'];break;
		case 3: $label = ['success','Disetujui'];break;
		case 4: $label = ['danger','Ditolak Kasie'];break;
		case 5: $label = ['danger','Ditolak Kabid'];break;
	}
	return $label;
}
// 
// function kode_save_dokumen($stat_laporan_id){
// 	switch ($stat_laporan_id) {
// 		case 1: $label = ['warning','Verifikasi'];break;
// 		case 2: $label = ['success','Disetujui'];break;
// 		case 3: $label = ['danger','Ditolak'];break;
// 	}
// 	return $label;
// }


function onetable_all_data($table,$where =array(),$order_by = array(),$limit = null){
	$CI = &get_instance();
	$CI->load->database();
	$CI->db->from($table);
	$CI->db->where($where);
	if($order_by){
		$CI->db->order_by($order_by[0],$order_by[1]);		
	}
	$CI->db->limit($limit);
	return $CI->db->get()->result_array();
}

function onetable_current_data($table,$data){
	$CI = &get_instance();
	$CI->load->database();
	$CI->db->from($table);
	$CI->db->where($data);
	return $CI->db->get()->row();
}

function update_data($table,$update_data){
	$CI = &get_instance();
	$CI->load->database();
	$CI->db->where(array_slice($update_data,0,1));
	return $CI->db->update($table, $update_data);
}

function insert_data($table,$insert){
	$CI = &get_instance();
	$CI->load->database();
	return $CI->db->insert($table, $insert);
}

function delete_data($table,$where = array()){
	$CI = &get_instance();
	$CI->load->database();
	$CI->db->where($where);
	return $CI->db->delete($table);
}

function skpd_options($skpd_id = null){
	$CI = &get_instance();
	$CI->load->database();
	$where = array();
	if($skpd_id){
		$where['id'] = $skpd_id;
	}
	$CI->db->from('m_skpd');
	$CI->db->where($where);
	$list = $CI->db->get()->result_array();
	if($list){
		if(!$skpd_id){
			$result[''] = ' Silahkan Pilih SKPD ';
		}
		foreach ($list as $key) {
			$result[$key['id']] = ucwords($key['deskripsi']);
		}
		return $result;
	}
	return false;
}

function bidang_skpd_options($skpd_id = null,$bidang_skpd_id = null){
	$CI = &get_instance();
	$CI->load->database();
	$where = array();
	if($skpd_id){
		$where['skpd_id'] = $skpd_id;
	}
	if($bidang_skpd_id){
		$where['id'] = $bidang_skpd_id;
	}
	$CI->db->from('m_bidang_skpd');
	$CI->db->where($where);
	$list = $CI->db->get()->result_array();
	if($list){
		$result[''] = ' Silahkan Pilih Bidang SKPD ';
		foreach ($list as $key) {
			$result[$key['id']] = ucwords($key['deskripsi']);
		}
		return $result;
	}
	return false;
}

function kabid_skpd_options($bidang_skpd_id = null){
	$CI = &get_instance();
	$CI->load->database();
	$where = array();
	if($bidang_skpd_id){
		$where['t_spv.bidang_skpd_id'] = $bidang_skpd_id;
	}
	$CI->db->select('t_spv.kode_spv,t_spv.nama');
	$CI->db->from('t_spv');
	$CI->db->join('t_akun', 't_akun.kode = t_spv.kode_spv');
	$CI->db->join('m_stat_akun', 'm_stat_akun.id = t_akun.stat_akun_id');
	$CI->db->where($where+['t_akun.author_id' => 4,'t_akun.stat_akun_id' => 1]);
	$list = $CI->db->get()->result_array();
	// echo $CI->db->last_query();
	// var_dump($list);die;
	if($list){
		if(!$bidang_skpd_id){
			$result[''] = 'Pilih Kepala Bidang ';
		}
		foreach ($list as $key) {
			$result[$key['kode_spv']] = ucwords($key['nama']);
		}
		return $result;
	}
	return false;
}

function kasi_bidskpd_options($bidang_skpd_id = null){
	$CI = &get_instance();
	$CI->load->database();
	$where = array();
	if($bidang_skpd_id){
		$where['t_spv.bidang_skpd_id'] = $bidang_skpd_id;
	}
	$CI->db->select('t_spv.kode_spv,t_spv.nama');
	$CI->db->from('t_spv');
	$CI->db->join('t_akun', 't_akun.kode = t_spv.kode_spv');
	$CI->db->join('m_stat_akun', 'm_stat_akun.id = t_akun.stat_akun_id');
	$CI->db->where($where+['t_akun.author_id' => 5,'t_akun.stat_akun_id' => 1]);
	$list = $CI->db->get()->result_array();
	if($list){
		$result[''] = 'Pilih Kepala Seksi ';
		foreach ($list as $key) {
			$result[$key['kode_spv']] = ucwords($key['nama']);
		}
		return $result;
	}
	return false;
}

function profesi_thl_options($profesi_thl_id = null){
	$CI = &get_instance();
	$CI->load->database();
	$where = array();
	if($profesi_thl_id){
		$where['id'] = $profesi_thl_id;
	}
	$CI->db->from('m_profesi_thl');
	$CI->db->where($where);
	$list = $CI->db->get()->result_array();
	if($list){
		if(!$profesi_thl_id){
			$result[''] = ' Silahkan Pilih Profesi THL ';
		}
		foreach ($list as $key) {
			$result[$key['id']] = ucwords($key['deskripsi']);
		}
		return $result;
	}
	return false;
}

function stat_perkawinan_options($stat_perkawinan_id = null){
	$CI = &get_instance();
	$CI->load->database();
	$where = array();
	if($stat_perkawinan_id){
		$where['id'] = $stat_perkawinan_id;
	}
	$CI->db->from('m_stat_perkawinan');
	$CI->db->where($where);
	$list = $CI->db->get()->result_array();
	if($list){
		if(!$stat_perkawinan_id){
			$result[''] = ' Pilih Status Kawin';
		}
		foreach ($list as $key) {
			$result[$key['id']] = ucwords($key['deskripsi']);
		}
		return $result;
	}
	return false;
}

function pendidikan_options($pendidikan_id = null){
	$CI = &get_instance();
	$CI->load->database();
	$where = array();
	if($pendidikan_id){
		$where['id'] = $pendidikan_id;
	}
	$CI->db->from('m_pendidikan');
	$CI->db->where($where);
	$list = $CI->db->get()->result_array();
	if($list){
		if(!$pendidikan_id){
			$result[''] = ' Pilih Pendidikan ';
		}
		foreach ($list as $key) {
			$result[$key['id']] = ucwords($key['deskripsi']);
		}
		return $result;
	}
	return false;
}

function wilayah_options($wilayah_id = null){
	$CI = &get_instance();
	$CI->load->database();
	$where = array();
	if($wilayah_id){
		$where['id'] = $wilayah_id;
	}
	$CI->db->from('m_wilayah');
	$CI->db->where($where);
	$list = $CI->db->get()->result_array();
	if($list){
		if(!$wilayah_id){
			$result[''] = ' Silahkan Pilih Wilayah';
		}
		foreach ($list as $key) {
			$result[$key['id']] = ucwords($key['deskripsi']);
		}
		return $result;
	}
	return false;
}

function stat_laporan_options(){
	$CI = &get_instance();
	$CI->load->database();
	$CI->db->from('m_stat_laporan');
	$list = $CI->db->get()->result_array();
	if($list){
		$result[''] = ' Pilih Status ';
		$result[0] 	= ' Seluruh Laporan';
		foreach ($list as $key) {
			$result[$key['id']] = ucwords($key['deskripsi']);
		}
		return $result;
	}
	return false;
}

?>