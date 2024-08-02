<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarantinModel extends CI_Model
{
    function getBarantinSampel($data) {
        // $this->db->select('*');
        // $this->db->where($data);
        // return $this->db->get('permohonan_lab');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://ws.karantina.pertanian.go.id/api/index.php?a=ujilab',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'upt=03&kar=kt&d1=' . $data['dFrom'] . '&d2=' . $data['dTo'],
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}