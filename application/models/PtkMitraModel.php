<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PtkMitraModel extends CI_Model
{
    function getMitraPj($pj, $id) {
        $dbapps = $this->load->database('apps', TRUE);
        $dbapps->order_by('created_at', 'desc');
        if($id) {
            return $dbapps->get_where('pengguna_jasa_mitra', array('id' => $id, 'pengguna_jasa_id' => $pj))->result_array();
        } else {
            return $dbapps->get_where('pengguna_jasa_mitra', array('pengguna_jasa_id' => $pj))->result_array();
        }
    }

    function postMitraPj($data) {
        $dbapps = $this->load->database('apps', TRUE);
        $dbapps->insert('pengguna_jasa_mitra', $data);
        return $dbapps->affected_rows();
    }
    
    function putMitraPj($data, $id) {
        $dbapps = $this->load->database('apps', TRUE);
        $dbapps->where('id', $id);
        $dbapps->update('pengguna_jasa_mitra', $data);
        return $dbapps->affected_rows();
    }

    function deleteMitraPj($id) {
        $dbapps = $this->load->database('apps', TRUE);
        $dbapps->delete('pengguna_jasa_mitra', array('id' => $id));
        return $dbapps->affected_rows();
    }
}