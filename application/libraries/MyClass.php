<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    use \Godruoyi\Snowflake\Snowflake;

    class MyClass
    {
        private $ci;
        private $snowflake;

        public function __construct()
        {
            $this->ci        = &get_instance();
            $this->snowflake = new Snowflake();
        }


        /**
         * 下划线转驼峰(大驼峰)
         *
         * @param string $uncamelized_words 需要转换的字符串
         * @param string $separator         分割字符串
         * @return string
         */
        public function camelize($uncamelized_words, $separator = '_')
        {
            $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
            return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
        }

        /**
         * 驼峰转下划线
         *
         * @param sring  $camelCaps 需要转换的字符串
         * @param string $separator 分类字符串
         * @return string
         */
        public function uncamelize($camelCaps, $separator = '_')
        {
            return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
        }


        /**
         * 需要加密的字符串
         *
         * @param string $string
         * @return string
         */
        public function urlSafeBase64Encode($string)
        {
            $data = base64_encode($string);
            $data = str_replace(['+', '/', '='], ['-', '_', ''], $data);
            return $data;
        }

        /**
         * 需要解密的字符串
         *
         * @param string $string
         * @return string
         */
        public function urlSafeBase64Decode($string)
        {
            $data = str_replace(['-', '_'], ['+', '/'], $string);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }
            return base64_decode($data);
        }

        /**
         * 生成随机字符串
         *
         * @param integer $length
         * @return void
         */
        /**
         * 生成随机字符串，数字，大小写字母随机组合
         *
         * @param int $length 长度
         * @param int $type   类型，1 纯数字，2 纯小写字母，3 纯大写字母，4 数字和小写字母，5 数字和大写字母，6 大小写字母，7 数字和大小写字母
         */
        function randStr($length = 6, $type = 7)
        {
            // 取字符集数组
            $number      = range(0, 9);
            $lowerLetter = range('a', 'z');
            $upperLetter = range('A', 'Z');
            // 根据type合并字符集
            if ($type == 1) {
                $charset = $number;
            } elseif ($type == 2) {
                $charset = $lowerLetter;
            } elseif ($type == 3) {
                $charset = $upperLetter;
            } elseif ($type == 4) {
                $charset = array_merge($number, $lowerLetter);
            } elseif ($type == 5) {
                $charset = array_merge($number, $upperLetter);
            } elseif ($type == 6) {
                $charset = array_merge($lowerLetter, $upperLetter);
            } elseif ($type == 7) {
                $charset = array_merge($number, $lowerLetter, $upperLetter);
            } else {
                $charset = $number;
            }
            $str = '';
            // 生成字符串
            for ($i = 0; $i < $length; $i++) {
                $str .= $charset[mt_rand(0, count($charset) - 1)];
                // 验证规则
                if ($type == 4 && strlen($str) >= 2) {
                    if (!preg_match('/\d+/', $str) || !preg_match('/[a-z]+/', $str)) {
                        $str = substr($str, 0, -1);
                        $i   = $i - 1;
                    }
                }
                if ($type == 5 && strlen($str) >= 2) {
                    if (!preg_match('/\d+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                        $str = substr($str, 0, -1);
                        $i   = $i - 1;
                    }
                }
                if ($type == 6 && strlen($str) >= 2) {
                    if (!preg_match('/[a-z]+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                        $str = substr($str, 0, -1);
                        $i   = $i - 1;
                    }
                }
                if ($type == 7 && strlen($str) >= 3) {
                    if (!preg_match('/\d+/', $str) || !preg_match('/[a-z]+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                        $str = substr($str, 0, -2);
                        $i   = $i - 2;
                    }
                }
            }
            return $str;
        }

        /**
         * 获取首字母
         * @param $str
         * @return int|string|null
         */
        public function getFirstLetter($str)
        {
            if (empty($str)) {
                return '';
            }
            if (is_numeric($str{0})) return $str{0};// 如果是数字开头 则返回数字
            $fchar = ord($str{0});
            if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0}); //如果是字母则返回字母的大写
            $s1  = iconv('UTF-8', 'gb2312', $str);
            $s2  = iconv('gb2312', 'UTF-8', $s1);
            $s   = $s2 == $str ? $s1 : $str;
            $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
            if ($asc >= -20319 && $asc <= -20284) return 'A';//这些都是汉字
            if ($asc >= -20283 && $asc <= -19776) return 'B';
            if ($asc >= -19775 && $asc <= -19219) return 'C';
            if ($asc >= -19218 && $asc <= -18711) return 'D';
            if ($asc >= -18710 && $asc <= -18527) return 'E';
            if ($asc >= -18526 && $asc <= -18240) return 'F';
            if ($asc >= -18239 && $asc <= -17923) return 'G';
            if ($asc >= -17922 && $asc <= -17418) return 'H';
            if ($asc >= -17417 && $asc <= -16475) return 'J';
            if ($asc >= -16474 && $asc <= -16213) return 'K';
            if ($asc >= -16212 && $asc <= -15641) return 'L';
            if ($asc >= -15640 && $asc <= -15166) return 'M';
            if ($asc >= -15165 && $asc <= -14923) return 'N';
            if ($asc >= -14922 && $asc <= -14915) return 'O';
            if ($asc >= -14914 && $asc <= -14631) return 'P';
            if ($asc >= -14630 && $asc <= -14150) return 'Q';
            if ($asc >= -14149 && $asc <= -14091) return 'R';
            if ($asc >= -14090 && $asc <= -13319) return 'S';
            if ($asc >= -13318 && $asc <= -12839) return 'T';
            if ($asc >= -12838 && $asc <= -12557) return 'W';
            if ($asc >= -12556 && $asc <= -11848) return 'X';
            if ($asc >= -11847 && $asc <= -11056) return 'Y';
            if ($asc >= -11055 && $asc <= -10247) return 'Z';
            return NULL;

        }

        /**
         * 获取区域编码
         *
         * @param integer $parent_id 父节点
         * @param integer $level     level 1省  2市  3区 4街道
         * @return array
         */
        public function getRegion($pid = 0, $level = 1, $country = 'cn')
        {
            $config = $this->_CI->config->item('website')['cqyy_api'];
            $this->_CI->load->library('Request');
            $url  = $config['url'] . '/' . $config['ver'] . $config['api']['region'];
            $data = [
                'appid'     => $config['app_id'],
                'token'     => $config['token'],
                'country'   => $country,
                'parent_id' => $pid,
                'level'     => $level,
            ];

            $result = json_decode($this->_CI->request->ssl(FALSE)->simple_post($url, $data), TRUE);
            if (0 == $result['code']) {
                $res = $result['data'];
            } else {
                $res = FALSE;
            }
            return $res;
        }

        /**
         * 返回随机编号
         * @return string
         */
        public function getNo($prefix = '')
        {
            $no = $this->snowflake->id();
            if (0 > $no) {
                $no = abs($no);
            }
            return $prefix . $no;
        }

        //记录日志
        public function log($type, $content)
        {
            $max_size     = 100000;
            $log_filename = APPPATH . "logs/" . $type . ".log";
            if (file_exists($log_filename) && abs(filesize($log_filename)) > $max_size) {
                $new_filename = APPPATH . "logs/" . $type . "_" . time() . ".log";
                @rename($log_filename, $new_filename);
            }
            file_put_contents($log_filename, date('H:i:s') . " \r\n " . $content . "\r\n", FILE_APPEND);
        }

        /**
         * 加密
         * @param $data
         */
        public function encrypt($data)
        {
            $file   = APPPATH . '../.key';
            $key    = file_get_contents($file);
            $return = base64_encode(\Dcrypt\Aes256Cbc::encrypt($data, $key));
            return $return;
        }

        /**
         * 解密
         * @param $data
         * @return mixed
         */
        public function decrypt($data)
        {
            $file   = APPPATH . '../.key';
            $key    = file_get_contents($file);
            $return = \Dcrypt\Aes256Cbc::decrypt(base64_decode($data), $key);
            return $return;
        }

        /**
         * 输出json
         * @param $data
         */
        public function echoJson($data)
        {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        /**
         * 获取用户ip
         * @return mixed|string
         */
        public function getIp()
        {
            if (isset($_SERVER["HTTP_CLIENT_IP"]) && strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown")) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown")) {
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    if (isset($_SERVER["REMOTE_ADDR"]) && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) {
                        $ip = $_SERVER["REMOTE_ADDR"];
                    } else {
                        if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],
                                "unknown")
                        ) {
                            $ip = $_SERVER['REMOTE_ADDR'];
                        } else {
                            $ip = "unknown";
                        }
                    }
                }
            }
            return ip2long($ip);
        }


        /**
         * 发送短信
         * @param $mobile
         * @param $content
         */
        public function sendSms($mobile, $content)
        {
            $client   = new \GuzzleHttp\Client(['verify' => FALSE]);
            $api      = config_item('website')['sharkapi'];
            $url      = $api['url'] . '/' . $api['ver'] . $api['api']['sms'];
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'appid'   => $api['app_id'],
                    'token'   => $api['token'],
                    'content' => $content,
                    'mobile'  => $mobile,
                ],
            ]);
            return json_decode($response->getBody()->getContents(), TRUE);
        }

        /**
         * 获取当前页面完整url
         * @return string
         */
        public function getUrl()
        {
            $current_url = 'http://';
            if (isset($_SERVER['HTTPS']) && 'on' == $_SERVER['HTTPS']) {
                $current_url = 'https://';
            }
            if ('80' != $_SERVER['SERVER_PORT']) {
                $current_url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
            } else {
                $current_url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            }
            return $current_url;
        }

        /**
         * 验证验证码有效性
         * @param $mobile
         * @param $type
         */
        public function checkCaptcha($mobile, $type)
        {

            $this->ci->load->model('Captcha_model', 'captcha');
            //判断验证码是否有效
            $time    = time();
            $captcha = $this->ci->captcha->where(['account' => $mobile, 'type' => $type])->order_by(['id' => 'DESC'])->get();
            if (!$captcha) {
                return FALSE;
            }
            if ($time > $captcha['expire_date'] || 2 == $captcha['state']) {
                return FALSE;
            }

            $this->ci->captcha->where(['id' => $captcha['id']])->update(['state' => 2]);
            return TRUE;
        }


        /**
         * 元金额转为分
         * @param $amount
         */
        public function amountToInt($amount)
        {
            return (int)bcmul($amount . '', '100'); //转为分
        }

        /**
         * 分转元 2位小数
         * @param $amount
         */
        public function amountToFloat($amount, $decimal = 2)
        {
            $amount = '' . $amount;
            return sprintf("%.{$decimal}f", bcdiv($amount, '100', 5));
        }

        /**
         * 计算签名
         * @param      $data      请求的数据
         * @param null $shop_no   店铺编号
         * @param null $token     店铺token
         * @return array
         */
        public function createSign($data = [], $token = NULL)
        {
            foreach ($data as $key => $item) {
                if (NULL == $item) {
                    $data[$key] = '';
                }
            }
            $data['sign_time'] = time();
            ksort($data);
            $str = [];
            foreach ($data as $key => $v) {
                if (is_array($v)) {
                    $v = json_encode($v, JSON_UNESCAPED_UNICODE);
                }
                $str[] = $key . '=' . $v;
            }
            $sign_str = implode('', $str) . 'token=' . $token;
            $this->log('push', '字符串：' . $sign_str);
            $sign = md5($sign_str);
            return ['data' => $data, 'sign' => $sign];
        }


        /**
         * post请求
         * @param $url
         * @param $data
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function requestPost($url, $token, $data = [])
        {
            $config    = config_item('website');
            $return    = $this->createSign($data, $token);
            $client    = new GuzzleHttp\Client();
            $post_data = [
                'headers'     => [
                    'token'         => $token,
                    'Authorization' => $return['sign'],
                ],
                'form_params' => $return['data'],
            ];
            $url       = $config['api_url'] . $url;
            $response  = $client->request('POST', $url, $post_data);
            $content   = $response->getBody()->getContents();
            $result = json_decode($content, TRUE);
            //$this->log('push', '返回值：' . $content);
            return $result;
        }

        /**
         * Get请求
         * @param $url
         * @param $data
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function requestGet($url, $token, $data = [])
        {
            $config   = config_item('website');
            $return   = $this->createSign($data);
            $client   = new GuzzleHttp\Client();
            $get_data = [
                'headers'     => [
                    'agent_no'      => $token,
                    'Authorization' => $return['sign'],
                ],
                'form_params' => $return['data'],
            ];

            $url      = $config['api_url'] . $url;
            $response = $client->request('GET', $url, $get_data);
            $content  = $response->getBody()->getContents();
            $result   = json_decode($content, TRUE);
            return $result;
        }
    }