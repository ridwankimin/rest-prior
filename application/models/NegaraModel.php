<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NegaraModel extends CI_Model
{
    function getNegaraId($id) {
        return $this->db->get_where('negara', array('kode_negara' => $id))->result_array();
    }
}