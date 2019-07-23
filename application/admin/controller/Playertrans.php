<?php

namespace app\admin\controller;



use app\common\Api;
use app\common\GameLog;
use redis\Redis;
use socket\QuerySocket;


class Playertrans extends Main
{
    /**
     * 转出申请审核
     */
    public function apply()
    {

        if ($this->request->isAjax()) {

            $page    = intval(input('page')) ? intval(input('page')) : 1;
            $limit   = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId  = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $start   = input('start') ? input('start') : '';
            $end     = input('end') ? input('end') : '';
            $payway  = intval(input('payway'))>0 ? intval(input('payway')) : 0;
            $realname = input('realname') ? input('realname') : '';
            $data = ['page' => $page, 'pagesize' => $limit, 'roleid' => $roleId,'startdate' =>  $start,'enddate' =>  $end , 'OperateVerifyType' => 0 ,'payway' => $payway, 'realname' => $realname];
            $res = Api::getInstance()->sendRequest($data, 'charge', 'outlist');
            return $this->apiReturn($res['code'], isset($res['data']['list'])?$res['data']['list']:[], $res['message'], $count = $res['total'], ['alltotal'=>isset($res['data']['total'])?$res['data']['total']:0]);

        }
        return $this->fetch();


    }

    /**
     * 财务审核
     */
    public function finance()
    {

        if ($this->request->isAjax()) {

            $page    = intval(input('page')) ? intval(input('page')) : 1;
            $limit   = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId  = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $start   = input('start') ? input('start') : '';
            $end     = input('end') ? input('end') : '';
            $payway  = intval(input('payway'))>0 ? intval(input('payway')) : 0;
            $realname = input('realname') ? input('realname') : '';
            $data = ['page' => $page, 'pagesize' => $limit, 'roleid' => $roleId,'startdate' =>  $start,'enddate' =>  $end , 'OperateVerifyType' => 1 ,'payway' => $payway, 'realname' => $realname];
            $res = Api::getInstance()->sendRequest($data, 'charge', 'outlist');
            if (isset($res['data']['list'])) {
                foreach ($res['data']['list'] as &$v) {
                    $v['totaltax'] = config('site.tax')['tax']*$v['totalmoney']/100;
                }
                unset($v);
            }

            return $this->apiReturn($res['code'], isset($res['data']['list'])?$res['data']['list']:[], $res['message'], $count = $res['total'], ['alltotal'=>isset($res['data']['total'])?$res['data']['total']:0]);

        }

        return $this->fetch();
    }

    /**
     * 转出记录
     */
    public function record()
    {
        if ($this->request->isAjax()) {

            $page    = intval(input('page')) ? intval(input('page')) : 1;
            $limit   = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId  = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $OperateVerifyType= intval(input('classid'))>=0 ? intval(input('classid')) : -1;
            $start   = input('start') ? input('start') : '';
            $end     = input('end') ? input('end') : '';
            $payway  = intval(input('payway'))>=0 ? intval(input('payway')) : 0;

            $realname = input('realname') ? input('realname') : '';
            $data = ['page' => $page, 'pagesize' => $limit, 'roleid' => $roleId,'startdate' =>  $start,'enddate' =>  $end , 'OperateVerifyType' => $OperateVerifyType,'payway' => $payway, 'realname' => $realname];
            $res = Api::getInstance()->sendRequest($data, 'charge', 'outlist');
            if (isset($res['data']['list'])) {
                foreach ($res['data']['list'] as &$v) {
                    $v['totaltax'] = config('site.tax')['tax']*$v['totalmoney']/100;
                }
                unset($v);
            }

            return $this->apiReturn($res['code'], isset($res['data']['list'])?$res['data']['list']:[], $res['message'], $count = $res['total'], ['alltotal'=>isset($res['data']['total'])?$res['data']['total']:0]);
        }
        return $this->fetch();
    }

    /**
     * 同意
     */
    public function agree()
    {
        if ($this->request->isAjax()) {

            $request  = $this->request->request();
            $status=$request['status'];
            if($status==0){
                $status=1;
            }else if($status==1){
                $status=3;
            }
            $res = Api::getInstance()->sendRequest([
                'roleid'   => $request['roleid'],
                'orderid' => $request['orderid'],
                'status'      => $status,
                'checkuser'    => $request['checkuser'],
                'descript'  => $request['descript']
            ], 'charge', 'updatecheck');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }


        $this->assign('roleid', input('roleid'));
        $this->assign('orderid', input('orderid') ? input('orderid') : '');
        $this->assign('status', input('status') ? input('status') : '');
        $this->assign('checkuser', session('username'));
        $this->assign('descript', input('descript') ? input('descript') : '');
        return $this->fetch();
    }

    /**
     * 拒绝
     */
    public function refuse()
    {
        if ($this->request->isAjax()) {

            $request  = $this->request->request();

            //加锁
            $key = 'lock_refuseorder_'.$request['orderid'];
            if (!Redis::getInstance()->lock($key)) {
                return $this->apiReturn(1, [], '请勿重复操作');
            }
            $res = Api::getInstance()->sendRequest([
                'roleid'   => $request['roleid'],
                'orderid' => $request['orderid'],
                'status' => 2,
                'checkuser'    => $request['checkuser'],
                'descript'  => $request['descript']
            ], 'charge', 'updatecheck');


            if ($res['code'] == 0) {
                //给玩家返还
                //查询订单详情
                $orderInfo =  Api::getInstance()->sendRequest([
                    'orderno' => $request['orderid'],
                ], 'charge', 'orderdetail');
                if ($orderInfo['data']) {
                    if (($orderInfo['data']['status'] == 2 || $orderInfo['data']['status'] == 6) && $orderInfo['data']['isreturn'] == 0 &&  is_numeric($orderInfo['data']['imoney']) && $orderInfo['data']['imoney']>0) {
                        //拒绝或者银行未通过 && 未返还
                        //给玩家返还
                        //先更新状态
                        $update = Api::getInstance()->sendRequest([
                            'orderno' => $request['orderid'],
                            'status'  => 1
                        ], 'charge', 'updatereturn');

                        if ($update['code'] == 0) {
                            //发钱
                            $socket = new QuerySocket();
                            $a = $socket->addRoleMoney($request['roleid'], $orderInfo['data']['imoney']);
                            save_log('returnmoney', json_encode($update). ' addmoneycode : '.$a['iResult']);
                        }
                    }
                }
            }
            Redis::getInstance()->rm($key);
            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }



        $this->assign('roleid', input('roleid'));
        $this->assign('orderid', input('orderid') ? input('orderid') : '');
        $this->assign('status', input('status') ? input('status') : '');
        $this->assign('checkuser', session('username'));
        $this->assign('descript', input('descript') ? input('descript') : '');
        return $this->fetch();
    }


    //冻结没收
    public function freeze()
    {
        if ($this->request->isAjax()) {
            $request  = $this->request->request();
            //加锁
            $key = 'lock_refuseorder_'.$request['orderid'];
            if (!Redis::getInstance()->lock($key)) {
                return $this->apiReturn(1, [], '请勿重复操作');
            }

            $res = Api::getInstance()->sendRequest([
                'roleid'   => $request['roleid'],
                'orderid' => $request['orderid'],
                'status' => 2,
                'checkuser'    => $request['checkuser'],
                'descript'  => $request['descript']
            ], 'charge', 'updatecheck');


            if ($res['code'] == 0) {
                //冻结
                $update = Api::getInstance()->sendRequest([
                    'orderno' => $request['orderid'],
                    'status'  => 2
                ], 'charge', 'updatereturn');
            }
            Redis::getInstance()->rm($key);
            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }

        $this->assign('roleid', input('roleid'));
        $this->assign('orderid', input('orderid') ? input('orderid') : '');
        $this->assign('status', input('status') ? input('status') : '');
        $this->assign('checkuser', session('username'));
        $this->assign('descript', input('descript') ? input('descript') : '');
        return $this->fetch();
    }


    //补发
    public function bufa()
    {
        $request  = $this->request->request();

        if (!$request['orderno']) {
            return $this->apiReturn(1, [], '参数有误');
        }
        //加锁
        $key = 'lock_bufaorder_'.$request['orderno'];
        if (!Redis::getInstance()->lock($key)) {
            return $this->apiReturn(2, [], '请勿重复操作');
        }

        $orderInfo =  Api::getInstance()->sendRequest([
            'orderno' => $request['orderno'],
        ], 'charge', 'orderdetail');
        if ($orderInfo['data']) {
            if (($orderInfo['data']['status'] == 2 || $orderInfo['data']['status'] == 6) && $orderInfo['data']['isreturn'] == 0 &&  is_numeric($orderInfo['data']['imoney']) && $orderInfo['data']['imoney']>0) {
                //拒绝或者银行未通过 && 未返还
                //给玩家返还
                //先更新状态
                $update = Api::getInstance()->sendRequest([
                    'orderno' => $request['orderno'],
                ], 'charge', 'updatereturn');

                if ($update['code'] == 0) {
                    //发钱
                    $socket = new QuerySocket();
                    $a = $socket->addRoleMoney($request['roleid'], $orderInfo['data']['imoney']);
                    save_log('returnmoney', json_encode($update). ' addmoneycode : '.$a['iResult']);
                    Redis::getInstance()->rm($key);
                    return $this->apiReturn(0, [], '补发成功');
                } else {
                    Redis::getInstance()->rm($key);
                    return $this->apiReturn(3, [], '补发失败');
                }
            } else {
                Redis::getInstance()->rm($key);
                return $this->apiReturn(4, [], '补发失败');
            }
        } else {
            Redis::getInstance()->rm($key);
            return $this->apiReturn(5, [], '未找到订单信息');
        }
    }


    //查看备注详情
    public function descript()
    {
        if ($this->request->isAjax()) {
            $orderno = input('orderno') ? input('orderno') : '';
            if (!$orderno) {
                return $this->apiReturn(1, [], '参数有误');
            }

            $descInfo =  Api::getInstance()->sendRequest([
                'orderno' => $orderno,
            ], 'charge', 'checknote');

            return $this->apiReturn($descInfo['code'], $descInfo['data'], $descInfo['message']);
        }

        $orderno = input('orderno') ? input('orderno') : '';
        $this->assign('orderno', $orderno);
        return $this->fetch();
    }

}
