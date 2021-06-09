<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_laporan_bulanan extends CI_Model {
  
    public function __construct() {
        parent::__construct();
    }

    public function get_thl_list($data)
    {
        $stored_proc = "CALL get_thl_list(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        $result = $result->result();

        $data   = array();

        foreach ($result as $row) {
            if(!array_key_exists($row->skpd_id, $data))
            {
                $data[$row->skpd_id]['nama_skpd']   = $row->skpd;
                $data[$row->skpd_id]['skpd_id']     = $row->skpd_id;
                $data[$row->skpd_id]['bidang']      = array();
            }

            if(!array_key_exists($row->bidang_skpd_id, $data[$row->skpd_id]['bidang']))
            {
                $data[$row->skpd_id]['bidang'][$row->bidang_skpd_id]['nama_bidang_skpd']    = $row->bidang_skpd;
                $data[$row->skpd_id]['bidang'][$row->bidang_skpd_id]['bidang_skpd_id']      = $row->bidang_skpd_id;
                $data[$row->skpd_id]['bidang'][$row->bidang_skpd_id]['thl']                 = array();
            }

            $data[$row->skpd_id]['bidang'][$row->bidang_skpd_id]['thl'][] = array(  'kode_thl'  => $row->kode_thl,
                                                                                    'nama_thl'  => $row->nama_thl);
        }

        return $data;
    }

    public function get_spv_list($data)
    {
        $stored_proc = "CALL get_spv_list(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        $result = $result->result();

        $data   = array();

        foreach ($result as $row) {
            if(!array_key_exists($row->skpd_id, $data))
            {
                $data[$row->skpd_id]['nama_skpd']   = $row->skpd;
                $data[$row->skpd_id]['skpd_id']     = $row->skpd_id;
                $data[$row->skpd_id]['bidang']      = array();
            }

            if(!array_key_exists($row->bidang_skpd_id, $data[$row->skpd_id]['bidang']))
            {
                $data[$row->skpd_id]['bidang'][$row->bidang_skpd_id]['nama_bidang_skpd']    = $row->bidang_skpd;
                $data[$row->skpd_id]['bidang'][$row->bidang_skpd_id]['bidang_skpd_id']      = $row->bidang_skpd_id;
                $data[$row->skpd_id]['bidang'][$row->bidang_skpd_id]['spv']                 = array();
            }

            $data[$row->skpd_id]['bidang'][$row->bidang_skpd_id]['spv'][$row->author_spv][] = array(  'kode_spv'  => $row->kode_spv, 'nama_spv'  => $row->nama_spv, 'jabatan' => $row->jabatan);
        }

        return $data;
    }

    public function get_detail_thl($data)
    {
        $stored_proc = "CALL get_detail_thl(?)";
        $result = $this->db->query($stored_proc, $data);
        return $result->row();
    }

    public function get_mengetahui($data)
    {
        $stored_proc = "CALL get_mengetahui(?, ?)";
        $result = $this->db->query($stored_proc, $data);
        $result = $result->result();

        $data = array();

        foreach ($result as $row) {
            if(!array_key_exists($row->kode_spv, $data))
            {
                $data[$row->kode_spv]['nama']       = $row->nama_spv;
                $data[$row->kode_spv]['nip']        = $row->nip_spv;
                $data[$row->kode_spv]['jabatan']    = $row->jabatan_spv;
                $data[$row->kode_spv]['skpd']       = $row->skpd_spv;
            }
        }

        return $data;
    }

    public function get_laporan_aktivitas_thl($data)
    {
        $stored_proc = "CALL get_aktivitas_bulanan_thl(?, ?, ?)";
        $result = $this->db->query($stored_proc, $data);
        $result = $result->result();

        $data   = array();

        $dict   = array();
        $i      = 0;
        foreach ($result as $row) {
            if(!array_key_exists($row->laporan_id, $dict))
            {
                $dict[$row->laporan_id] = $i;
                $i++;

                $data[$dict[$row->laporan_id]]['tanggal_laporan']  = $row->tanggal_laporan;
                $data[$dict[$row->laporan_id]]['aktivitas']        = array();
            }

            $data[$dict[$row->laporan_id]]['aktivitas'][] = array(  'aktivitas'         => $row->aktivitas,
                                                                    'status_aktivitas'  => $row->status_aktivitas,
                                                                    'kode_kabid'        => $row->kode_kabid,
                                                                    'order'             => $row->order);
        }

        return $data;
    }

    public function get_laporan_target_thl($data)
    {
        $stored_proc = "CALL get_target_bulanan_thl(?, ?, ?)";
        $result = $this->db->query($stored_proc, $data);
        $result = $result->result();

        $data = array();

        $dict   = array();
        $i      = 0;
        foreach ($result as $row) {
            if(!array_key_exists($row->target_id, $dict))
            {
                $dict[$row->target_id] = $i;
                $i++;

                $data[$dict[$row->target_id]]['tanggal_target']  = $row->tanggal_target;
                $data[$dict[$row->target_id]]['target']          = array();
                $data[$dict[$row->target_id]]['status_target']   = $row->status_target;
                $data[$dict[$row->target_id]]['kode_kabid']      = $row->kode_kabid;
            }

            $data[$dict[$row->target_id]]['target'][] = $row->target;
        }

        return $data;
    }
}
