<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends XI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    //教师首页
    public function index()
    {
        //教师首页
        $this->display('teacher/home.html', []);
    }

    public function see()
    {
        $this->display('teacher/see.html', []);
    }
}