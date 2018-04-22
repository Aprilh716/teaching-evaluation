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
        $code = $this->input->post('code');
        $password = $this->input->post('password');
        $user = $this->login_model->check_login($code, $password);
        //setcookie('__role', $this->_user['role'], time() + 300 * 86400, '/', WWW_HOST, false, true);
        if ($user) {
            header('Location:/');
        }
    }

    public function logout()
    {
        Login_model::clear_verify_cookie();
        header('Location:/');
    }
}
