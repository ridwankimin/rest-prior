<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Confirm extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('RegistrasiModel', 'regis');
    }

    public function cek($data = null)
    {
        if($data) {
            $data = base64_decode($data);
            $pisah = explode("_", $data);
            $email = $pisah[0];
            $code = $pisah[1];
            $validasi = array(
                'email' => $email,
                'verifcode' => $code
            );
            $cekaktifasi = $this->regis->cekEmailCode($validasi);
            if($cekaktifasi) {
                $aktif = array('status' => 1);
                $update = $this->regis->updateUser($aktif, $validasi);
                if($update > 0) {
                    redirect('https://notice.karantinaindonesia.go.id/#/confirmation/' . $code);
                } else {
                    redirect('https://notice.karantinaindonesia.go.id/#/confirmation/402');
                }
            } else {
                redirect('https://notice.karantinaindonesia.go.id/#/confirmation/404');
            }
        }
    }
}
