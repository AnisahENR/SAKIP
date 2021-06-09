<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_login extends CI_Model {
  
    public function __construct() {
        parent::__construct();
    }

    public function login($username)
    {
        $stored_proc = "CALL get_login(?)";
        $data = array('param_username' => $username);
        $result = $this->db->query($stored_proc, $data);
        return $result->row_array();
    }

    public function update_cookie($data, $id_user)
    {
        $this->db->where($this->pk, $id_user);
        $this->db->update($this->table, $data);
    }

    public function get_cookie($cookie)
    {
        $this->db->where('cookie', $cookie);
        return $this->db->get($this->table);
    }
}
