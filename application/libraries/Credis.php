<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Credis
 */
class Credis
{
    private static $instance;

    public function __construct()
    {
    }

    public static function getInstance($forceNew = false)
    {
        if(empty(self::$instance) || $forceNew) {
            include(APPPATH . 'config/redis.php');
            $conf = $redisConf[$active_group];
            $redis = new Redis();
            $redis->connect($conf['host'], $conf['port'], $conf['timeout']);
            self::$instance = $redis;
        }
        return self::$instance;
    }

}