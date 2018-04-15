<?php

class Misc_model extends CI_Model
{
    public function __construct()
    {
        $this->load->library('http');
        $this->load->library('credis');
        $this->redis = Credis::getInstance();
    }

    //验证码
    public function setCaptcha($str)
    {
        $str = strtolower($str);
        $redis = CRedis::getInstance();
        $key = md5(implode(',', array(getmypid(), time(), mt_rand())));
        $redis->set('captcha:' . $key, $str);
        $redis->expire('captcha:' . $key, 600);
        return $key;
    }

	/**
     * 检验图片验证码正确性
     * @param $key
	 * @param $str
     * @return bool
     */
    public function verifyCaptcha($key, $str)
    {
        $str = strtolower($str);
        $redis = CRedis::getInstance();
        $res = $redis->get('captcha:' . $key);
        if(empty($res)) {
            return false;
        }

		if ($res == $str) {
			$redis->del('captcha:' . $key);
			return true;
		}
        return false;
    }

    public function getUserIp()
    {
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip_group = explode(',', getenv("HTTP_X_FORWARDED_FOR"));
            $ip = $ip_group[0];
        } else if (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "";
        }
        return $ip;
    }
}