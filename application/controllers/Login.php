<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class Login extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('RegistrasiModel', 'regis');
    }

    public function reset_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('email', 'Email', 'required|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('newPassword', 'New Password', 'required|min_length[8]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $cekemail = $this->regis->cekemail($this->post('email'));
            if ($cekemail) {
                if ($cekemail[0]['status'] == 1) {
                    if (password_verify(('Ndr00' . $this->post('password') . 'MukeG!l3'), $cekemail[0]['pass'])) {
                        $update = array('pass' => password_hash(('Ndr00' . $this->post('newPassword') . 'MukeG!l3'), PASSWORD_DEFAULT));
                        $where = array('regid' => $cekemail[0]['regid']);
                        $this->regis->updateUser($update, $where);

                        $this->response([
                            'status' => TRUE,
                            'message' => 'Reset password success',
                        ], RESTController::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Wrong password'
                        ], RESTController::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Your account is not active yet'
                    ], RESTController::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'User not found'
                ], RESTController::HTTP_BAD_REQUEST);
            }
        }
    }

    public function index_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('email', 'Email', 'required|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $cekemail = $this->regis->cekemail($this->post('email'));
            if ($cekemail) {
                if ($cekemail[0]['status'] == 1) {
                    if (password_verify(('Ndr00' . $this->post('password') . 'MukeG!l3'), $cekemail[0]['pass'])) {
                        //update last login
                        $update = array('lastlogin' => $this->post('time'));
                        $where = array('regid' => $cekemail[0]['regid']);
                        $this->regis->updateUser($update, $where);

                        unset($cekemail[0]["pass"]);
                        unset($cekemail[0]["level"]);
                        unset($cekemail[0]["status"]);
                        unset($cekemail[0]["ip"]);
                        unset($cekemail[0]["verifcode"]);

                        $date = strtotime($this->post('time'));
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Login success',
                            'expired' => date("Y-m-d H:i:s", strtotime('+2 hours', $date)),
                            'data' => $cekemail[0]
                        ], RESTController::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Wrong password'
                        ], RESTController::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Your account is not active yet'
                    ], RESTController::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'User not found'
                ], RESTController::HTTP_BAD_REQUEST);
            }
        }
    }
}