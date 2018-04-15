<?php
/**
 * Upload_model
 * 文件存储
 */
class Upload_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library('upload');
    }

    public function upload($field, $uid)
    {
        $arr = explode('.', $_FILES[$field]['name']);
        $ext = trim(strtolower(($arr[count($arr) - 1])));
        if($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png') {
            return false;
        }


        $config['file_name'] = self::genName($ext);
        $config['upload_path'] = self::getPath($config['file_name']);
        $config['allowed_types'] = '*';
        $config['max_size'] = '5242880';  //5M
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            return $this->upload->display_errors('<p>', '</p>');
            //return false;
        }

        $res = $this->upload->data();
        $this->addUploadFile($_FILES[$field]['name'], $res['file_name'], $uid);
        return $res['file_name'];
    }
	
	public function save($raw, $uid)
    {
        list($explain, $content) = explode(',', $raw);
        preg_match('/([a-z]+)\/([a-z]+);/', $explain, $matches);
        //$type_raw = $matches[1];
        $ext = $matches[2];
        
        $filename = self::genName($ext);
        $res = file_put_contents(self::getFullPath($filename), base64_decode($content));
        $this->addUploadFile($filename, $filename, $uid);        
        return $filename;
    }
	
    public static function genName($ext)
    {
        return md5(implode(',', array(getmypid(), time(), mt_rand()))) . '.' . $ext;
    }

    public static function getPath($filename)
    {
        return UPLOADPATH . $filename[0] . '/' . $filename[1];
    }

    public static function getFullPath($filename)
    {
        return UPLOADPATH . $filename[0] . '/' . $filename[1] . '/' . $filename;
    }

    public static function getUrl($filename)
    {
        if(empty($filename)) {
            return '';
        }
        return 'http://' . IMG_HOST . '/upload/' . $filename[0] . '/' . $filename[1] . '/' . $filename;
    }

    public function addUploadFile($name, $store, $uid=0)
    {
        $arr = array(
            'uid' => $uid,
            'name' => $name,
            'store' => $store,
            );
        $this->db->insert('uploadfiles', $arr);
    }

    public function getUploadFile($store)
    {
        $res = $this->db->get_where('uploadfiles', array('store'=>$store))
                        ->row_array();
        if(empty($res)) {
            return array('name'=>$store);
        }
        return $res;
    }
}