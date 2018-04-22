<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends XI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('conf_model');
        $this->load->model('lesson_model');
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
        $count = 5;
        $teacher_list = $this->user_model->getUserList(['role' => Conf_model::ROLE_TEACHER], $start, $count, $total);
        foreach ($teacher_list as $k => $v) {
            //$teacher_list[$k]['sorce'] = $this->lesson_model->getAvgScore($v['uid']);
        }
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
        $count = 5;
        $student_list = $this->user_model->getUserList(['role' => Conf_model::ROLE_STUDENT], $start, $count, $total);
        foreach ($student_list as $k => $v) {
            $student_list[$k]['grade'] = $this->user_model->getGradeByGid($v['grade_id']);
        }
        $grade_list = $this->user_model->getAllGrade();
        $pageHtml = getPageHtml($start, $count, $total, '/admin/student', 'normal');
        $params = [
            'pageHtml' => $pageHtml,
            'student_list' => $student_list,
            'grade_list' => $grade_list,
        ];
        $this->display('admin/student.html', $params);
    }

    public function question()
    {
        $start = intval($this->input->get('start'));
        $count = 2;
        $question_list = $this->lesson_model->getQuestionList([], $start, $count, $total);
        $question_list = $this->conf_model->formatQuestion($question_list);
        $pageHtml = getPageHtml($start, $count, $total, '/admin/question', 'normal');
        $params = [
            'pageHtml' => $pageHtml,
            'question_list' => $question_list,
            ];
        $this->display('admin/question.html', $params);
    }

    public function task()
    {
        $teacher_list = $this->user_model->getUserByRole(Conf_model::ROLE_TEACHER);
        $grade_list = $this->user_model->getAllGrade();
        $lesson_list = $this->lesson_model->getAllLesson();

        $params = [
            'teacher_list' => $teacher_list,
            'grade_list' => $grade_list,
            'lesson_list' => $lesson_list
        ];

        $this->display('admin/task.html', $params);
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
        if ($arr['role'] == Conf_model::ROLE_STUDENT) {
            $arr['grade_id'] = $this->input->post('grade_id');
        }
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

    public function add_question()
    {
        //添加
        $arr = array(
            'type' => intval($this->input->post('type')), //0|1
            'description' => $this->input->post('description')
        );
        $qid = $this->lesson_model->addQuestion($arr);
        if ($qid) {
            $this->responseJson(array('result'=>1,'qid' => $qid));
        } else {
            $this->responseJson(array('result'=>0));
        }
    }

    public function add_task()
    {
        //添加
        $arr = array(
            'teacher_uid' => intval($this->input->post('teacher')), //0|1
            'lid' => intval($this->input->post('lesson')),
            'gid' => intval($this->input->post('grade')),
        );
        $id = $this->lesson_model->addTask($arr);
        if ($id) {
            $this->responseJson(array('result'=>1,'qid' => $qid));
        } else {
            $this->responseJson(array('result'=>0));
        }
    }
}