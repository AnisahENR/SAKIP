<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_beranda extends CI_Model {
    
    //set column field database for datatable orderable
    var $column_order_target    = array(    1 => array(null, 't_target_thl.tgl_laporan', 'm_skpd.deskripsi', 'm_bidang_skpd.deskripsi', 'spv_kabid.nama', 'spv_kasie.nama', 't_thl.nama', 'm_stat_laporan.id', 't_target_thl.jml_waktu'),
                                            2 => array(null, 't_target_thl.tgl_laporan', 'm_bidang_skpd.deskripsi', 'spv_kabid.nama', 'spv_kasie.nama', 't_thl.nama', 'm_stat_laporan.id', 't_target_thl.jml_waktu'),
                                            3 => array(null, 't_target_thl.tgl_laporan', 'm_bidang_skpd.deskripsi', 'spv_kabid.nama', 'spv_kasie.nama', 't_thl.nama', 'm_stat_laporan.id', 't_target_thl.jml_waktu'),
                                            4 => array(null, 't_target_thl.tgl_laporan', 'spv_kasie.nama', 't_thl.nama', 'm_stat_laporan.id', 't_target_thl.jml_waktu'),
                                            5 => array(null, 't_target_thl.tgl_laporan', 't_thl.nama', 'm_stat_laporan.id', 't_target_thl.jml_waktu'),
                                            6 => array(null, 't_target_thl.tgl_laporan', 't_thl.nama', 'm_stat_laporan.id', 't_target_thl.jml_waktu'));

    var $column_order_laporan   = array(    1 => array(null, 'tgl_laporan', 'skpd', 'bidang_skpd', 'nama_kabid', 'nama_kasie', 'nama_thl', 'status_verifikasi', 'waktu'),
                                            2 => array(null, 'tgl_laporan', 'bidang_skpd', 'nama_kabid', 'nama_kasie', 'nama_thl', 'status_verifikasi', 'waktu'),
                                            3 => array(null, 'tgl_laporan', 'bidang_skpd', 'nama_kabid', 'nama_kasie', 'nama_thl', 'status_verifikasi', 'waktu'),
                                            4 => array(null, 'tgl_laporan', 'nama_kasie', 'nama_thl', 'status_verifikasi', 'waktu'),
                                            5 => array(null, 'tgl_laporan', 'nama_thl', 'status_verifikasi', 'waktu'),
                                            6 => array(null, 'tgl_laporan', 'nama_thl', 'status_verifikasi', 'waktu'));

    //set column field database for datatable searchable
    var $column_search_target   = array(    1 => array('t_target_thl.tgl_laporan', 'm_skpd.deskripsi', 'm_bidang_skpd.deskripsi', 'spv_kabid.nama', 'spv_kasie.nama', 't_thl.nama'),
                                            2 => array('t_target_thl.tgl_laporan', 'm_bidang_skpd.deskripsi', 'spv_kabid.nama', 'spv_kasie.nama', 't_thl.nama'),
                                            3 => array('t_target_thl.tgl_laporan', 'm_bidang_skpd.deskripsi', 'spv_kabid.nama', 'spv_kasie.nama', 't_thl.nama'),
                                            4 => array('t_target_thl.tgl_laporan', 'spv_kasie.nama', 't_thl.nama'),
                                            5 => array('t_target_thl.tgl_laporan', 't_thl.nama'),
                                            6 => array('t_target_thl.tgl_laporan', 't_thl.nama'));

    var $column_search_laporan  = array(    1 => array('tgl_laporan', 'skpd', 'bidang_skpd', 'nama_kabid', 'nama_kasie', 'nama_thl'),
                                            2 => array('tgl_laporan', 'bidang_skpd', 'nama_kabid', 'nama_kasie', 'nama_thl'),
                                            3 => array('tgl_laporan', 'bidang_skpd', 'nama_kabid', 'nama_kasie', 'nama_thl'),
                                            4 => array('tgl_laporan', 'nama_kasie', 'nama_thl'),
                                            5 => array('tgl_laporan', 'nama_thl'),
                                            6 => array('tgl_laporan', 'nama_thl'));
    // default order 
    var $order_target = array(  't_target_thl.tgl_laporan'  => 'asc',
                                't_thl.nama'                => 'asc');
    var $order_laporan = array(	'tgl_laporan'   => 'asc',
    					        'nama_thl'     => 'asc');

    public function __construct() {
        parent::__construct();
    }

    public function get_beranda($data)
    {
        $stored_proc = "CALL get_beranda_current_month(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->result_array();
    }

    public function get_rekap_status_target($data)
    {
        $stored_proc = "CALL get_beranda_rekap_status_target_current_month(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->result_array();
    }

    public function get_rekap_status_laporan($data)
    {
        $stored_proc = "CALL get_beranda_rekap_status_laporan_current_month(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->result_array();
    }

    public function get_beranda_rekap_umur_thl($data)
    {
        $stored_proc = "CALL get_beranda_rekap_umur_thl(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->result_array();
    }

    public function get_beranda_rekap_pendidikan_thl($data)
    {
        $stored_proc = "CALL get_beranda_rekap_pendidikan_thl(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->result_array();
    }

    private function get_skpd_bidang($data)
    {
        $this->db->select('skpd_id, bidang_skpd_id');
        $this->db->from('t_spv');
        $this->db->where('kode_spv', $data['param_kode']);

        $query = $this->db->get();
        return $query->row_array();
    }

    private function get_ringkasan_target_thl_query($data)
    {
        $skpd_bidang = $this->get_skpd_bidang($data);

        $this->db->select(  "t_target_thl.kode_target_thl                           AS kode_thl,
                            m_skpd.deskripsi                                        AS nama_skpd,
                            m_bidang_skpd.deskripsi                                 AS nama_bidang_skpd,
                            spv_kabid.nama                                          AS nama_kabid,
                            spv_kasie.nama                                          AS nama_kasie,
                            t_thl.nama                                              AS nama_thl,
                            DATE(t_target_thl.tgl_laporan)                          AS tgl_laporan,
                            m_stat_laporan.id                                       AS status_laporan_id,
                            m_stat_laporan.deskripsi                                AS status_laporan,
                            t_target_thl.jml_waktu                                  AS waktu");
        $this->db->from('t_target_thl');
        $this->db->join('t_thl', 't_target_thl.kode_thl = t_thl.kode_thl');
        $this->db->join('m_stat_laporan' ,'t_target_thl.stat_laporan_id = m_stat_laporan.id');
        $this->db->join('m_skpd' ,'t_target_thl.skpd_id = m_skpd.id');
        $this->db->join('m_bidang_skpd' ,'t_target_thl.bidang_skpd_id = m_bidang_skpd.id');
        $this->db->join('t_target_detail_thl', 't_target_thl.kode_target_thl = t_target_detail_thl.kode_target_thl', 'left');
        $this->db->join('m_kegiatan_thl', 'm_kegiatan_thl.id = t_target_detail_thl.kegiatan_thl_id', 'left');
        $this->db->join('t_spv AS spv_kasie', 't_target_thl.kode_spv_kasie = spv_kasie.kode_spv', 'left');
        $this->db->join('t_spv AS spv_kabid', 't_target_thl.kode_spv_kabid = spv_kabid.kode_spv', 'left');

        date_default_timezone_set("Asia/Jakarta");
        $first_day_this_month = date('Y-m-01');
        $last_day_this_month  = date('Y-m-t');

        $this->db->where('t_target_thl.tgl_laporan >=', $first_day_this_month);
        $this->db->where('t_target_thl.tgl_laporan <=', $last_day_this_month);

        switch ($data['param_author']) {
            case 2:
                $this->db->where('t_target_thl.skpd_id', $skpd_bidang['skpd_id']);
                break;
            case 3:
                $this->db->where('t_target_thl.skpd_id', $skpd_bidang['skpd_id']);
                break;
            case 4:
                $this->db->where('t_target_thl.bidang_skpd_id', $skpd_bidang['bidang_skpd_id']);
                break;
            case 5:
                $this->db->where('t_target_thl.kode_spv_kasie', $data['param_kode']);
                break;
            case 6:
                $this->db->where('t_target_thl.kode_thl', $data['param_kode']);
                break;
        }
 
        $i = 0;
        
        // Set search query
        if (isset($_POST['search'])) {
            foreach ($this->column_search_target[$data['param_author']] as $item) // loop column 
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
     
                    if(count($this->column_search_target[$data['param_author']]) - 1 == $i) //last loop
                        $this->db->group_end(); //close bracket
                }
                $i++;
            }
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order_target[$data['param_author']][$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            foreach ($this->order_target as $key => $value) // loop column 
            {
                $this->db->order_by($key, $value);
            }    
        }
    }
 
    public function get_ringkasan_target_thl($data)
    {
        $this->get_ringkasan_target_thl_query($data);
        if (isset($_POST['length']))
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_target($data)
    {
        $this->get_ringkasan_target_thl_query($data);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all_target($data)
    {
        $skpd_bidang = $this->get_skpd_bidang($data);

        $this->db->select('1');
        $this->db->from('t_target_thl');
        $this->db->join('t_thl', 't_target_thl.kode_thl = t_thl.kode_thl');
        $this->db->join('m_stat_laporan' ,'t_target_thl.stat_laporan_id = m_stat_laporan.id');
        $this->db->join('t_target_detail_thl', 't_target_thl.kode_target_thl = t_target_detail_thl.kode_target_thl', 'left');
        $this->db->join('m_kegiatan_thl', 'm_kegiatan_thl.id = t_target_detail_thl.kegiatan_thl_id', 'left');

        date_default_timezone_set("Asia/Jakarta");
        $first_day_this_month = date('Y-m-01');
        $last_day_this_month  = date('Y-m-t');

        $this->db->where('t_target_thl.tgl_laporan >=', $first_day_this_month);
        $this->db->where('t_target_thl.tgl_laporan <=', $last_day_this_month);

        switch ($data['param_author']) {
            case 2:
                $this->db->where('t_target_thl.skpd_id', $skpd_bidang['skpd_id']);
                break;
            case 3:
                $this->db->where('t_target_thl.skpd_id', $skpd_bidang['skpd_id']);
                break;
            case 4:
                $this->db->where('t_target_thl.bidang_skpd_id', $skpd_bidang['bidang_skpd_id']);
                break;
            case 5:
                $this->db->where('t_target_thl.kode_spv_kasie', $data['param_kode']);
                break;
            case 6:
                $this->db->where('t_target_thl.kode_thl', $data['param_kode']);
                break;
        }

        return $this->db->count_all_results();
    }

    private function get_ringkasan_laporan_thl_query($data)
    {
        $skpd_bidang = $this->get_skpd_bidang($data);

        $this->db->from('v_summary_laporan_thl_new');

        date_default_timezone_set("Asia/Jakarta");
        $first_day_this_month = date('Y-m-01');
        $last_day_this_month  = date('Y-m-t');

        $this->db->where('tgl_laporan >=', $first_day_this_month);
        $this->db->where('tgl_laporan <=', $last_day_this_month);

        switch ($data['param_author']) {
            case 2:
                $this->db->where('skpd_id', $skpd_bidang['skpd_id']);
                break;
            case 3:
                $this->db->where('skpd_id', $skpd_bidang['skpd_id']);
                break;
            case 4:
                $this->db->where('bidang_skpd_id', $skpd_bidang['bidang_skpd_id']);
                break;
            case 5:
                $this->db->where('kode_spv_kasie', $data['param_kode']);
                break;
            case 6:
                $this->db->where('kode_thl', $data['param_kode']);
                break;
        }
 
        $i = 0;
        
        // Set search query
        if (isset($_POST['search'])) {
            foreach ($this->column_search_laporan[$data['param_author']] as $item) // loop column 
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
     
                    if(count($this->column_search_laporan[$data['param_author']]) - 1 == $i) //last loop
                        $this->db->group_end(); //close bracket
                }
                $i++;
            }
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order_laporan[$data['param_author']][$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            foreach ($this->order_laporan as $key => $value) // loop column 
            {
                $this->db->order_by($key, $value);
            }    
        }
    }
 
    public function get_ringkasan_laporan_thl($data)
    {
        $this->get_ringkasan_laporan_thl_query($data);
        if (isset($_POST['length']))
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_laporan($data)
    {
        $this->get_ringkasan_laporan_thl_query($data);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all_laporan($data)
    {
        $skpd_bidang = $this->get_skpd_bidang($data);

        $this->db->from('v_summary_laporan_thl_new');

        date_default_timezone_set("Asia/Jakarta");
        $first_day_this_month = date('Y-m-01');
        $last_day_this_month  = date('Y-m-t');

        $this->db->where('tgl_laporan >=', $first_day_this_month);
        $this->db->where('tgl_laporan <=', $last_day_this_month);

        switch ($data['param_author']) {
            case 2:
                $this->db->where('skpd_id', $skpd_bidang['skpd_id']);
                break;
            case 3:
                $this->db->where('skpd_id', $skpd_bidang['skpd_id']);
                break;
            case 4:
                $this->db->where('bidang_skpd_id', $skpd_bidang['bidang_skpd_id']);
                break;
            case 5:
                $this->db->where('kode_spv_kasie', $data['param_kode']);
                break;
            case 6:
                $this->db->where('kode_thl', $data['param_kode']);
                break;
        }
        return $this->db->count_all_results();
    }
 
    public function get_rekap_jumlah_thl($data)
    {
        $stored_proc = "CALL get_beranda_rekap_jumlah_thl_new(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->result();
    }
}
