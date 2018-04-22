<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends XI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    //管理员首页
    public function index()
    {
        //管理员首页
        $this->display('admin/home.html', []);
    }

    public function teach()
    {
        $this->display('admin/teach.html', []);
    }

    public function addUserViews()
    {
        //添加用户页面

    }

    public function addUser()
    {
        //添加用户
        $arr = array(
            'role' => intval($this->input->post('role')), //1|2|3
            'name' => $this->input->post('name'),
            'password' => '123456'
        );
        //初始密码
        $uid = $this->user_model->addUser($arr);
        if ($uid) {
            $this->responseJson(array('result'=>1,'uid' => $uid));
        } else {
            $this->responseJson(array('result'=>0));
        }
    }

    public function delUser($uid)
    {
        //删除用户
        $this->user_model->delUser($uid);
        $this->responseJson(array('result'=>1));
    }
}