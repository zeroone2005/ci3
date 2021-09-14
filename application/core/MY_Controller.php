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

        protected function isAjaxPost()
        {
            return $this->isAjax() && $this->isPost();
        }

        protected function isAjaxGet()
        {
            return $this->isAjax() && $this->isGet();
        }

        /**
         * 输出错误信息
         * @param array  $data
         * @param string $msg
         * @param int    $code
         */
        public function error($msg = '', $data = [], $code = 1000)
        {
            $this->echoJson($msg, $data, $code);
        }

        /**
         * 输出成功信息
         * @param array  $data
         * @param string $msg
         * @param int    $code
         */
        public function success($msg = '', $data = [], $code = 0)
        {
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
        static public $ruleList = [];
        public $all_parent_id = [];
        public $current_menu_id = NULL;

        public function __construct()
        {
            parent::__construct();
            $this->load->model('Rule_model', 'rule');

            //print_r($_SESSION['user']['menu']);
            $this->checkAuth();
        }

        /**
         * 验证登录
         */
        protected function checkAuth()
        {
            //验证登录
            if (!isset($_SESSION['user'])) {
                if ($this->isAjax()) {
                    $this->echoJson('登录失效，请重新登录', NULL, 201);
                } else {
                    $url = $this->myclass->getUrl();
                    set_cookie('redirect_uri', $url, 60 * 60 * 24);

                    //重定向到登录页面
                    header('location:/index/index');
                }
            }

            //验证权限
            $module     = $this->uri->segment(1);
            $controller = $this->router->class;
            $method     = $this->router->method;

            if ($module != $controller) {
                $url = "/{$module}/{$controller}/{$method}";
            } else {
                $url = "/{$controller}/{$method}";
            }


            if ('common' == $controller) { //表示公共
                return TRUE;
            }

            $this->load->model('Rule_model', 'rule');
            $rule = $this->rule->where(['route' => $url])->order_by('id DESC')->to_convert(FALSE)->get();
            if (!$rule) {
                $this->echoJson('授权限制', NULL, 1000);
            } else {
                $pids   =  $this->getParentRule($rule['id']);
                $pids[] = $rule['id'];
                $this->all_parent_id = implode(',', $pids);
                if (!in_array($rule['id'], $_SESSION['user']['rule_id'])) {
                    $this->echoJson('访问限制', NULL, 1000);
                }
            }

        }


        /**
         * 无限级分类
         * @access public
         * @param Array $data  //数据库里获取的结果集
         * @param Int   $pid
         * @param Int   $count //第几级分类
         * @return Array $treeList
         */
        private function ruleList(&$data, $pid = 0, $level = 1)
        {
            foreach ($data as $key => $value) {
                if ($value['pid'] == $pid) {
                    $value['level']    = $level;
                    self::$ruleTree[] = $value;
                    unset($data[$key]);
                    $this->ruleTree($data, $value['id'], $level + 1);
                }
            }
        }

        private function ruleTree($data, $pid = 0){
            $list = [];
            foreach ($data as $k => $v){
                if ($v['pid'] == $pid){
                    $v['sub'] = $this->ruleTree($data, $v['id']);
                    $list[] = $v;
                }
            }
            return $list;
        }

        /**
         * 获取所有父权限
         * @param $id
         */
        private function getParentRule($id) {
            $pid = [];
            $rule = $this->rule->where(['id' => $id])->get();
            if (0 != $rule['pid']) {
                $pid = array_merge($this->getParentRule($rule['pid']), $pid);
            } else {
                $pid[] = $rule['pid'];
            }
            return $pid;
        }
    }
