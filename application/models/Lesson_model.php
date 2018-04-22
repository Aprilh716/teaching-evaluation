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

    public function getAvgScore($teacher_uid)
    {
        //
        $lesson_teacher_grade_ids = $this->db->select('id')->from('lesson_teacher_grade')->where('teacher_uid',$teacher_uid)->get()->result_array();
        return $lesson_teacher_grade_ids;
    }

    public function getQuestionList($where, $start, $count, &$total)
    {
        /*
        if(isset($where['keyword'])) {
            $keyword = $where['keyword'];
            unset($where['keyword']);
        }

        if(!empty($keyword)) {
            $this->db->like('id_name', $keyword);
        }
        */

        $res = $this->db->select('count(1) as total')
            ->from('question')
            ->where($where)
            ->get()
            ->row_array();

        $total = $res['total'];
        /*
        if(!empty($keyword)) {
            $this->db->like('id_name', $keyword);
        }
        */
        return $this->db->select('*')
            ->from('question')
            ->where($where)
            ->limit($count, $start)
            ->get()
            ->result_array();
    }

    public function addQuestion($data)
    {
        $data['created_at'] = TIMESTR;
        $data['updated_at'] = TIMESTR;
        $this->db->insert('question', $data);
        return $this->db->insert_id();
    }

}