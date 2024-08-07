<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DocPriorModel extends CI_Model
{
    function getByRegPrior($id)
    {
        return $this->db->get_where('docprior', array('regid' => $id))->result_array();
    }

    function getDocPrior($id)
    {
        return $this->db->get_where('docprior', array('docnbr' => $id))->result_array();
    }

    function insertDocPrior($data)
    {
        $this->db->insert('docprior', $data);
        return $this->db->affected_rows();
    }

    function updateDocPrior($data, $id)
    {
        $this->db->where('docnbr', $id);
        $this->db->update('docprior', $data);
        return $this->db->affected_rows();
    }

    function deleteDocPrior($id)
    {
        $this->db->where('docnbr', $id);
        $this->db->delete('docprior');
        return $this->db->affected_rows();
    }
}
