<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrintPdf extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('DocPriorModel', 'doc');
        $this->load->model('KomoditasModel', 'kom');
        $this->load->model('KontainerModel', 'cont');
        $this->load->model('CertPriorModel', 'cert');
    }

    public function doc($id = null)
    {
        $id = base64_decode($id);
        // $id = '5835eadc-c4e4-4118-990d-ea9accde8c43';
        $get = $this->doc->getDocPrior($id);
        if ($get) {
            $get = $get[0];
            $komoditas = $this->kom->getKomoditasDocPrior($id);
            $kontainer = $this->cont->getKontainerDocPrior($id);
            $detilCert = $this->cert->getCertDocPrior($id);
        }
        $this->load->library('mypdf');
        $this->load->library('ciqrcode');
        
        $string = base_url() . 'print/' . base64_encode($id);
        ob_start();
        $params['data'] = $string;
        $this->ciqrcode->generate($params);
        $qrcode = ob_get_clean();

        $check = base_url() . 'img/check.jpg';
        $uncheck = base_url() . 'img/uncheck.jpg';
        $check = file_get_contents($check);
        $uncheck = file_get_contents($uncheck);

        $data = array(
            'data' => $get,
            'komoditas' => $komoditas,
            'kontainer' => $kontainer,
            'detilcert' => $detilCert,
            'check' => base64_encode($check),
            'uncheck' => base64_encode($uncheck),
            'qrcode' => base64_encode($qrcode)
        );

        // $this->load->view('printPrior.php', $data);
        $this->mypdf->generate('printPrior', $data);
        // if ($sts_print == "view") {
        // } elseif ($sts_print == "base") {
        //     return base64_encode($this->mypdf->getPdf('printk91', $data));
        // }
    }
}