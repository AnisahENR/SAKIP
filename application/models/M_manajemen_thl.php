<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_manajemen_thl extends CI_Model 
{

    //set column field database for datatable orderable
	var $column_order = array(null,'t_akun.username','t_thl.nik','t_thl.nama','m_stat_akun.deskripsi',null,'m_skpd.deskripsi');
    //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $column_search = array('t_akun.username','t_thl.nik', 't_thl.nama');
    // default order 
	var $order = array('t_thl.id' => 'asc');

	public function __construct()
	{
		parent::__construct();
	}



    ////////////////////////////// DATATABLE SERVER SIDE///////////////////////////////////////////////////////
	private function get_akun_query()
	{
		$this->db->select('t_thl.kode_thl,t_akun.username,t_thl.nik,t_thl.nama,m_stat_akun.id stat_akun_id,m_stat_akun.deskripsi status,m_skpd.deskripsi skpd,m_profesi_thl.deskripsi profesi');
		$this->db->from('t_thl');
		$this->db->join('m_skpd', 'm_skpd.id = t_thl.skpd_id');
        $this->db->join('m_profesi_thl', 'm_profesi_thl.id = t_thl.profesi_thl_id');
		$this->db->join('t_akun', 't_akun.kode = t_thl.kode_thl');
		$this->db->join('m_stat_akun', 'm_stat_akun.id = t_akun.stat_akun_id');


		$i = 0;

        // Set search query
		if (isset($_POST['search'])) {
            foreach ($this->column_search as $item) // loop column 
            {   
                if($_POST['search']['value']) // if datatable send POST for search
                {

                    if($i===0) // first loop
                    {
                        $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                        $this->db->like($item, $_POST['search']['value']);
                    }
                    else
                    {
                    	$this->db->or_like($item, $_POST['search']['value']);
                    }

                    if(count($this->column_search) - 1 == $i) //last loop
                        $this->db->group_end(); //close bracket
                    }
                    $i++;
                }
            }

        if(isset($_POST['order'])) // here order processing
        {
        	$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
        	$order = $this->order;
        	$this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_data_thl($where = array())
    {
    	$this->get_akun_query();
    	if (isset($_POST['length'])){
    		if ($_POST['length'] != -1){
    			$this->db->limit($_POST['length'], $_POST['start']);
            }
        }
        $this->db->where(['t_akun.stat_akun_id !=' => 3]+$where);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->get_akun_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('t_thl');
        return $this->db->count_all_results();
    }

    // ============================================  

    public function check_availthl($nik,$where = array())
    {
        $this->db->select('t_thl.id');
        $this->db->from('t_thl');
        $this->db->join('t_akun', 't_akun.kode = t_thl.kode_thl');
        $this->db->where('t_akun.stat_akun_id !=',3);
        $this->db->where('t_thl.nik', $nik);
        $this->db->where($where);
        return $this->db->get()->row_array();
    }

    public function get_identitas_thl($kode_thl)
    {
        $this->db->select('t_thl.kode_thl,t_akun.username,t_thl.nik,t_thl.nama,t_thl.email,t_thl.telepon,t_thl.skpd_id,t_thl.profesi_thl_id,t_thl.pendidikan_id,m_pendidikan.deskripsi pendidikan,t_thl.tmt_pendidikan,t_thl.ijazah,t_thl.scan_ktp,m_stat_akun.id stat_akun_id,m_stat_akun.deskripsi status,m_skpd.deskripsi skpd,t_thl.bidang_skpd_id,m_bidang_skpd.deskripsi bidang,m_profesi_thl.deskripsi profesi,t_thl.kode_spv_kabid,kabid.nama kabid,t_thl.kode_spv_kasie,kasie.nama kasie,t_thl.stat_perkawinan_id,m_stat_perkawinan.deskripsi kawin,t_thl.tmpt_lahir,lahir.deskripsi lahir,t_thl.tmpt_asal,asal.deskripsi asal,t_thl.alamat,t_thl.tgl_lahir,t_thl.flag_migration,migration.deskripsi migration');
        $this->db->from('t_thl');
        $this->db->join('m_skpd', 'm_skpd.id = t_thl.skpd_id');
        $this->db->join('m_bidang_skpd', 'm_bidang_skpd.id = t_thl.bidang_skpd_id');
        $this->db->join('m_profesi_thl', 'm_profesi_thl.id = t_thl.profesi_thl_id');
        $this->db->join('m_stat_perkawinan', 'm_stat_perkawinan.id = t_thl.stat_perkawinan_id');
        $this->db->join('t_akun', 't_akun.kode = t_thl.kode_thl');
        $this->db->join('m_pendidikan', 'm_pendidikan.id = t_thl.pendidikan_id');
        $this->db->join('m_stat_akun', 'm_stat_akun.id = t_akun.stat_akun_id');
        $this->db->join('t_spv kabid', 'kabid.kode_spv = t_thl.kode_spv_kabid');
        $this->db->join('t_spv kasie', 'kasie.kode_spv = t_thl.kode_spv_kasie');
        $this->db->join('m_flag_migration migration', 'migration.id = t_thl.flag_migration');
        $this->db->join('m_wilayah lahir', 'lahir.id = t_thl.tmpt_lahir');
        $this->db->join('m_wilayah asal', 'asal.id = t_thl.tmpt_asal');
        $this->db->where('t_thl.kode_thl', $kode_thl);
        return $this->db->get()->row_array();
    }

    public function get_rekaptarget_thl($kode_thl)
    {
        $this->db->select('m_stat_laporan.id,m_stat_laporan.id,COUNT(t_target_thl.stat_laporan_id) rekap');
        $this->db->from('t_target_thl');
        // $this->db->where('kode_thl', $kode_thl);
        $this->db->join('m_stat_laporan', 'm_stat_laporan.id = t_target_thl.stat_laporan_id AND t_target_thl.kode_thl = \''.$kode_thl.'\' AND YEAR(tgl_laporan) = \''.date('Y').'\'','right');
        $this->db->group_by('m_stat_laporan.id');
        $this->db->order_by('m_stat_laporan.id','asc');
        return $this->db->get()->result_array();
    } 

    public function get_rekapaktivitas_thl($data)
    {
        $stored_proc = "CALL get_rekap_aktivitas_thl(?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->result_array();
    }
}
