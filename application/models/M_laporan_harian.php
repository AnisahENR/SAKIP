<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_laporan_harian extends CI_Model 
{

    //set column field database for datatable orderable
	var $column_order = array(null,'t_thl.persetujuan','t_thl.nama','m_profesi_thl.deskripsi','t_laporan_thl.tgl_laporan','t_laporan_thl.jml_waktu',null,null);
    //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $column_search = array('kabid.nama','kasie.nama','t_thl.nama','m_profesi_thl.deskripsi','t_laporan_thl.tgl_laporan','m_bidang_skpd.deskripsi');
    // default order 
	var $order = array('v_status_laporan_thl.jumlah' => 'desc','t_laporan_thl.tgl_laporan' => 'asc');

	public function __construct()
	{
		parent::__construct();
	}



    ////////////////////////////// DATATABLE SERVER SIDE///////////////////////////////////////////////////////
	private function get_laporan_query()
	{
		$this->db->select('t_laporan_thl.kode_laporan_thl,kasie.nama kasie,kabid.nama kabid, t_thl.nama,m_profesi_thl.deskripsi profesi, t_laporan_thl.tgl_laporan, t_laporan_thl.jml_waktu,m_bidang_skpd.deskripsi bidang,m_skpd.deskripsi skpd,v_status_laporan_thl.jumlah');
		$this->db->from('t_laporan_thl');
        $this->db->join('m_skpd', 'm_skpd.id = t_laporan_thl.skpd_id');
        $this->db->join('m_bidang_skpd', 'm_bidang_skpd.id = t_laporan_thl.bidang_skpd_id');
		// $this->db->join('m_stat_laporan', 'm_stat_laporan.id = t_laporan_thl.stat_laporan_id');
        $this->db->join('t_thl', 't_thl.kode_thl = t_laporan_thl.kode_thl');
        $this->db->join('v_status_laporan_thl', 'v_status_laporan_thl.kode_laporan = t_laporan_thl.kode_laporan_thl');
        $this->db->join('t_spv kasie', 'kasie.kode_spv = t_laporan_thl.kode_spv_kasie');
        $this->db->join('t_spv kabid', 'kabid.kode_spv = t_laporan_thl.kode_spv_kabid');
        $this->db->join('m_profesi_thl', 'm_profesi_thl.id = t_thl.profesi_thl_id');


        // $this->db->from('v_summary_laporan_thl_new');

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

    public function get_data_laporan($where = array(),$range_date = array(),$flag_migration = array())
    {
    	$this->get_laporan_query();
    	if (isset($_POST['length'])){
    		if ($_POST['length'] != -1){
    			$this->db->limit($_POST['length'], $_POST['start']);
            }
        }
        $this->db->where($where);
        $this->db->where_in('t_thl.flag_migration',$flag_migration);
        $this->db->where("t_laporan_thl.tgl_laporan BETWEEN '".$range_date[0]."' AND '".$range_date[1]."'", null, false);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->get_laporan_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('t_laporan_thl');
        return $this->db->count_all_results();
    }

    // public function getrekap_kegiatan($where = array(),$range_date = array())
    // {
    //     $this->db->select('tlkt.kode_laporan_thl,SUM(CASE WHEN tlkt.stat_laporan_id = 1 OR tlkt.stat_laporan_id = 2 THEN 1 ELSE 0 END) AS jumlah');
    //     $this->db->from('t_laporan_thl tlt');
    //     // $this->db->where('kode_spv', $kode_spv);
    //     $this->db->join('t_laporan_kegiatan_thl tlkt', 'tlt.kode_laporan_thl = tlkt.kode_laporan_thl');
    //     $this->db->where($where);
    //     $this->db->where("tlt.tgl_laporan BETWEEN '".$range_date[0]."' AND '".$range_date[1]."'", null, false);
    //     $this->db->group_by('tlkt.kode_laporan_thl');
    //     return $this->db->get()->result_array();
    // }

    // public function getrekap_lainnya($where = array(),$range_date = array())
    // {
    //     $this->db->select('m_stat_laporan.id,m_stat_laporan.id,COUNT(t_laporan_thl.stat_laporan_id) rekap');
    //     $this->db->from('t_laporan_thl');
    //     // $this->db->where('kode_spv', $kode_spv);
    //     $this->db->join('m_stat_laporan', 'm_stat_laporan.id = t_laporan_thl.stat_laporan_id '.$additional_where.' AND YEAR(tgl_laporan) = \''.date('Y').'\'','right');
    //     $this->db->where($where);
    //     $this->db->where("t_laporan_thl.tgl_laporan BETWEEN '".$range_date[0]."' AND '".$range_date[1]."'", null, false);
    //     return $this->db->get()->result_array();
    // }

    // ============================================ 
    public function get_identitas_laporan($kode_laporan_thl)
    {
        $this->db->select('t_laporan_thl.kode_thl,t_laporan_thl.kode_target_thl,t_laporan_thl.kode_spv_kasie,kasie.nama kasie,t_laporan_thl.kode_spv_kabid,kabid.nama kabid,t_thl.nik,t_thl.nama,m_profesi_thl.deskripsi profesi,t_laporan_thl.tgl_laporan,t_laporan_thl.jml_waktu,m_bidang_skpd.deskripsi bidang,m_skpd.deskripsi skpd,t_laporan_thl.created_at,t_thl.flag_migration,migration.deskripsi migration');
        $this->db->from('t_laporan_thl');
        $this->db->join('t_thl', 't_thl.kode_thl = t_laporan_thl.kode_thl');
        $this->db->join('m_bidang_skpd', 'm_bidang_skpd.id = t_laporan_thl.bidang_skpd_id');
        $this->db->join('t_spv kasie', 'kasie.kode_spv = t_laporan_thl.kode_spv_kasie');
        $this->db->join('t_spv kabid', 'kabid.kode_spv = t_laporan_thl.kode_spv_kabid');
        $this->db->join('m_skpd', 'm_skpd.id = t_laporan_thl.skpd_id');
        $this->db->join('m_flag_migration migration', 'migration.id = t_thl.flag_migration');
        $this->db->join('m_profesi_thl', 'm_profesi_thl.id = t_laporan_thl.profesi_thl_id');
        $this->db->where('t_laporan_thl.kode_laporan_thl', $kode_laporan_thl);
        return $this->db->get()->row_array();
    }

    public function getlist_kegiatan($kode_laporan_thl)
    {
        $this->db->select('mkt.deskripsi kegiatan, tlkt.*,tlt.kode_thl,tlt.tgl_laporan');
        $this->db->from('t_laporan_kegiatan_thl tlkt');
        $this->db->join('m_kegiatan_thl mkt','mkt.id = tlkt.kegiatan_thl_id');
        $this->db->join('t_laporan_thl tlt','tlt.kode_laporan_thl = tlkt.kode_laporan_thl');
        // $this->db->join('t_spv kasie', 'kasie.kode_spv = tlkt.kode_spv_kasie');
        // $this->db->join('t_spv kabid', 'kabid.kode_spv = tlkt.kode_spv_kabid');
        $this->db->where('tlkt.kode_laporan_thl',$kode_laporan_thl);
        $this->db->order_by('tlkt.stat_laporan_id','asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getlist_lainnya($kode_laporan_thl)
    {
        $this->db->select('tllt.*,tlt.kode_thl,tlt.tgl_laporan');
        $this->db->from('t_laporan_lain_thl tllt');
        $this->db->join('t_laporan_thl tlt','tlt.kode_laporan_thl = tllt.kode_laporan_thl');
        $this->db->where('tllt.kode_laporan_thl',$kode_laporan_thl);
        $this->db->order_by('tllt.stat_laporan_id','asc');
        $query = $this->db->get();
        return $query->result_array();
    }

}
