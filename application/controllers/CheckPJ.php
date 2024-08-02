<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class CheckPJ extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('CheckPJModel', 'pj');
    }

    public function index_post()
    {
        // Ambil parameter dari URL
        $idpj = $this->post('idpj');
        $idppjk = $this->post('idppjk');
        $kdupt = $this->post('kdupt');
        
        // Periksa apakah parameter sudah ada
        if ($idpj === NULL || $kdupt === NULL ) {
            $this->response([
                'status' => FALSE,
                'message' => 'Parameters missing'
            ], RESTController::HTTP_BAD_REQUEST);

        } else {
            // Panggil model untuk mengambil data berdasarkan parameter
            $result = $this->pj->getDataNew($idpj, $idppjk, $kdupt);
            // var_dump($result);
            if ($result) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Data found',
                    'data' => $result
                ], RESTController::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Data not found'
                ], RESTController::HTTP_NOT_FOUND);
            }
        }
    }
}