<?php
namespace app\admin\controller;

use pay\Pay;
class Withdraw {
    private $pay;

    public function __construct()
    {
        $this->pay = new Pay();
    }


    //提现
    public function index()
    {
        $this->pay->pay();
    }


    public function notify()
    {
        $this->pay->notify();
    }



}