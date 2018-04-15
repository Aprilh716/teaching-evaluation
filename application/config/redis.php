<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| redis CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your redis.
*/


$active_group = ENVIRONMENT;

$redisConf['testing'] = array(
    'host' => '127.0.0.1',
    'port' => 6379,
    'timeout' => 1,
);

$redisConf['production'] = array(
    'host' => '127.0.0.1',
    'port' => 6379,
    'timeout' => 1,
);

/* End of file redis.php */
/* Location: ./application/config/redis.php */