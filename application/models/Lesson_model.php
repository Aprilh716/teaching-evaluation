<?php
/**
 * Created by PhpStorm.
 * Date: 2018/4/22
 * Time: 下午5:02
 */

class Lesson_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->helper('misc');
    }

    public function getAllLesson()
    {
        return $this->db->select('*')->from('lesson')->get()->result_array();
    }

    public function getAvgScore($teacher_uid)
    {
        $query = 'select avg(sorce) as avg_sorce from student_answer AS sa 
                  LEFT JOIN lesson_teacher_grade AS ltg 
                  ON sa.lesson_teacher_grade_id=ltg.id 
                  WHERE ltg.teacher_uid=' . $teacher_uid;
        $lesson_teacher_grade_ids = $this->db->query($query)->row_array();
        return $lesson_teacher_grade_ids['avg_sorce'];
    }

    public function getLesson($lid)
    {
        return $this->db->get_where('lesson', array('lid'=>$lid))->row_array();
    }

    public function getQuestionList($where, $start, $count, &$total)
    {
        $res = $this->db->select('count(1) as total')
            ->from('question')
            ->where($where)
            ->get()
            ->row_array();

        $total = $res['total'];
        return $this->db->select('*')
            ->from('question')
            ->where($where)
            ->limit($count, $start)
            ->get()
            ->result_array();
    }

    public function getResultList($teacher_uid)
    {
        $query = 'select avg(gid) as gid, avg(sorce) as avg_sorce, sum(sorce) as sum_sorce from student_answer AS sa 
                  LEFT JOIN lesson_teacher_grade AS ltg 
                  ON sa.lesson_teacher_grade_id=ltg.id 
                  WHERE ltg.teacher_uid=' . $teacher_uid;
        $lesson_teacher_grade_ids = $this->db->query($query)->result_array();
        return $lesson_teacher_grade_ids;//[$lesson_teacher_grade_ids['avg_sorce'], $lesson_teacher_grade_ids['sum_sorce']];
    }

    public function addQuestion($data)
    {
        $data['created_at'] = TIMESTR;
        $data['updated_at'] = TIMESTR;
        $this->db->insert('question', $data);
        return $this->db->insert_id();
    }

    public function getAllQuestions()
    {
        return $this->db->select('*')->from('question')->get()->result_array();
    }

    public function getQuestion($qid)
    {
        return $this->db->get_where('question', array('qid'=>$qid))->row_array();
    }


    public function addTask($arr)
    {
        $arr['created_at'] = TIMESTR;
        $arr['updated_at'] = TIMESTR;
        $this->db->insert('lesson_teacher_grade', $arr);
        return $this->db->insert_id();
    }

    public function getTaskList($where, $start, $count, &$total)
    {
        $res = $this->db->select('count(1) as total')
            ->from('lesson_teacher_grade')
            ->where($where)
            ->get()
            ->row_array();

        $total = $res['total'];
        return $this->db->select('*')
            ->from('lesson_teacher_grade')
            ->where($where)
            ->limit($count, $start)
            ->get()
            ->result_array();
    }

    public function getLessonTeacherGrade($id)
    {
        return $this->db->get_where('lesson_teacher_grade', array('id'=>$id))->row_array();
    }

    public function addStudentAnswer($arr)
    {
        $arr['created_at'] = TIMESTR;
        $arr['updated_at'] = TIMESTR;
        $this->db->insert('student_answer', $arr);
        return $this->db->insert_id();
    }

    public function getStudentAnswerByWhere($where)
    {
        return $this->db->get_where('student_answer', $where)->row_array();
    }
}