  <?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Phpmailer_library
    {
        public function __construct()
        {
            log_message('Debug', 'PHPMailer class is loaded.');
        }

        public function sendMail($emailTujuan, $subjek, $isiMail)
        {
            require_once(APPPATH . 'third_party/phpmailer/src/PHPMailer.php');
            require_once(APPPATH . 'third_party/phpmailer/src/SMTP.php');

            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host     = 'smtp.hostinger.com';
            // $mail->Host     = 'mail.karantinaindonesia.go.id';
            $mail->SMTPAuth = true;
            // $mail->Username = 'noreply@karantinaindonesia.go.id';
            // $mail->Password = 'R4h4s14';
            $mail->Username = 'info@karantinaindonesia.id';
            $mail->Password = 'P4ssw0rd@Mail';
            $mail->SMTPSecure = 'ssl';
            $mail->Port     = 465;

            // $mail->setFrom('noreply@karantinaindonesia.id', 'Indonesian Quarantine Authority');
            $mail->setFrom('info@karantinaindonesia.id', 'Indonesian Quarantine Authority');
            // $mail->addReplyTo('info@karantinaindonesia.id', 'Indonesian Quarantine Authority');

            // Add a recipient
            $mail->addAddress($emailTujuan);

            // Add cc or bcc 
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Email subject
            $mail->Subject = $subjek;

            // Set email format to HTML
            $mail->isHTML(true);

            // Email body content
            $mail->Body = $isiMail;

            // Send email
            if (!$mail->send()) {
                $respon = array(
                    'status' => FALSE,
                    'message' => 'Gagal kirim - ' . $mail->ErrorInfo
                );
            } else {
                $respon = array(
                    'status' => TRUE,
                    'message' => 'Email sukses terkirim!'
                );
            }
            return $respon;
        }
    }
