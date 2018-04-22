<?php

class User_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
		$this->load->helper('misc');
    }
    
    //添加用户
    public function addUser($data)
    {
        $data['created_at'] = TIMESTR;
        $data['updated_at'] = TIMESTR;
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }
   
    public function updatePwd($uid, $password)
    {
        $arr = array(
            'password' => md5($password),
            'updated_at' => TIMESTR,
        );
        return $this->db->update('user', $arr, array('uid'=>$uid));
        
    }

    public function updateUser($uid, $data)
    {
        $arr['updated_at'] = TIMESTR;
        $this->db->update('user', $data, array('uid'=>$uid));
    }

    public function getUser($uid)
    {
        return $this->db->get_where('user', array('uid'=>$uid))
                        ->row_array();
    }
    public function getUserByCode($code)
    {
        return $this->db->get_where('user', array('code'=>$code))->row_array();
    }

    public function getManyUser($uids)
    {
        $uids = array_unique($uids);
		if(empty($uids)) {
			return array();
		}

        $res = $this->db->select('*')
                        ->from('user')
                        ->where_in('uid', $uids)
                        ->get()
                        ->result_array();

		return list2map($res, 'uid');
    }


    /**
     * 用户列表
     * Date 2018/4/22
     * Time 下午1:55
     * @param $where
     * @param $start
     * @param $count
     * @param $total
     * @return mixed
     */
    public function getUserList($where, $start, $count, &$total)
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
            ->from('user')
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
            ->from('user')
            ->where($where)
            ->limit($count, $start)
            ->get()
            ->result_array();
    }
}