<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');

    class MY_Controller extends CI_Controller
    {
        public $website;

        public function __construct()
        {
            parent::__construct();
            $this->website = $this->config->item('website');
        }



        protected function isPost()
        {
            return $this->input->method() == 'post';
        }

        protected function isGet()
        {
            return $this->input->method() == 'get';
        }

        protected function isAjax()
        {
            return $this->input->is_ajax_request();
        }

        protected  function isAjaxPost()
        {
            return $this->isAjax() && $this->isPost();
        }
        protected  function isAjaxGet()
        {
            return $this->isAjax() && $this->isGet();
        }

        /**
         * 输出错误信息
         * @param array  $data
         * @param string $msg
         * @param int    $code
         */
        public function error($msg = '', $data = [],  $code = 1000)
        {
            $this->echoJson( $msg, $data, $code);
        }

        /**
         * 输出成功信息
         * @param array  $data
         * @param string $msg
         * @param int    $code
         */
        public function success($msg = '', $data = [], $code = 0) {
            $this->echoJson($msg, $data, $code);
        }

        /**
         * 输出json对象
         * @param $data
         */
        public function echoJson($msg, $data, $code)
        {
            header('Content-Type: application/json; charset=utf-8');
            if (is_array($msg)) {
                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['code' => $code, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

    }

    class Admin_Controller extends MY_Controller
    {
        public function __construct()
        {
            parent::__construct();
            //$this->checkLogin(); //验证的登录
        }

        /**
         * 验证登录
         */
        protected function checkLogin()
        {
            if (!isset($_SESSION['admin'])) {
                if ($this->isAjax()) {
                    $this->echoJson(['code' => 1001, 'msg' => '登录失效，请重新登录']);
                } else {
                    $url = $this->myclass->getUrl();
                    set_cookie('m_redirect_uri', $url, 60 * 60 * 24);
                    //重定向到登录页面
                    header('location:/index/index');
                }
            }
        }
    }

    class Api_Controller extends MY_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->myclass->checkSign();
        }

    }