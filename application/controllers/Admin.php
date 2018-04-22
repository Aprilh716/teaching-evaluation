<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends XI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('conf_model');
        $this->load->helper('page');
    }

    //管理员首页
    public function index()
    {
        //管理员首页
        $this->display('admin/home.html', []);
    }

    public function teach()
    {
        $start = intval($this->input->get('start'));
        $count = 2;
        $teacher_list = $this->user_model->getUserList(['role' => Conf_model::ROLE_TEACHER], $start, $count, $total);
        $pageHtml = getPageHtml($start, $count, $total, '/admin/teach', 'normal');
        $params = [
            'pageHtml' => $pageHtml,
            'teacher_list' => $teacher_list
        ];
        $this->display('admin/teach.html', $params);
    }

    public function student()
    {
        $start = intval($this->input->get('start'));
        $count = 2;
        $student_list = $this->user_model->getUserList(['role' => Conf_model::ROLE_STUDENT], $start, $count, $total);
        $pageHtml = getPageHtml($start, $count, $total, '/admin/student', 'normal');
        $params = [
            'pageHtml' => $pageHtml,
            'student_list' => $student_list
        ];
        $this->display('admin/student.html', $params);
    }

    /**
     * 添加用户
     * Author qina
     * Date 2018/4/22
     * Time 下午1:37
     */
    public function add_user()
    {
        //添加用户
        $arr = array(
            'role' => intval($this->input->post('role')), //1|2|3
            'name' => $this->input->post('name'),
            'code' => $this->input->post('code'),
            'password' => md5($this->input->post('code')),
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