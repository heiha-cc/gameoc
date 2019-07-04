<?php

namespace app\admin\controller;



use app\common\Api;
use app\common\GameLog;



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
        $this->assign('checkuser', input('checkuser') ? input('checkuser') : '');
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

            $res = Api::getInstance()->sendRequest([
                'roleid'   => $request['roleid'],
                'orderid' => $request['orderid'],
                'status'      => 2,
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
        $this->assign('checkuser', input('checkuser') ? input('checkuser') : '');
        $this->assign('descript', input('descript') ? input('descript') : '');
        return $this->fetch();
    }

}
