<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegistrasiModel extends CI_Model
{
    function cekemail($email) {
        return $this->db->get_where('user', array('email' => $email))->result_array();
    }

    function insertUser($data) {
        $this->db->insert('user', $data);
        return $this->db->affected_rows();
    }

    function cekEmailCode($cek) {
        $this->db->where($cek);
        return $this->db->get('user')->result_array();
    }
    
    function updateUser($update, $validasi) {
        $this->db->set($update);
        $this->db->where($validasi);
        $this->db->update('user');
        return $this->db->affected_rows();
    }
}