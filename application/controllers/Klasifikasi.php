<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class Klasifikasi extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('KlasifikasiModel', 'klas');
    }

    public function index_get()
    {
        $kar = $this->get("kar");
        if($kar) {
            if($kar == "kh") {
                $result = $this->klas->get_all_kh();
            } else if($kar == "kt") {
                $result = $this->klas->get_all_kt();
            } else if($kar == "ki") {
                $result = $this->klas->get_all_ki();
            }
        } else {
            $this->response([
                'status' => TRUE,
                'message' => 'Wrong parameter'
            ], RESTController::HTTP_BAD_REQUEST);
        }

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