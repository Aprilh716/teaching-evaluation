<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends XI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('lesson_model');
        $this->load->helper('page');
    }

    //学生首页
    public function index()
    {
        //学生首页
        $this->display('student/home.html', []);
    }

    public function evaluation()
    {
        $start = intval($this->input->get('start'));
        $count = 2;
        $user = $this->user_model->getUser($this->_uid);
        $task_list = $this->lesson_model->getTaskList(['gid' => $user['grade_id']], $start, $count, $total);
        foreach ($task_list as $key => $task) {
            $task_list[$key]['teacher'] = $this->user_model->getUser($task['teacher_uid']);
            $task_list[$key]['lesson'] = $this->lesson_model->getLesson($task['lid']);
        }
        $pageHtml = getPageHtml($start, $count, $total, '/student/evaluation', 'normal');
        $params = [
            'pageHtml' => $pageHtml,
            'task_list' => $task_list,
            'user' => $user
        ];
        $this->display('student/evaluation.html', $params);
    }

    public function answer_question()
    {
        //
    }
}