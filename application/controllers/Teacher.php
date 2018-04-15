<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends XI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('teacher_model');
    }

    //教师首页
    public function index()
    {
        //教师首页
        echo 'teacher';
    }
}