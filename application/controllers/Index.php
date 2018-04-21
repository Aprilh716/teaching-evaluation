<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends XI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
    }

    /**
     * 首页
     */
    public function index()
    {
        if (!$this->_role) {
            header('Location:/index/login');
        } elseif ($this->_role == Conf_model::ROLE_STUDENT) {
            header('Location:/student');
        } elseif ($this->_role == Conf_model::ROLE_TEACHER) {
            header('Location:/teacher');
        } elseif ($this->_role == Conf_model::ROLE_ADMIN) {
            header('Location:/admin');
        }
    }

    public function login()
    {
        $this->csmarty->display('login.html');
    }

    public function auth()
    {
        ob_start();
        $code = $this->input->post('code');
        $password = $this->input->post('password');
        $user = $this->login_model->check_login($code, $password);
        $verify =  md5(TIMESTAMP . ':' . $user['uid']) . '_' . TIMESTAMP . '_' .  $user['uid'];
        var_dump($verify);
        setcookie('__verify',$verify, 865000);
        //setcookie('__verify', $verify, time() + 300 * 86400, '/', WWW_HOST, false, true);
        //setcookie('__role', $user['role'], time() + 300 * 86400, '/', WWW_HOST, false, true);
        $verify = @$_COOKIE['__verify'];
        var_dump($verify);exit;
        //$uid = $this->login_model->check_session_verify($verify);
        //var_dump($uid);exit;
        if ($user) {
            //header('Location:/');
        }
    }

    public function logout()
    {
        Login_model::clear_verify_cookie();
        header('Location:/');
    }
}
