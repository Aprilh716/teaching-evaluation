<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends XI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    //学生首页
    public function index()
    {
        //学生首页
        $this->display('student/home.html', []);
    }

    public function evaluation()
    {
        $this->display('student/evaluation.html', []);
    }
}