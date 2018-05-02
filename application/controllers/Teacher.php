<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends XI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('lesson_model');
        $this->load->model('user_model');
    }

    //教师首页
    public function index()
    {
        //教师首页
        $this->display('teacher/home.html', []);
    }

    public function see()
    {
        $ret = $this->lesson_model->getResultList($this->_uid);
        foreach ($ret as $key => $val) {
            $grade = $this->user_model->getGradeByGid($val['gid']);
            $ret[$key]['grade_name'] = $grade['major'] . $grade['grade'];
        }

        $this->display('teacher/see.html', ['rets' => $ret]);
    }
}