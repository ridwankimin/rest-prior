<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class RegLab extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('RegLabModel', 'lab');
    }

    public function index_get() {
        $this->form_validation->set_data($this->get());
        $this->form_validation->set_rules('kdneg', 'Country Code', 'required|max_length[2]');
        $this->form_validation->set_rules('kar', 'Quarantine Type', 'required|max_length[2]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $get = $this->lab->getByNegara($this->get('kdneg'), $this->get('kar'));
            if ($get) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Data found',
                    'data' => $get
                ], RESTController::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' =>'Data not found'
                ], RESTController::HTTP_NOT_FOUND);
            }
        }
    }
}