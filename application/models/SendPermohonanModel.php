<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SendPermohonanModel extends CI_Model
{
    function insert($data) {
        $dbssm = $this->load->database('ssm',TRUE);
        $dbssm->insert('tssm', $data);
        return $dbssm->affected_rows();
    }
}