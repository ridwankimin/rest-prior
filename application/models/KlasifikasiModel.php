<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KlasifikasiModel extends CI_Model
{
    function get_all_kh() {
        $this->db->select('id,deskripsi');
        return $this->db->get('klasifikasi_hewan')->result_array();
    }
    
    function get_all_kt() {
        $this->db->select('id,deskripsi');
        return $this->db->get('klasifikasi_tumbuhan')->result_array();
    }
    
    function get_all_ki() {
        $this->db->select('id,deskripsi');
        return $this->db->get('klasifikasi_ikan')->result_array();
    }
}