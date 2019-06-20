<?php

namespace app\admin\controller;

use app\common\Api;
use app\common\GameLog;
use redis\Redis;

class Game extends Main
{
    /**
     * 充值汇总
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $page    = intval(input('page')) ?? 1;
            $limit   = intval(input('limit')) ?? 20;
            $roleId  = intval(input('roleid')) ?? 0;
            $roomId  = intval(input('roomid')) ?? 0;
            $orderby = intval(input('orderby')) ?? 0;

            $res = Api::getInstance()->sendRequest([
                'roleid'   => $roleId,
                'roomid'   => $roomId,
                'orderby'  => $orderby,
                'page'     => $page,
                'pagesize' => $limit
            ], 'player', 'online');
            if ($res['data']) {
                foreach ($res['data'] as &$v) {
                    //盈利
                    $v['totalget'] = $v['totalin'] - $v['totalout'];
                    //活跃度
                    $v['huoyue'] = $v['totalin'] != 0 ? sprintf(".2f", $v['totalwater'] / $v['totalin']) : 0;
                }
                unset($v);
            }
            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
        }

        return $this->fetch();
    }

}
