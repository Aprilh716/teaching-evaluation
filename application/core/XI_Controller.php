<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class XI_Controller extends CI_Controller
{
    protected $_uid = false;
    protected $_user = array();
    protected $_module;
    protected $_method;
    protected $_role;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('csmarty');
        $this->load->model('conf_model');
        $this->load->model('login_model');
        $this->load->model('user_model');
        $this->load->helper('cookie');

        //当前路径
        global $RTR;
        $this->_module = $RTR->fetch_class();
        $this->_method = $RTR->fetch_method();

        //检查登录
        if (!$this->checkLogin()) {
            if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $this->responseError(self::ERRNO_NEED_TOKEN);
            } else {
                header('Location: /index/login');
            }
            exit;
        }
    }

    /**
     * 检查是否登录
     * @return bool
     */
    protected function checkLogin()
    {
        $noLoginOption = array('index/*');
        //检查cookie
        $verify = @$_COOKIE['__verify'];
        $uid = $this->login_model->check_session_verify($verify);
        if (!$uid) {
            //检查不需要登录的
            foreach($noLoginOption as $item) {
                list($module, $method) = explode('/', $item);
                if($module == $this->_module && ($method == '*' || $this->_method == $method)) {
                    return true;
                }
            }
            return false;
        }
        $this->_uid = $uid;
        $this->_user = $this->user_model->getUser($uid);
        Login_model::create_session_verify($this->_user);
        //setcookie('__role', $this->_user['role'], time() + 300 * 86400, '/', WWW_HOST, false, true);
        $this->_role = $this->_user['role'];
        $adminOption = array(
            'admin/*'
        );
        $teacherOption = array(
            'teacher/*'
        );
        $studentOption = array(
            'student/*'
        );
        switch ($this->_role) {
            case Conf_model::ROLE_STUDENT:
                $optional = $studentOption;
                break;
            case Conf_model::ROLE_TEACHER:
                $optional = $teacherOption;
                break;
            case Conf_model::ROLE_ADMIN:
                $optional = $adminOption;
                break;
            default:
                $optional = [];
        }
        $optional = array_merge($optional,$noLoginOption);
        foreach($optional as $item) {
            list($module, $method) = explode('/', $item);
            if($module == $this->_module && ($method == '*' || $this->_method == $method)) {
                return true;
            }
        }
        return false;
    }

    //输出页面
    protected function display($tpl, $params)
    {
        $this->csmarty->assignHtml('_role', $this->_role);
        $this->csmarty->assignHtml('_module', $this->_module);
        $this->csmarty->assignHtml('_method', $this->_method);
        $this->csmarty->assignHtml('_uid', $this->_uid);
        $this->csmarty->assignRaw('_user', $this->_user);
        $this->csmarty->display('common/head.html');
        foreach($params as $key=>$value) {
            $this->csmarty->assignRaw($key, $value);
        }

        $this->csmarty->display($tpl);
        $this->csmarty->display('common/tail.html');
    }

    protected function responseJson(array $data)
    {
        echo json_encode(array(
                'errno' => 0,
                'errmsg' => '',
                'data' => $data,
            ));
        exit;
    }

    protected function responseError($errno)
    {
        echo json_encode(array(
                'errno' => $errno,
                'errmsg' => self::$errmsg[$errno],
                'data' => array(),
            ));
        exit;
    }

    //输出json
    const
        ERRNO_NEED_TOKEN = 100001,
        ERRNO_INVALID_PWD = 100002,
        ERRNO_INVALID_REGISTER = 100003,
        ERRNO_INVALID_USER = 100004,
        ERRNO_LOGIN_PWD = 100005,
        ERRNO_LOGIN_FAILED = 100006;

    private static $errmsg = array(
        self::ERRNO_NEED_TOKEN => '需要登录',
        self::ERRNO_INVALID_PWD => '密码必须含有字母数字长度6-10',
        self::ERRNO_INVALID_REGISTER => '用户已存在',
        self::ERRNO_INVALID_USER => '用户不存在,请注册',
        self::ERRNO_LOGIN_PWD => '密码错误',
        self::ERRNO_LOGIN_FAILED => '登录失败请重试'
    );
}
