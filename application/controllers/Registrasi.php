<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class Registrasi extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('RegistrasiModel', 'regis');
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

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function index_get()
    {
        $this->form_validation->set_data($this->get());
        $this->form_validation->set_rules('email', 'Email', 'required|max_length[50]');
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $cekemail = $this->regis->cekemail($this->get('email'));
            if ($cekemail) {
                $newpass = $this->generateRandomString(10);
                $verif = $this->guidv4();
                $set = array(
                    'pass' => password_hash($newpass, PASSWORD_DEFAULT),
                    'verifcode' => $verif,
                    'status' => 0
                );
                $updateUser = $this->regis->updateUser($set, array('regid' => $cekemail[0]['regid']));
                if($updateUser > 0) {
            $this->load->library('phpmailer_library');

            $isi = '<p>Your new password is: <strong>' . $newpass . '</strong></p></br>

To activate your new password, please click on this link: </br></br>
http://localhost/rest-prior/confirm/cek/' . (base64_encode($this->get('email') . "_" . $verif)) . '
</br></br></br>
<strong>Perhatian</strong>:
<p>E-mail ini dan dokumen lampirannya ditujukan untuk digunakan oleh penerima e-mail. Informasi yang terdapat dalam e-mail ini dapat bersifat RAHASIA, bila Anda bukan orang yang tepat untuk menerima e-mail ini segera memberitahukan ke pengirimnya dan menghapus e-mail ini dari komputer Anda. Anda dilarang memperbanyak, menyebarkan dan menyalin informasi kepada pihak lain. Isi e-mail ini mungkin saja berisi pandangan dan pendapat pribadi pengirimnya dan tidak mewakili pandangan dan/atau pendapat Kementerian Pertanian, kecuali bila dinyatakan dengan jelas demikian. Walaupun e-mail ini sudah diperiksa terhadap virus komputer, Kementerian Pertanian tidak bertanggungjawab atas kerusakan yang diakibatkan oleh e-mail ini jika terkena virus atau gangguan komunikasi.</p>
</br>
<strong>Disclaimer</strong>:
<p>The contents of this e-mail and attachments are confidential and subject to legal privilege. If you are not the intended recipient, you are strictly prohibited and may be unlawful to use, copy, store, distribute, disclose or communicate any part of it to others and you are obliged to return it immediately to sender notify us and delete the e-mail and any attachments from your system. Ministry Of Agriculture accepts no liability for the content of this email, or for the consequences of any actions taken on the basis of the information provided, unless that information is subsequently confirmed in writing. Any views or opinions presented in this email are solely those of the author and do not necessarily represent those of Ministry Of Agriculture. The recipient should check this email and any attachments for the presence of viruses. Ministry Of Agriculture accepts no liability for any damage caused by any virus transmitted by this email.</p>';

            // PHPMailer object
            $mail = $this->phpmailer_library->sendMail($this->get('email'), 'Prior Notice Reset Password Confirmation', $isi);
            if ($mail['status']) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Please check your email to activated your account'
                ], RESTController::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Send email failed - ' . $mail['message']
                ], RESTController::HTTP_BAD_REQUEST);
            }
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Password reset failure'
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

    public function index_post() {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('firstname', 'First Name', 'required|max_length[100]');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required|max_length[100]');
        $this->form_validation->set_rules('company', 'Company Name', 'required|max_length[100]');
        $this->form_validation->set_rules('country', 'Country', 'required|max_length[2]');
        $this->form_validation->set_rules('address', 'Address', 'required|max_length[255]');
        $this->form_validation->set_rules('kodeNegTelp', 'Kode Telp', 'required|max_length[5]');
        $this->form_validation->set_rules('nomorTelp', 'Phone Number', 'required|max_length[45]');
        $this->form_validation->set_rules('email', 'Email', 'required|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => validation_errors()
            ], RESTController::HTTP_BAD_REQUEST);
        } else {
            $cekemail = $this->regis->cekemail($this->post('email'));
            if($cekemail) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Your email has been registered'
                ], RESTController::HTTP_BAD_REQUEST);
            } else {
                $verif = $this->guidv4();
                $input = array(
                    'regid' => $this->guidv4(),
                    'email' => $this->post('email'),
                    'verifcode' => $verif,
                    'firstname' => $this->post('firstname'),
                    'lastname' => $this->post('lastname'),
                    'company' => $this->post('company'),
                    'address' => $this->post('address'),
                    'country' => $this->post('country'),
                    'pass' => password_hash($this->post('password'), PASSWORD_DEFAULT),
                    'phone' => $this->post('kodeNegTelp') . $this->post('nomorTelp'),
                    'regdate' => date('Y-m-d H:i:s'),
                    'status' => 0,
                    'latitude' => $this->post('latitude'),
                    'longitude' => $this->post('longitude'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                $insert = $this->regis->insertUser($input);
                if($insert > 0) {
                    $this->load->library('phpmailer_library');

                    $isi = '<p>To activate your account, please click on this link:</p></br></br>
                    
                    http://localhost/rest-prior/confirm/cek/' . (base64_encode($this->post('email') . "_" . $verif));
                    // PHPMailer object
                    $mail = $this->phpmailer_library->sendMail($this->post('email'), 'Prior Notice Registration Confirmation', $isi);
                    if($mail['status']) {
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Please check your email to activated your account'
                        ], RESTController::HTTP_CREATED);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Send email failed - ' . $mail['message']
                        ], RESTController::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Registration failed, please try again later'
                    ], RESTController::HTTP_BAD_REQUEST);
                }
            }
        }
    }
}