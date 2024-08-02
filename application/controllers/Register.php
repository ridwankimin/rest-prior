<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class Register extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('RegisterModel', 'reg');
    }

    public function index_get($id = 0)
	{
        // ------- Main Logic part -------
        if(!empty($id)){
            $data = $this->Product_model->show($id);
        } else {
            $data = $this->Product_model->show();
        }
        $this->response($data, REST_Controller::HTTP_OK);
        // ------------- End -------------
	}

    public function index_post()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('company_name', 'No Permohonan', 'required|alpha_numeric');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'Status', 'required|alpha');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('companies/create');
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone')
            );
            $this->Company_model->insert_company($data);
            redirect('companies');
        }
        
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(['status' => FALSE, 'errors' => $errors], RESTController::HTTP_BAD_REQUEST);
            return;
        } else {
            
        }

        
        $input = array(
            'id' => $data['DOKUMEN']['IDPERMOHONAN'],
            'noAju' => $data['DOKUMEN']['NOPERMOHONAN'],
            'tglAju' => $data['DOKUMEN']['WKPERMOHONAN'],
            'jnsAju' => $data['DOKUMEN']['JNSPERMOHONAN'],
            'nmPerusahaan' => $data['DOKUMEN']['HEADER']['PERUSAHAAN']['NAMA'],
            'npwp' => $data['DOKUMEN']['HEADER']['PERUSAHAAN']['ID'],
            'ppjk' => $data['DOKUMEN']['HEADER']['PPJK']['NMPPJK'],
            'npwp_ppjk' => $data['DOKUMEN']['HEADER']['PPJK']['IDPPJK'],
            // 'noReg' => $data['DOKUMEN']['IDPERMOHONAN'],
            'jenis_karantina' => $kar,
            'upt' => $data['DOKUMEN']['HEADER']['GA']['KARANTINA']['UPT'],
            'modaAngkut' => $data['DOKUMEN']['HEADER']['ANGKUT']['MODA'],
            'namaAngkut' => $data['DOKUMEN']['HEADER']['ANGKUT']['NMANGKUT'],
            // 'gudang' => $data['DOKUMEN']['IDPERMOHONAN'],
            // 'kd_tpk' => $data['DOKUMEN']['IDPERMOHONAN'],
            'tglTiba' => $data['DOKUMEN']['HEADER']['ANGKUT']['TGTIBA'],
            'portAsal' => $data['DOKUMEN']['HEADER']['PELABUHAN']['ASAL'],
            'portTujuan' => $data['DOKUMEN']['HEADER']['PELABUHAN']['BONGKAR'],
            // 'tujuan' => $data['DOKUMEN']['IDPERMOHONAN'],
            // 'peruntukan' => $data['DOKUMEN']['IDPERMOHONAN'],
            'xml' => $json_data
        );
        
        $result = $this->reg->insert($input);
        var_dump($result);
        if($result>0) {
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