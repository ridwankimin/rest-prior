<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KontainerModel extends CI_Model
{
    function getKontainerPrior($id) {
        return $this->db->get_where('dtl_kontainer', array('id' => $id))->result_array();
    }
    
    function getKontainerDocPrior($id) {
        return $this->db->get_where('dtl_kontainer', array('docnbr' => $id))->result_array();
    }
    
    function insertKontainerPrior($data) {
        $this->db->insert('dtl_kontainer', $data);
        return $this->db->affected_rows();
    }
    
    function updateKontainerPrior($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('dtl_kontainer', $data);
        return $this->db->affected_rows();
    }
    
    function deleteKontainerPrior($id) {
        $this->db->where('id', $id);
        $this->db->delete('dtl_kontainer');
        return $this->db->affected_rows();
    }
}