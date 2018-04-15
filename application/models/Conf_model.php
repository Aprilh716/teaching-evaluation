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

    public function __construct()
    {
        $this->load->database();
    }
}