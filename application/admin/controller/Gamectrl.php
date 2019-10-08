<?php

namespace app\admin\controller;

use app\admin\controller\traits\search;
use app\common\Api;
use app\common\GameLog;
use socket\QuerySocket;
use app\admin\controller\traits\getSocketRoom;


class Gamectrl extends Main
{
    use getSocketRoom;
    use search;
    private $socket = null;

    public function __construct()
    {
        parent::__construct();
        $this->socket = new QuerySocket();
    }

    //游戏配置一览
    public function index()
    {
        $roomlist = Api::getInstance()->sendRequest(['id' => 0], 'room', 'kind');
        if ($this->request->isAjax()) {
            $roomId = intval(input('roomId')) ? intval(input('roomId')) : 0;
            $page = intval(input('page')) ? intval(input('page')) : 1;
            $limit = intval(input('limit')) ? intval(input('limit')) : 15;
            $orderby = intval(input('orderby')) ? intval(input('orderby')) : 0;
            $asc     = intval(input('asc')) ? intval(input('asc')) : 0;

            $res = $this->socket->getProfitPercent($roomId);

            $his = Api::getInstance()->sendRequest(['id' => 0], 'room', 'roompreperty');

            if ($res) {
                if ($roomlist['data']) {
                    foreach ($res as &$v) {
                        foreach ($his['data'] as $v5) {
                            if($v5['roomid']==$v['nRoomId']){
                                $v['mingtax']=$v5['mingtax'];
                                $v['goldmoney']=$v5['goldmoney'];
                                break;
                            }
                        }
                        foreach ($roomlist['data'] as $v2) {
                            if ($v['nRoomId'] == $v2['roomid']) {
                                $v['roomname'] = $v2['roomname'];
                            }
                        }
                        $v['lTotalRunning'] /= 1000;
                        $v['lTotalProfit'] /= 1000;
                        $v['lTotalTax'] /= 1000;
                        $v['lHistorySumRunning'] /= 1000;
                        $v['lHistorySumProfile'] /= 1000;
                        $v['lHistorySumTax'] /= 1000;
                        $v['nAdjustValue'] /= 1000;
                        $v['lTotalBlackTax'] /= 1000;
                        $v['lMinStorage'] /= 1000;
                        $v['lMaxStorage'] /= 1000;
                        $v['nCtrlValue'] = intval($v['nCtrlValue']/2);

//                        $v['currentget'] = $v['lTotalTax'] + $v['lTotalProfit'];
//                        $v['totalget'] = $v['lHistorySumTax'] + $v['lHistorySumProfile'];
                    }
                    unset($v);
                }
            }

            if ($orderby > 0) {
                if ($orderby == 1) {
                    $orderbystr = 'nCtrlValue';
                } elseif ($orderby == 2) {
                    $orderbystr = 'lTotalProfit';
                } elseif ($orderby == 3) {
                    $orderbystr = 'lTotalTax';
                } else {
                    $orderbystr = 'lTotalBlackTax';
                }

                if ($asc == 0) {
                    $ascstr = 'asc';
                } else {
                    $ascstr = 'desc';
                }

                $res = arraySort($res, $orderbystr, $ascstr);
            }
            //假分页
            $result = [];

            $from = ($page -1) * $limit;
            $to = $page * $limit - 1;
            $count = count($res);
            if ($count >0 && $count>=$from ) {
                for ($i=$from; $i<= $to; $i++) {
                    if (isset($res[$i])) {
                        $result[] = $res[$i];
                    }
                }
            }
            ob_clean();

            return $this->apiReturn(0, $result, '', $count, ['orderby' => $orderby, 'asc' => $asc]);
        }
        //$roomList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'kind');
        $kindList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'kindlist');
        $this->assign('roomlist', $roomlist['data']);
        $this->assign('kindlist', $kindList['data']);

        $res = Api::getInstance()->sendRequest(['id' => 0], 'room', 'list');
        $this->assign('roomlist', isset($res['data']['ResultData']) ? $res['data']['ResultData'] : []);
        $this->assign('historytotal', isset($res['data']['historytotal']) ? $res['data']['historytotal'] : 0);
        $this->assign('currentscore', isset($res['data']['currentscore']) ? $res['data']['currentscore'] : 0);
        $this->assign('totalonline', isset($res['data']['totalonline']) ? $res['data']['totalonline'] : 0);

        return $this->fetch();
    }


    //设置房间千分比
    public function setProfit()
    {
        if ($this->request->isAjax()) {
            $id = intval(input('id')) ? intval(input('id')) : 0;
            $setrange = input('setrange') ? input('setrange') : 1;
            $percent = input('percent') ? input('percent') : 0;
            $ajustvalue = input('ajustvalue') ? input('ajustvalue') : 0;
            $curstorage = input('curstorage') ? input('curstorage') : 0;
            $minstorage = input('minstorage') ? input('minstorage') : 0;
            $maxstorage = input('maxstorage') ? input('maxstorage') : 0;
            $type = 1;
//            if ($curstorage < $minstorage || $curstorage > $maxstorage) {
//                return $this->apiReturn(1, [], '当前库存不能小于最小值或大于最大值');
//            }
//            if ($minstorage > $maxstorage) {
//                return $this->apiReturn(2, [], '库存下限不能大于上限');
//            }

            $res = $this->socket->setProfitPercent($type, $setrange, $id, $percent, $ajustvalue*1000,$curstorage*1000,$minstorage*1000,$maxstorage*1000);
            $code = $res['iResult'];
            GameLog::logData(__METHOD__, $this->request->request(), ($code == 0) ? 1 : 0, $res);
            return $this->apiReturn($code);
        }

        $id = intval(input('id')) ? intval(input('id')) : 0;
        $percent = input('percent') ? input('percent') : 0;
        $ajustvalue = input('ajustvalue') ? input('ajustvalue') : 0;
        $curstorage = input('curstorage') ? input('curstorage') : 0;
        $minstorage = input('minstorage') ? input('minstorage') : 0;
        $maxstorage = input('maxstorage') ? input('maxstorage') : 0;
        $roomname = input('roomname') ? input('roomname') : 0;

        $this->assign('roomId', $id);
        $this->assign('roomname', $roomname);
        $this->assign('percent', $percent);
        $this->assign('ajustvalue', $ajustvalue);
        $this->assign('curstorage', $curstorage);
        $this->assign('minstorage', $minstorage);
        $this->assign('maxstorage', $maxstorage);
        return $this->fetch();
    }

    //在线玩家
    public function online()
    {
        if ($this->request->isAjax()) {
            $page    = intval(input('page')) ? intval(input('page')) : 1;
            $limit   = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId  = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $roomId  = intval(input('roomid')) ? intval(input('roomid')) : 0;
            $orderby = intval(input('orderby')) ? intval(input('orderby')) : 0;
            $asc     = intval(input('asc')) ? intval(input('asc')) : 0;
//            $mobile     = trim(input('mobile')) ? trim(input('mobile')) : '';

            $res = Api::getInstance()->sendRequest([
                'roleid'   => $roleId,
                'roomid'   => $roomId,
                'orderby'  => $orderby,
                'page'     => $page,
                'asc'      => $asc,
                //                'mobile'      => $mobile,
                'pagesize' => $limit
            ], 'player', 'online');
            if (isset($res['data']['list']) && $res['data']['list']) {
                foreach ($res['data']['list'] as &$v) {
                    //盈利
                    $v['totalget'] = $v['totalin'] - $v['totalout'];
                    //活跃度
                    $v['huoyue'] = $v['totalin'] != 0 ? round($v['totalwater'] / $v['totalin'], 2) : 0;

                }
                unset($v);
            }
            return $this->apiReturn($res['code'], isset($res['data']['list'])?$res['data']['list'] : [] , $res['message'], $res['total'], [
                'orderby' => isset($res['data']['orderby']) ? $res['data']['orderby'] : 0,
                'asc'     => isset($res['data']['asc']) ? $res['data']['asc'] : 0,
            ]);

        }
        $selectData = $this->getRoomList();
        $this->assign('selectData', $selectData);
        return $this->fetch();
    }

    /**
     * * Notes: 返回前端select标签里的内容
     * @return mixed
     */
    public function getRoomList()
    {
        $res = Api::getInstance()->sendRequest([
            'id' => 0
        ], 'room', 'kind');
        return $res['data'];
    }
}
