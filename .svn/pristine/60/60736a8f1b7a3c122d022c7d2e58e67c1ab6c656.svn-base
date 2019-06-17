<?php

namespace app\admin\controller;


use app\common\Api;
use app\common\GameLog;

/**
 * 支付通道
 */
class Payment extends Main
{
    /**
     * 线下转账
     */
    public function offline()
    {
        if ($this->request->isAjax()) {
            $res = Api::getInstance()->sendRequest([], 'payment', 'offline');
            return $this->apiReturn($res['code'], $res['data'], $res['message'], 2);
        }
        return $this->fetch();
    }

    /**
     * 线下转账修改
     */
    public function editOffline()
    {
        if ($this->request->isAjax()) {
            $request  = $this->request->request();
            $validate = validate('Offline');
            $validate->scene('editOffline');
            if (!$validate->check($request)) {
                return $this->apiReturn(1, [], $validate->getError());
            }
            $res = Api::getInstance()->sendRequest([
                'classid'   => $request['classid'],
                'classname' => $request['classname'],
                'bank'      => $request['bank'],
                'cardno'    => $request['cardno'],
                'cardname'  => $request['cardname'],
                'descript'  => $request['descript']
            ], 'payment', 'updateoff');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }


        $this->assign('classid', input('classid'));
        $this->assign('classname', input('classname') ?? '');
        $this->assign('bank', input('bank') ?? '');
        $this->assign('cardno', input('cardno') ?? '');
        $this->assign('cardname', input('cardname') ?? '');
        $this->assign('descript', input('descript') ?? '');
        return $this->fetch();
    }

    /**
     * 支付通道
     */
    public function channel()
    {
        if ($this->request->isAjax()) {
            $limit = intval(input('limit')) ?? 20;
            $page  = intval(input('page')) ?? 1;

            $res = Api::getInstance()->sendRequest(['page' => $page, 'pagesize' => $limit], 'payment', 'channel');
            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
        }
        return $this->fetch();
    }

    /**
     * 新增支付通道
     */
    public function addChannel()
    {
        if ($this->request->isAjax()) {
            $request  = $this->request->request();
            $validate = validate('Payment');
            $validate->scene('addChannel');
            if (!$validate->check($request)) {
                return $this->apiReturn(1, [], $validate->getError());
            }

            $insert = [
                'channelname' => $request['channelname'],
                'mchid'       => $request['mchid'] ?? '',
                'appid'       => $request['appid'] ?? '',
                'noticeurl'   => $request['noticeurl'] ?? '',
                'descript'    => $request['descript'] ?? '',
                'status'      => 0
            ];


            $res = Api::getInstance()->sendRequest($insert, 'payment', 'addchannel');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }
        return $this->fetch();
    }

    /**
     * Notes: 编辑支付通道
     * @return mixed
     */
    public function editChannel()
    {
        if ($this->request->isAjax()) {
            $request  = $this->request->request();
            $validate = validate('Payment');
            $validate->scene('editChannel');
            if (!$validate->check($request)) {
                return $this->apiReturn(1, [], $validate->getError());
            }
            $data = [
                'channelid'   => $request['channelid'],
                'channelname' => $request['channelname'],
                'mchid'       => $request['mchid'] ?? '',
                'appid'       => $request['appid'] ?? '',
                'noticeurl'   => $request['noticeurl'] ?? '',
                'descript'    => $request['descript'] ?? '',
            ];
            $res  = Api::getInstance()->sendRequest($data, 'payment', 'updatechannel');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }


        $this->assign('channelid', input('channelid'));
        $this->assign('channelname', input('channelname') ?? '');
        $this->assign('mchid', input('mchid') ?? '');
        $this->assign('appid', input('appid') ?? '');
        $this->assign('noticeurl', input('noticeurl') ?? '');
        $this->assign('descript', input('descript') ?? '');
        return $this->fetch();
    }

    /**
     * Notes: 删除支付通道
     * @return mixed
     */
    public function deleteChannel()
    {
        $request  = $this->request->request();
        $validate = validate('Payment');
        $validate->scene('deleteChannel');
        if (!$validate->check($request)) {
            return $this->apiReturn(1, [], $validate->getError());
        }

        $res = Api::getInstance()->sendRequest(['id' => $request['id']], 'payment', 'delchannel');

        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return $this->apiReturn($res['code'], $res['data'], $res['message']);
    }

    /**
     * Notes: 开启、关闭通道
     * @return mixed
     */
    public function setChannelStatus()
    {
        $request  = $this->request->request();
        $validate = validate('Payment');
        $validate->scene('setChannelStatus');
        if (!$validate->check($request)) {
            return $this->apiReturn(1, [], $validate->getError());
        }

        $res = Api::getInstance()->sendRequest(['id' => $request['id'], 'status' => $request['status']], 'payment', 'setchannel');

        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return $this->apiReturn($res['code'], $res['data'], $res['message']);
    }

    /**
     * 支付金额
     */
    public function amount()
    {
        if ($this->request->isAjax()) {
            $res = Api::getInstance()->sendRequest([], 'payment', 'amount');
            return $this->apiReturn($res['code'], $res['data'], $res['message'], 10);
        }
        return $this->fetch();
    }

    /**
     * 新增金额
     */
    public function addAmount()
    {
        if ($this->request->isAjax()) {
            $request  = $this->request->request();
            $validate = validate('Payment');
            $validate->scene('addAmount');
            if (!$validate->check($request)) {
                return $this->apiReturn(1, [], $validate->getError());
            }
            $res = Api::getInstance()->sendRequest([
                'amount' => $request['amount']
            ], 'payment', 'addamount');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }
        return $this->fetch();
    }

    /**
     * Notes: 修改金额
     * @return mixed
     */
    public function editAmount()
    {
        if ($this->request->isAjax()) {
            $request  = $this->request->request();
            $validate = validate('Payment');
            $validate->scene('editAmount');
            if (!$validate->check($request)) {
                return $this->apiReturn(1, [], $validate->getError());
            }
            $res = Api::getInstance()->sendRequest([
                'id'     => $request['id'],
                'amount' => $request['amount']
            ], 'payment', 'updateamount');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }


        $this->assign('id', input('id'));
        $this->assign('amount', input('amount') ?? '');
        return $this->fetch();
    }

    /**
     * Notes: 删除固定金额
     * @return mixed
     */
    public function deleteAmount()
    {
        $request  = $this->request->request();
        $validate = validate('Payment');
        $validate->scene('deleteAmount');
        if (!$validate->check($request)) {
            return $this->apiReturn(1, [], $validate->getError());
        }
        $res = Api::getInstance()->sendRequest(['id' => $request['id']], 'payment', 'delamount');

        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return $this->apiReturn($res['code'], $res['data'], $res['message']);
    }

    /**
     * 通道金额关系列表
     */
    public function payment()
    {
        if ($this->request->isAjax()) {
            $page      = input('page') ?? 1;
            $pagesize  = input('limit') ?? 10;
            $classid   = input('classid') ?? 0;
            $channelid = input('channelid') ?? 0;

            $res = Api::getInstance()->sendRequest([
                'classid'   => $classid,
                'channelid' => $channelid,
                'page'      => $page,
                'pagesize'  => $pagesize
            ], 'payment', 'relate');
            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
        }

        $channel = Api::getInstance()->sendRequest(['page' => 1, 'pagesize' => 1000], 'payment', 'channel');
        $class = config('site.zf_class');
        $this->assign('channel', $channel['data']);
        $this->assign('class', $class);
        return $this->fetch();
    }


    /**
     * Notes: 删除关系
     * @return mixed
     */
    public function deletePayment()
    {
        $request  = $this->request->request();
        $validate = validate('Payment');
        $validate->scene('deletePayment');
        if (!$validate->check($request)) {
            return $this->apiReturn(1, [], $validate->getError());
        }

        $res = Api::getInstance()->sendRequest(['id' => $request['id']], 'payment', 'delrelate');

        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return $this->apiReturn($res['code'], $res['data'], $res['message']);
    }

    /**
     * 新增通道金额关系
     */
    public function addPayment()
    {
        if ($this->request->isAjax()) {
            $request  = $this->request->request();
            $validate = validate('Payment');
            $validate->scene('addPayment');
            if (!$validate->check($request)) {
                return $this->apiReturn(1, [], $validate->getError());
            }
            $res = Api::getInstance()->sendRequest([
                'amountid' => $request['amountid'],
                'classid' => $request['classid'],
                'channelid' => $request['channelid'],
            ], 'payment', 'addrelate');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }

        $channel = Api::getInstance()->sendRequest(['page' => 1, 'pagesize' => 1000], 'payment', 'channel');
        $amount = Api::getInstance()->sendRequest(['page' => 1, 'pagesize' => 1000], 'payment', 'amount');
        $class = config('site.zf_class');
        $this->assign('channel', $channel['data'] ?? []);
        $this->assign('amount', $amount['data'] ?? []);
        $this->assign('class', $class);
        return $this->fetch();
    }

}
