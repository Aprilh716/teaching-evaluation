<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends XI_Controller
{

    public function __construct()
    {
        parent::__construct();
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
        $params = [];
        $this->display('login.html', $params);
    }
}
