<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class DocPrior extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('DocPriorModel', 'doc');
        $this->load->model('KomoditasModel', 'kom');
        $this->load->model('KontainerModel', 'cont');
        $this->load->model('CertPriorModel', 'cert');
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
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

    public function getByRegID_get()
    {
        $this->form_validation->set_data($this->get());
        $this->form_validation->set_rules('regid', 'ID', 'required|max_length[45]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $get = $this->doc->getByRegPrior($this->get('regid'));
            if ($get) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Data found',
                    'data' => $get
                ], RESTController::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Data Not found'
                ], RESTController::HTTP_NOT_FOUND);
            }
        }
    }

    public function getAll_get()
    {
        $this->form_validation->set_data($this->get());
        $this->form_validation->set_rules('docnbr', 'ID', 'required|max_length[45]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $get = $this->doc->getDocPrior($this->get('docnbr'));
            if ($get) {
                $get = $get[0];
                $komoditas = $this->kom->getKomoditasDocPrior($this->get('docnbr'));
                if(is_array($komoditas)) {
                    $get['listKomoditas'] = $komoditas;
                } else {
                    $get['listKomoditas'] = [];
                }
                
                $kontainer = $this->cont->getKontainerDocPrior($this->get('docnbr'));
                if(is_array($kontainer)) {
                    $get['listKontainer'] = $kontainer;
                } else {
                    $get['listKontainer'] = [];
                }
                
                $detilCert = $this->cert->getCertDocPrior($this->get('docnbr'));
                if(is_array($detilCert)) {
                    $get['listCert'] = $detilCert;
                } else {
                    $get['listCert'] = [];
                }

                $this->response([
                    'status' => TRUE,
                    'message' => 'Data found',
                    'data' => $get
                ], RESTController::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Data Not found'
                ], RESTController::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_get()
    {
        $this->form_validation->set_data($this->get());
        $this->form_validation->set_rules('docnbr', 'ID', 'required|max_length[45]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $get = $this->doc->getDocPrior($this->get('docnbr'));
            if ($get) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Data found',
                    'data' => $get
                ], RESTController::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Data Not found'
                ], RESTController::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_post()
    {
        $this->form_validation->set_data($this->post());
        // $this->form_validation->set_rules('docnbr', 'Doc Prior', 'required|max_length[45]');
        $this->form_validation->set_rules('karantina', 'Quarantiney', 'required|max_length[1]');
        $this->form_validation->set_rules('regid', 'Exporter ID', 'required|max_length[45]');
        $this->form_validation->set_rules('name', 'Exporter name', 'required|max_length[50]');
        $this->form_validation->set_rules('company', 'Company name', 'required|max_length[255]');
        $this->form_validation->set_rules('alamat', 'Address', 'required|max_length[500]');
        $this->form_validation->set_rules('email', 'unit', 'max_length[50]');
        $this->form_validation->set_rules('kdneg', 'Country', 'required|max_length[2]');
        $this->form_validation->set_rules('telp', 'Phone Number', 'max_length[50]');
        $this->form_validation->set_rules('name_imp', 'Importer name', 'required|max_length[50]');
        $this->form_validation->set_rules('company_imp', 'Importer company', 'required|max_length[255]');
        $this->form_validation->set_rules('alamat_imp', 'Importer address', 'required|max_length[500]');
        $this->form_validation->set_rules('email_imp', 'Importer email', 'required|max_length[50]');
        $this->form_validation->set_rules('telp_imp', 'Importer Phone Number', 'max_length[50]');
        $this->form_validation->set_rules('gmo', 'GMO', 'required|max_length[1]');
        $this->form_validation->set_rules('processing', 'Processing', 'required|max_length[20]');
        $this->form_validation->set_rules('processingLain', 'processingLain', 'max_length[100]');
        $this->form_validation->set_rules('jnsangkut', 'Angkutan', 'required|max_length[3]');
        $this->form_validation->set_rules('bulk', 'BULK', 'required|max_length[1]');
        $this->form_validation->set_rules('novoyage', 'No Voyage', 'max_length[15]');
        $this->form_validation->set_rules('port_asal', 'Origin Port', 'required|max_length[5]');
        $this->form_validation->set_rules('tgl_loading', 'Loading Date', 'required|max_length[20]');
        $this->form_validation->set_rules('port_tuju', 'Destination Port', 'required|max_length[5]');
        $this->form_validation->set_rules('tgl_tiba', 'Arrival Date', 'required|max_length[20]');
        $this->form_validation->set_rules('tujuan', 'Purpose', 'required|max_length[25]');
        $this->form_validation->set_rules('ket_tujuan', 'Other Purpose', 'max_length[255]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $docnbr = $this->post('karantina') . "." . date('Ymd') ."." . $this->generateRandomString(6);
            // $id = $this->guidv4();
            // var_dump($docnbr);
            $insert = array(
                'docnbr' => $docnbr,
                'regid' => $this->post('regid'),
                'tgl_doc' => $this->post('tgl_doc'),
                'karantina' => $this->post('karantina'),
                'name' => $this->post('name'),
                'company' => $this->post('company'),
                'alamat' => $this->post('alamat'),
                'email' => $this->post('email'),
                'kdneg' => $this->post('kdneg'),
                'telp' => $this->post('telp'),
                'name_imp' => $this->post('name_imp'),
                'company_imp' => $this->post('company_imp'),
                'alamat_imp' => $this->post('alamat_imp'),
                'email_imp' => $this->post('email_imp'),
                'telp_imp' => $this->post('telp_imp'),
                'gmo' => $this->post('gmo'),
                'processing' => $this->post('processing'),
                'processingLain' => $this->post('processingLain'),
                'jnsangkut' => $this->post('jnsangkut'),
                'bulk' => $this->post('bulk'),
                'novoyage' => $this->post('novoyage'),
                'port_asal' => $this->post('port_asal'),
                'tgl_loading' => $this->post('tgl_loading'),
                'kota_tuju' => $this->post('kota_tuju'),
                'port_tuju' => $this->post('port_tuju'),
                'tgl_tiba' => $this->post('tgl_tiba'),
                'tujuan' => $this->post('tujuan'),
                'ket_tujuan' => $this->post('ket_tujuan'),
                'place_issued' => $this->post('place_issued'),
                'keterangan' => $this->post('keterangan'),
                'stat' => $this->post('stat'),
            );
            if($this->post('stat') == 1) {
                $insert['last_send'] = date('Y-m-d H:i:s');
            } else {
                $insert['last_update'] = date('Y-m-d H:i:s');
            }
            
            $datainsert = $this->doc->insertDocPrior($insert);
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
        $this->form_validation->set_rules('docnbr', 'Doc Prior', 'required|max_length[45]');
        $this->form_validation->set_rules('karantina', 'Quarantiney', 'required|max_length[1]');
        $this->form_validation->set_rules('regid', 'Exporter ID', 'required|max_length[45]');
        $this->form_validation->set_rules('name', 'Exporter name', 'required|max_length[50]');
        $this->form_validation->set_rules('company', 'Company name', 'required|max_length[255]');
        $this->form_validation->set_rules('alamat', 'Address', 'required|max_length[500]');
        $this->form_validation->set_rules('email', 'unit', 'max_length[50]');
        $this->form_validation->set_rules('kdneg', 'Country', 'required|max_length[2]');
        $this->form_validation->set_rules('telp', 'Phone Number', 'max_length[50]');
        $this->form_validation->set_rules('name_imp', 'Importer name', 'required|max_length[50]');
        $this->form_validation->set_rules('company_imp', 'Importer company', 'required|max_length[255]');
        $this->form_validation->set_rules('alamat_imp', 'Importer address', 'required|max_length[500]');
        $this->form_validation->set_rules('email_imp', 'Importer email', 'required|max_length[50]');
        $this->form_validation->set_rules('telp_imp', 'Importer Phone Number', 'max_length[50]');
        $this->form_validation->set_rules('gmo', 'GMO', 'required|max_length[1]');
        $this->form_validation->set_rules('processing', 'Processing', 'required|max_length[20]');
        $this->form_validation->set_rules('processingLain', 'processingLain', 'max_length[100]');
        $this->form_validation->set_rules('jnsangkut', 'Angkutan', 'required|max_length[3]');
        $this->form_validation->set_rules('bulk', 'BULK', 'required|max_length[1]');
        $this->form_validation->set_rules('novoyage', 'No Voyage', 'max_length[15]');
        $this->form_validation->set_rules('port_asal', 'Origin Port', 'required|max_length[5]');
        $this->form_validation->set_rules('tgl_loading', 'Loading Date', 'required|max_length[20]');
        $this->form_validation->set_rules('port_tuju', 'Destination Port', 'required|max_length[5]');
        $this->form_validation->set_rules('tgl_tiba', 'Arrival Date', 'required|max_length[20]');
        $this->form_validation->set_rules('tujuan', 'Purpose', 'required|max_length[25]');
        $this->form_validation->set_rules('ket_tujuan', 'Other Purpose', 'max_length[255]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            // $docnbr = date('Y') ."/" ;
            $insert = array(
                'regid' => $this->put('regid'),
                'tgl_doc' => date('Y-m-d H:i:s'),
                'karantina' => $this->put('karantina'),
                'name' => $this->put('name'),
                'company' => $this->put('company'),
                'alamat' => $this->put('alamat'),
                'email' => $this->put('email'),
                'kdneg' => $this->put('kdneg'),
                'telp' => $this->put('telp'),
                'name_imp' => $this->put('name_imp'),
                'company_imp' => $this->put('company_imp'),
                'alamat_imp' => $this->put('alamat_imp'),
                'email_imp' => $this->put('email_imp'),
                'telp_imp' => $this->put('telp_imp'),
                'gmo' => $this->put('gmo'),
                'processing' => $this->put('processing'),
                'processingLain' => $this->put('processingLain'),
                'jnsangkut' => $this->put('jnsangkut'),
                'bulk' => $this->put('bulk'),
                'novoyage' => $this->put('novoyage'),
                'port_asal' => $this->put('port_asal'),
                'tgl_loading' => $this->put('tgl_loading'),
                'kota_tuju' => $this->put('kota_tuju'),
                'port_tuju' => $this->put('port_tuju'),
                'tgl_tiba' => $this->put('tgl_tiba'),
                'tujuan' => $this->put('tujuan'),
                'ket_tujuan' => $this->put('ket_tujuan'),
                'keterangan' => $this->put('keterangan'),
                'stat' => $this->put('stat'),
            );

            $datainsert = $this->doc->updateDocPrior($insert, $this->put('docnbr'));
            if ($datainsert > 0) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Update data success',
                    'data' => $this->put()
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
        $this->form_validation->set_rules('docnbr', 'ID', 'required|max_length[45]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $datainsert = $this->doc->deleteDocPrior($this->delete('docnbr'));
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
