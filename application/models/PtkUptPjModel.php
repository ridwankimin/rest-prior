<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PtkUptPjModel extends CI_Model
{
    function getUptPj($pjId) {
        $dbapps = $this->load->database('apps', TRUE);
        $dbapps->select('p.*, m.nama as nama_upt');
        $dbapps->join('master_upt as m', 'p.upt_id=m.id');
        $dbapps->where('pengguna_jasa_id', $pjId);
        $dbapps->order_by('created_at', 'desc');
        return $dbapps->get('pengguna_jasa_upt as p')->result_array();
    }
    
    function insertUptPj($data) {
        $dbapps = $this->load->database('apps', TRUE);
        $dbapps->insert('pengguna_jasa_upt', $data);
        return $dbapps->affected_rows();
    }
    
    function cekUptPj($uptId, $pjId) {
        $dbapps = $this->load->database('apps', TRUE);
        $data = array(
            'pengguna_jasa_id' => $pjId,
            'upt_id' => $uptId
        );
        return $dbapps->get_where('pengguna_jasa_upt', $data)->num_rows();
    }
    
    function deleteUptPj($id) {
        $dbapps = $this->load->database('apps', TRUE);
        $dbapps->delete('pengguna_jasa_upt', array('id' => $id));
        return $dbapps->affected_rows();
    }
}