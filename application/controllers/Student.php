<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends XI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('student_model');
    }

    //学生首页
    public function index()
    {
        //学生首页
        echo 'student';
    }
}