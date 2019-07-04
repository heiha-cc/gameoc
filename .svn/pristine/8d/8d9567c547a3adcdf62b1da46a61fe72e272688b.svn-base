<?php

namespace pay;


use app\common\Api;

class Pay
{
    private $paymodel = null;

    public function __construct()
    {
        $this->paymodel = new WithdrawBank();
    }

    /**
     * Notes: 支付
     *
     */
    public function pay()
    {
        //获取待打款列表
        $res = Api::getInstance()->sendRequest([
            'startdate'         => '',
            'enddate'           => '',
            'roleid'            => 0,
            'OperateVerifyType' => 3,
            'payway'            => 2,
            'page'              => 1,
            'pagesize'          => 1000,
            'realname'          => '',
        ], 'charge', 'outlist');

        $list = isset($res['data']['list']) ? $res['data']['list'] : [];
        if (!$list) {
            save_log("apidata/withdrawbank", "code: " . $res["code"] . " msg: " . $res["message"] . " result: no data");
            exit;
        }


        foreach ($list as $v) {
            //逐条处理

            //判断各个参数格式
            if (!$v['orderno'] || !$v['roleid'] || !$v['realname'] || !$v['bankname'] || !$v['cardno'] || $v['totalmoney'] <= 0) {
                Api::getInstance()->sendRequest([
                    'roleid'    => $v['roleid'],
                    'orderid'   => $v['orderno'],
                    'status'    => 2,
                    'checkuser' => '系统处理',
                    'descript'  => '提现参数有误',
                ], 'charge', 'updatecheck');
                save_log("apidata/withdrawbank", "orderid: " . $v["orderno"] . " msg: "  . " 参数有误");
                continue;
            }

            //先更新状态为银行处理中
            Api::getInstance()->sendRequest([
                'roleid'    => $v['roleid'],
                'orderid'   => $v['orderno'],
                'status'    => 4,
                'checkuser' => '系统处理',
                'descript'  => '银行处理中',
            ], 'charge', 'updatecheck');


            //发送到银行
            //税收
            $tax = $v["totalmoney"] * config('site.tax')['tax'] / 100;
            $res1 = $this->paymodel->addCard($v["cardno"], $v["realname"], $v["bankname"], '福州', '福建'); //新增银行卡
            $res2 = $this->paymodel->addOrder($v["cardno"], $v["totalmoney"]-$tax, $v["realname"], $v["orderno"]);
            $res2 = json_decode($res2, true);
            if ($res2["success"] == true) {//成功
                //log记录
                save_log("apidata/withdrawbank", "orderid: " . $v["orderno"] . " msg: " .  " 银行处理中");
            } else {//失败
                //更新状态为银行处理失败
                Api::getInstance()->sendRequest([
                    'roleid'    => $v['roleid'],
                    'orderid'   => $v['orderno'],
                    'status'    => 6,
                    'checkuser' => '系统处理',
                    'descript'  => '银行处理未通过',
                ], 'charge', 'updatecheck');

                save_log("apidata/withdrawbank", "orderid: " . $v["orderno"] . " msg: " .  " 银行处理失败");
            }
        }
    }

    /**
     * Notes:回调
     */
    public function notify()
    {
        $get = file_get_contents("php://input");
        save_log("apidata/withdrawnotify", $get);
        $header = $_SERVER["HTTP_TLYHMAC"];
        save_log("apidata/withdrawnotify", "header:" . $header);
        $receive = json_decode($get, true);
        $this->paymodel->notify($receive, $header, file_get_contents("php://input"));
    }


}