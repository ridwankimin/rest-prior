<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegLabModel extends CI_Model
{
   function getByNegara($neg, $kar) {
        return $this->db->get_where('reg_lab', array('negara' => $neg, 'karantina' => $kar))->result_array();
   }
}