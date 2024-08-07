<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class CertPrior extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('CertPriorModel', 'cert');
    }

    function guidv4($data = null)
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function getByDok_get()
    {
        $this->form_validation->set_data($this->get());
        $this->form_validation->set_rules('docnbr', 'Docnbr', 'required|max_length[45]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $get = $this->cert->getCertDocPrior($this->get('docnbr'));
            if ($get) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Data found',
                    'data' => $get
                ], RESTController::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Data not found'
                ], RESTController::HTTP_NOT_FOUND);
            }
        }
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
            $get = $this->cert->getCertPrior($this->get('id'));
            if ($get) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Data found',
                    'data' => $get
                ], RESTController::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Data not found'
                ], RESTController::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('docnbr', 'Doc Prior', 'required|max_length[45]');
        $this->form_validation->set_rules('nomor', 'Cert Number', 'required|max_length[40]');
        $this->form_validation->set_rules('issued_place', 'Issued Place', 'max_length[40]');
        $this->form_validation->set_rules('issued_date', 'Issued Place', 'max_length[20]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            // $docnbr = date('Y') ."/" ;
            $id = $this->guidv4();
            $insert = array(
                'id' => $id,
                'docnbr' => $this->post('docnbr'),
                'nomor' => $this->post('nomor'),
                'issued_place' => $this->post('issued_place'),
                'issued_date' => $this->post('issued_date'),
            );

            $datainsert = $this->cert->insertCertPrior($insert);
            if ($datainsert > 0) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Insert data success',
                    'data' => $insert
                ], RESTController::HTTP_CREATED);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Insert data failed'
                ], RESTController::HTTP_BAD_REQUEST);
            }
        }
    }

    public function index_put()
    {
        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('id', 'ID', 'required|max_length[45]');
        $this->form_validation->set_rules('docnbr', 'Doc Prior', 'required|max_length[45]');
        $this->form_validation->set_rules('nomor', 'Cert Number', 'required|max_length[40]');
        $this->form_validation->set_rules('issued_place', 'Issued Place', 'max_length[40]');
        $this->form_validation->set_rules('issued_date', 'Issued Place', 'max_length[20]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            // $docnbr = date('Y') ."/" ;
            $insert = array(
                'docnbr' => $this->put('docnbr'),
                'nomor' => $this->put('nomor'),
                'issued_place' => $this->put('issued_place'),
                'issued_date' => $this->put('issued_date'),
            );

            $dataupdate = $this->cert->updateCertPrior($insert, $this->put('id'));
            if ($dataupdate > 0) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Update data success',
                    'data' => $insert
                ], RESTController::HTTP_CREATED);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Update data failed'
                ], RESTController::HTTP_NOT_MODIFIED);
            }
        }
    }

    public function index_delete()
    {
        $this->form_validation->set_data($this->delete());
        $this->form_validation->set_rules('id', 'ID', 'required|max_length[45]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $datainsert = $this->cert->deleteCertPrior($this->delete('id'));
            if ($datainsert > 0) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Delete data success',
                ], RESTController::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Delete data failed'
                ], RESTController::HTTP_BAD_REQUEST);
            }
        }
    }
}
