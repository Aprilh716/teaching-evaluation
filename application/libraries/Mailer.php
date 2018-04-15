<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Credis
 */
require_once  VENDORPATH . 'PHPMailer/PHPMailerAutoload.php';
class Mailer
{
    public $config;
    public $mail;

    public function __construct($params = array())
    {
        include(APPPATH . 'config/email.php');
        $type = $params['type'] ? $params['type'] : 'myjob';
        $this->config = $config[$type];
        $this->mail = new PHPMailer;
        $this->initMailer();
    }

    public function initMailer()
    {
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = $this->config['smtp_debug'];
        $this->mail->Debugoutput = $this->config['smtp_debug_type'];
        $this->mail->CharSet = $this->config['charset'];
        $this->mail->Host = $this->config['smtp_host'];
        $this->mail->Port = $this->config['smtp_port'];
        $this->mail->SMTPSecure = $this->config['smtp_crypto'];
        $this->mail->SMTPAuth = $this->config['smtp_auth'];
        $this->mail->Username = $this->config['smtp_user'];
        $this->mail->Password = $this->config['smtp_pass'];
    }

    public function send_email($from_name, $to, $title, $html)
    {
        try {
            $this->mail->setFrom($this->mail->Username, $from_name);
            $this->mail->addReplyTo($this->mail->Username, $from_name);
            if (is_array($to) && !empty($to)) {
                foreach ($to as $item) {
                    $this->mail->addAddress($item);
                }
            } else {
                $this->mail->addAddress($to);
            }
            $this->mail->Subject = $title;
            $this->mail->msgHTML($html, dirname(__FILE__));
            $this->mail->AltBody = 'This is a plain-text message body';
            if (!$this->mail->send()) {
                Loglog("form---" . $from_name . "\n");
                Loglog("to---" . $to . "\n");
                Loglog("Mailer Error——: " . $this->mail->ErrorInfo . "\n");
                return array('result' => false,'msg' => $this->mail->ErrorInfo);
            } else {
                Loglog("form---" . $from_name . "\n");
                Loglog("to---" . $to . "\n");
                Loglog("发送成功——: " . $this->mail->ErrorInfo . "\n");
                return array('result' => true,'msg' => '发送成功--' . $this->mail->ErrorInfo);
            }
        } catch (phpmailerException $e) {
            Loglog("form---" . $from_name . "\n");
            Loglog("to---" . $to . "\n");
            Loglog("Mailer Error——: " . $this->mail->ErrorInfo . "\n");
            return array('result' => false,'msg' => $this->mail->ErrorInfo);
        }
    }
}