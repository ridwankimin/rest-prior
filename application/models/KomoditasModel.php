<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KomoditasModel extends CI_Model
{
    function get_all_kh() {
        return $this->db->get('komoditas_hewan')->result_array();
    }
    
    function get_all_kt() {
        return $this->db->get('komoditas_tumbuhan')->result_array();
    }
    
    function get_all_ki() {
        return $this->db->get('komoditas_ikan')->result_array();
    }
    
    function get_by_klas_id_kh($id) {
        $this->db->select('id,nama,nama_en,nama_latin');
        return $this->db->get_where('komoditas_hewan', array('klas_id' => $id))->result_array();
    }
    
    function get_by_klas_id_kt($id) {
        $this->db->select('id,nama,nama_en,nama_latin');
        return $this->db->get_where('komoditas_tumbuhan', array('kode_golongan' => $id))->result_array();
    }
    
    function get_by_klas_id_ki($id) {
        $this->db->select('id,nama,nama_en,nama_latin');
        // return $this->db->get_where('komoditas_ikan', array('klas_id' => $id))->result_array();
        return $this->db->get_where('komoditas_ikan')->result_array();
    }
}