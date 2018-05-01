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
            $task_list[$key]['answer'] = $this->lesson_model->getStudentAnswerByWhere(['lesson_teacher_grade_id' => $task['id']]);
        }
        $pageHtml = getPageHtml($start, $count, $total, '/student/evaluation', 'normal');
        $params = [
            'pageHtml' => $pageHtml,
            'task_list' => $task_list,
            'user' => $user
        ];
        $this->display('student/evaluation.html', $params);
    }

    public function answer_question($lesson_teacher_grade_id)
    {
        //是否已评教
        $student_answer = $this->lesson_model->getStudentAnswerByWhere(['lesson_teacher_grade_id' => $lesson_teacher_grade_id]);
        if ($student_answer) {
            header('Location:/student/evaluation');
        }
        $lesson_teacher_grade = $this->lesson_model->getLessonTeacherGrade($lesson_teacher_grade_id);
        $question_list = $this->lesson_model->getAllQuestions();
        $answer_list = Conf_model::$answer;
        $params = [
            'answer_list' => $answer_list,
            'question_list' => $question_list,
            'lesson_teacher_grade' => $lesson_teacher_grade,
        ];

        $this->display('student/answer_question.html', $params);
    }

    public function answer($lesson_teacher_grade_id)
    {
        $answer_arr = $this->input->post();
        $sorce = 0;
        $answer = [];
        foreach ($answer_arr as $item => $value) {
            $question = $this->lesson_model->getQuestion($item);
            $sorce += $question['type'] == 0 ? $value : 0;
            $answer[] = $item . ':' . $value;
        }
        $answer_str = implode(';', $answer);
        $arr = [
            'lesson_teacher_grade_id' => $lesson_teacher_grade_id,
            'answer' => $answer_str,
            'sorce' => $sorce,
        ];
        $this->lesson_model->addStudentAnswer($arr);
        header('Location:/student/evaluation');
    }
}