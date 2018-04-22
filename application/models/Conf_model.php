<?php

class Conf_model extends CI_Model
{
    const
        ROLE_ADMIN = 3,
        ROLE_TEACHER = 2,
        ROLE_STUDENT = 1;

    public static $roles = array(
        self::ROLE_ADMIN => 'admin',
        self::ROLE_TEACHER => 'teacher',
        self::ROLE_STUDENT => 'student',
    );

    public static  $answer = array(
        4 => '非常优秀',
        3 => '不孬',
        2 => '一般化',
        1 => '辣鸡',
    );

    public static $questionType = array(
        0 => '选择题',
        1 => '主观题'
     );

    public function __construct()
    {
        $this->load->database();
    }

    public function formatQuestion($list)
    {
        if (empty($list)) {
            return [];
        }

        foreach ($list as $k => $item) {
            $list[$k]['type_desc'] = self::$questionType[$item['type']];
        }
        return $list;
    }
}