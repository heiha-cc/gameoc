<?php
namespace redis;

use think\Cache;

//定义redis操作
class Redis {
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
        if (!(self::$_instance instanceof Redis)){
            self::$_instance = new Redis();
        }
        return self::$_instance;
    }


    //设置缓存
    public function set($key, $value, $expire = 0)
    {
        return Cache::store('redis')->set($key, $value, $expire);
    }

    //读取缓存
    public function get($key)
    {
        return Cache::store('redis')->get($key);
    }

    //判断缓存
    public function has($key)
    {
        return Cache::store('redis')->has($key);
    }

    //自增
    public function inc($key, $step=1)
    {
        return Cache::store('redis')->inc($key, $step);
    }

    //自减
    public function dec($key, $step=1)
    {
        return Cache::store('redis')->dec($key, $step);
    }

    //删除缓存
    public function rm($key)
    {
        return Cache::store('redis')->rm($key);
    }

    /**
     * 如果不存在则写入缓存
     * @access public
     * @param  string $name   缓存变量名
     * @param  mixed  $value  存储数据
     * @param  int    $expire 有效时间 0为永久
     * @return mixed
     */
    public function remember($name, $value, $expire = 0)
    {
        return Cache::store('redis')->remember($name, $value, $expire);
    }

    //加入队列左部
    public function lpush($key, $value)
    {
        return Cache::store('redis')->handler()->lpush($key, $value);
    }

    //加入队列右部
    public function rpush($key, $value)
    {
        return Cache::store('redis')->handler()->rpush($key, $value);
    }

    //弹出队列左部
    public function lpop($key)
    {
        return Cache::store('redis')->handler()->lpop($key);
    }

    //弹出队列右部
    public function rpop($key)
    {
        return Cache::store('redis')->handler()->rpop($key);
    }

}