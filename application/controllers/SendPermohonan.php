<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class SendPermohonan extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('SendPermohonanModel', 'permohonan');
    }

    public function index_post()
    {
        // Get JSON input
        $json_data = $this->input->raw_input_stream;
        $data = json_decode($json_data, true);

        // Set validation rules
        if (!isset($data['DOKUMEN'])) {
            $this->response([
                'status' => FALSE,
                'message' => 'DOKUMEN key is missing in the JSON data.'
            ], RESTController::HTTP_BAD_REQUEST);
            return;
        }

        $this->form_validation->set_data($data['DOKUMEN']);
        $this->form_validation->set_rules('IDPERMOHONAN', 'ID Permohonan', 'required|alpha_dash');
        $this->form_validation->set_rules('NOPERMOHONAN', 'No Permohonan', 'required|alpha_numeric');
        $this->form_validation->set_rules('WKPERMOHONAN', 'Waktu Permohonan', 'required');
        $this->form_validation->set_rules('JNSPERMOHONAN', 'Jenis Permohonan', 'required|numeric');
        $this->form_validation->set_rules('SERI', 'Seri', 'required|numeric');
        $this->form_validation->set_rules('STATUS', 'Status', 'required|alpha');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(['status' => FALSE, 'errors' => $errors], RESTController::HTTP_BAD_REQUEST);
            return;
        }

        // Validate HEADER.PERUSAHAAN
        if (isset($data['DOKUMEN']['HEADER']['PERUSAHAAN'])) {
            $this->form_validation->set_data($data['DOKUMEN']['HEADER']['PERUSAHAAN']);
            $this->form_validation->set_rules('ID', 'ID PERUSAHAAN', 'required|alpha_numeric');
            $this->form_validation->set_rules('NAMA', 'Nama PERUSAHAAN', 'required');
            $this->form_validation->set_rules('ALAMAT', 'Alamat PERUSAHAAN', 'required');
            $this->form_validation->set_rules('KOTA', 'Kota PERUSAHAAN', 'required');
            $this->form_validation->set_rules('PROVINSI', 'Provinsi PERUSAHAAN', 'required');
            $this->form_validation->set_rules('JENIS', 'Jenis PERUSAHAAN', 'required|alpha');
            $this->form_validation->set_rules('JNSAPI', 'Jenis API PERUSAHAAN', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $errors = $this->form_validation->error_array();
                $this->response(['status' => FALSE, 'errors' => $errors], RESTController::HTTP_BAD_REQUEST);
                return;
            }
        }

        // Validate HEADER.PERUSAHAAN.PJAWAB
        if (isset($data['DOKUMEN']['HEADER']['PERUSAHAAN']['PJAWAB'])) {
            $this->form_validation->set_data($data['DOKUMEN']['HEADER']['PERUSAHAAN']['PJAWAB']);
            $this->form_validation->set_rules('NAMA', 'Nama Penanggung Jawab', 'required');
            $this->form_validation->set_rules('JABATAN', 'Jabatan Penanggung Jawab', 'required');
            $this->form_validation->set_rules('ALAMAT', 'Alamat Penanggung Jawab', 'required');
            $this->form_validation->set_rules('KOTA', 'Kota Penanggung Jawab', 'required');
            $this->form_validation->set_rules('EMAIL', 'Email Penanggung Jawab', 'required|valid_email');

            if ($this->form_validation->run() == FALSE) {
                $errors = $this->form_validation->error_array();
                $this->response(['status' => FALSE, 'errors' => $errors], RESTController::HTTP_BAD_REQUEST);
                return;
            }
        }
        
        if($data['DOKUMEN']['JNSPERMOHONAN']=='03999') $kar='Hewan';
        if($data['DOKUMEN']['JNSPERMOHONAN']=='04999') $kar='Tumbuhan';
        if($data['DOKUMEN']['JNSPERMOHONAN']=='02999') $kar='Ikan';
        
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
        
        $result = $this->permohonan->insert($input);
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