<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenerimaanModel extends CI_Model
{
    function getDataPenerimaan($uptKode, $satpelKode) {
        if($uptKode) {
            $data = array(
                'status' => 'menunggu',
                'deletedAt' => null,
                'uptKode' => $uptKode
            );
            if($satpelKode) {
                $data['satpelKode'] = $satpelKode;
            }

            return $this->db->get_where('penerimaan', $data)->result_array();
        } else {
            return $this->db->get('penerimaan')->result_array();
        }
    }
}