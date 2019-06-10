<?php

namespace app\common;

class Api {
    //存放实例
    private static $_instance = null;

    //私有化构造方法、
    private function __construct(){

    }
    //私有化克隆方法
    private function __clone(){

    }

    //公有化获取实例方法
    public static function getInstance(){
        if (!(self::$_instance instanceof Api)){
            self::$_instance = new Api();
        }
        return self::$_instance;
    }

    /**
     * 生成sign
     * @param $params array 参数
     * @return string
     */
    public function generateSign($params)
    {
        //按字典顺序排序
        ksort($params);
        //拼接参数
        $str = '';
        foreach ($params as $k => $v) {
            if ($v && !is_array($v)) {
                $str .= $k . '=' . $v . '&';
            }
        }
        $str  = rtrim($str, '&');
        //拼接key, 并用md5加密
        $sign = md5($str . '&key=' . config('apikey'));
        return $sign;
    }

    /**
     * Notes: 生成curlpost请求
     * @param $url string 地址
     * @param $data array 参数
     * @return mixed
     */
    public function curlPost($url, $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_HEADER,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * Notes: 发送请求
     * @param $params array 参数
     * @param $controller string 访问控制器
     * @param $method string 访问方法
     * @return mixed
     */
    public function sendRequest($params, $controller, $method)
    {
        $sign = $this->generateSign($params);
        $params['sign'] = $sign;
        //请求地址拼接
        $sendurl = config('apiurl').$controller.'/'.$method;
        $res = $this->curlPost($sendurl, $params);
        halt($res);
        return $res;
    }
}