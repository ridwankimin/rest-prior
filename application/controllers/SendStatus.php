<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class SendStatus extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('SendStatusModel', 'status');
    }

    public function index_post()
    {
        // Get JSON input
        $json_data = $this->input->raw_input_stream;
        $data = json_decode($json_data, true);

        // Set validation rules
        if (!isset($data['SENDSTATUS'])) {
            $this->response([
                'status' => FALSE,
                'message' => 'Invalid JSON data.'
            ], RESTController::HTTP_BAD_REQUEST);
            return;
        }

        $this->form_validation->set_data($data['SENDSTATUS']);
        $this->form_validation->set_rules('KODE_RESPONSE', 'Kode Response', 'required|numeric');
        $this->form_validation->set_rules('NO_AJU', 'No Aju', 'required|alpha_dash');
        $this->form_validation->set_rules('TGL_AJU', 'Tanggal Aju', 'required');
        $this->form_validation->set_rules('NPWP', 'NPWP', 'required|numeric|exact_length[15]');
        $this->form_validation->set_rules('KODE_KANTOR', 'Kode Kantor', 'required|numeric|exact_length[4]');
        $this->form_validation->set_rules('INSTANSI', 'Instansi', 'required|numeric|exact_length[2]');
        $this->form_validation->set_rules('JML_CONTAINER', 'Jumlah Container', 'required|numeric');
        $this->form_validation->set_rules('JML_CONTAINER_PERIKSA', 'Jumlah Container Periksa', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(['status' => FALSE, 'errors' => $errors], RESTController::HTTP_BAD_REQUEST);
            return;
        }

        // Validate TRANSPORT
        if (isset($data['SENDSTATUS']['TRANSPORT']['LIST'])) {
            $this->form_validation->set_data($data['SENDSTATUS']['TRANSPORT']['LIST']);
            $this->form_validation->set_rules('MODA', 'Moda', 'required|numeric');
            $this->form_validation->set_rules('NOMOR', 'Nomor', 'required');
            $this->form_validation->set_rules('KODE_TERMINAL', 'Kode Terminal', 'required');
            $this->form_validation->set_rules('NAMA', 'Nama', 'required');
            $this->form_validation->set_rules('TGL_TIBA', 'Tanggal Tiba', 'required');

            if ($this->form_validation->run() == FALSE) {
                $errors = $this->form_validation->error_array();
                $this->response(['status' => FALSE, 'errors' => $errors], RESTController::HTTP_BAD_REQUEST);
                return;
            }
        }

        // Validate TRANSPORT.CONTAINER
        if (count($data['SENDSTATUS']['TRANSPORT']['LIST']['CONTLIST']['CONTAINER']) > 0) {
            foreach ($data['SENDSTATUS']['TRANSPORT']['LIST']['CONTLIST']['CONTAINER'] as $key => $container) {
                $this->form_validation->set_data($container);
                $this->form_validation->set_rules('NOCONT', 'No Container', 'required');
                // $this->form_validation->set_rules('NOSEAL', 'No Seal', 'required');
                $this->form_validation->set_rules('TPCONT', 'Tipe Container', 'required');
                $this->form_validation->set_rules('UKCONT', 'Ukuran Container', 'required|numeric');

                if ($this->form_validation->run() == FALSE) {
                    $errors = $this->form_validation->error_array();
                    $this->response(['status' => FALSE, 'errors' => $errors], RESTController::HTTP_BAD_REQUEST);
                    return;
                }
            }
        }

        // Validate DETAILDOC
        if (isset($data['SENDSTATUS']['DETAILDOC']['LOOP'][0])) {
            $this->form_validation->set_data($data['SENDSTATUS']['DETAILDOC']['LOOP'][0]);
            $this->form_validation->set_rules('NO_DOC', 'No Dokumen', 'required');
            $this->form_validation->set_rules('TGL_DOC', 'Tanggal Dokumen', 'required');
            $this->form_validation->set_rules('JNS_DOC', 'Jenis Dokumen', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $errors = $this->form_validation->error_array();
                $this->response(['status' => FALSE, 'errors' => $errors], RESTController::HTTP_BAD_REQUEST);
                return;
            }
        }

        $input = array(
            'id_ssm' => $data['SENDSTATUS']['ID_PERMOHONAN'],
            'no_aju' => $data['SENDSTATUS']['NO_AJU'],
            'kode_respon' => $data['SENDSTATUS']['KODE_RESPONSE'],
            'payload' => $json_data,
            'created_at' => date("Y-m-d H:i:s")
        );

        $result = $this->status->insert($input);
        if ($result > 0) {
            $this->response([
                'status' => TRUE,
                'message' => 'Sukses insert data'
            ], RESTController::HTTP_CREATED);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Gagal Insert Data'
            ], RESTController::HTTP_BAD_REQUEST);
        }
    }
}
