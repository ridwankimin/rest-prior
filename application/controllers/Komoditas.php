<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class Komoditas extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('KomoditasModel', 'kom');
    }

    public function index_get()
    {
        $kar = $this->get("kar");
        $id = $this->get("klasId");
        if($kar && $id) {
            if($kar == "kh") {
                $result = $this->kom->get_by_klas_id_kh($id);
            } else if($kar == "kt") {
                $id = substr($id, 0, 1);
                $result = $this->kom->get_by_klas_id_kt($id);
            } else if($kar == "ki") {
                $result = $this->kom->get_by_klas_id_ki($id);
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