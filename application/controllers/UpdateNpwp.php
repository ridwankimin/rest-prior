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
        $npwp15 = $this->post('npwp15');
        $npwp22 = $this->post('npwp22');
        $email = $this->post('email');
        if($npwp15) {
            if($npwp22) {
                if($email) {
                    $getDataPJ = $this->pj->getDataPJBarantan($npwp15);
                    if ($getDataPJ) {
                        // generate akun pj barantin
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Pengguna jasa belum terdaftar'
                        ], RESTController::HTTP_NOT_FOUND);
                    }
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Email is required'
                    ], RESTController::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'NPWP 16 digit + NITKU is required'
                ], RESTController::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'NPWP 15 digit is required'
            ], RESTController::HTTP_BAD_REQUEST);
        }
    }
}