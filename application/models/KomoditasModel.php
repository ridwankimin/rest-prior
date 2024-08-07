<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KomoditasModel extends CI_Model
{
    function getKomoditasPrior($id) {
        return $this->db->get_where('dtl_komoditi', array('id' => $id))->result_array();
    }
    
    function getKomoditasDocPrior($id) {
        return $this->db->get_where('dtl_komoditi', array('docnbr' => $id))->result_array();
    }
    
    function getMasterKomoditasReg($id) {
        $this->db->like('recog_country', $id);
        $this->db->or_like('reg_lab', $id);
        return $this->db->get('komoditas')->result_array();
    }
    
    function insertKomoditasPrior($data) {
        $this->db->insert('dtl_komoditi', $data);
        return $this->db->affected_rows();
    }
    
    function updateKomoditasPrior($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('dtl_komoditi', $data);
        return $this->db->affected_rows();
    }
    
    function deleteKomoditasPrior($id) {
        $this->db->where('id', $id);
        $this->db->delete('dtl_komoditi');
        return $this->db->affected_rows();
    }
}