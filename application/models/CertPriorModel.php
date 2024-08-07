<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CertPriorModel extends CI_Model
{
    function getCertPrior($id) {
        return $this->db->get_where('dtl_cert', array('id' => $id))->result_array();
    }
    
    function getCertDocPrior($id) {
        return $this->db->get_where('dtl_cert', array('docnbr' => $id))->result_array();
    }
    
    function insertCertPrior($data) {
        $this->db->insert('dtl_cert', $data);
        return $this->db->affected_rows();
    }
    
    function updateCertPrior($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('dtl_cert', $data);
        return $this->db->affected_rows();
    }
    
    function deleteCertPrior($id) {
        $this->db->where('id', $id);
        $this->db->delete('dtl_cert');
        return $this->db->affected_rows();
    }
}