<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SendStatusModel extends CI_Model
{
    function insert($data)
    {
        $dbssm = $this->load->database('ssm', TRUE);
        $dbssm->insert('insw_respon', $data);
        return $dbssm->affected_rows();
    }
}
