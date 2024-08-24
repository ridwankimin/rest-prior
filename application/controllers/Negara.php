<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class Negara extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('NegaraModel', 'neg');
    }

    public function index_get()
    {
        $this->form_validation->set_data($this->get());
        $this->form_validation->set_rules('id', 'ID', 'required|max_length[45]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $get = $this->neg->getNegaraId($this->get('id'));
            if ($get) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Data found',
                    'data' => $get[0]
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
