<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 通用接口: 上传图片、App升级、配置信息等
 */
class Common extends XI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('upload_model');
        $this->load->model('conf_model');
    }

    public function upload()
    {
        $res = $this->upload_model->upload('fileUpload', $this->_uid);
        if(empty($res)) {
            $this->responseJson(array('result'=>0));
        } else {
            $this->responseJson(array('result'=>1, 'imgurl'=>Upload_model::getUrl($res)));
        }
    }
}