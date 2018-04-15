<?php

class Login_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->model('user_model');
    }

    public function check_login($username, $password)
    {
        $user = $this->user_model->getUserByUsername($username);
        if (empty($user)) {
            return false;
        }
        if (md5($user['salt'] . ':' . $password) != $user['password']) {
            return false;
        }
        self::create_session_verify($user);
        return $user;

    }

    public function create_session_verify($user)
    {
        $uid = $user['uid'];
        return md5(TIMESTAMP . ':' . $uid) . '_' . TIMESTAMP . '_' . $uid;
    }

    public  function check_session_verify($verify)
    {
        if (empty($verify)) {
            return false;
        }

        list($hash, $timestamp, $uid) = explode('_', $verify);

        if (time() - $timestamp > 8 * 3600) {
            //return false;
        }

        $user = $this->user_model->getUser($uid);
        if (empty($user)) {
            return false;
        }
        if (md5($timestamp . ':' . $uid . ':' . $user['salt']) != $hash) {
            return false;
        }
        return $uid;
    }

    public static function set_session_verify_cookie($verify)
    {
        setcookie('__verify', $verify, time() + 300 * 86400, '/', WWW_HOST, false, true);
    }

    public static function clear_verify_cookie()
    {
        setcookie('__verify', '', -1, '/', WWW_HOST, false, true);
    }
}