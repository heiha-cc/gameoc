<?php

namespace app\admin\controller;


use app\common\Api;
use app\common\GameLog;
use socket\QuerySocket;


/**
 * 支付通道
 */
class Ranke extends Main
{

    /**
     * 金币排行
     */
    /**
     * @return View
     */
    public function coin()
    {
        if ($this->request->isAjax()) {
            $limit = intval(input('limit')) ? intval(input('limit')) : 10;
            $page = intval(input('page')) ? intval(input('page')) : 1;
            $res = Api::getInstance()->sendRequest(['page' => $page, 'pagesize' => $limit], 'player', 'toprank');
            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
        }
        return $this->fetch();
    }

    /**
     * 战绩排行
     */
    /**
     * @return View
     */
    public function winrank()
    {
        if ($this->request->isAjax()) {
            $roomId  = intval(input('roomid')) ? intval(input('roomid')) : 0;
            $limit = intval(input('limit')) ? intval(input('limit')) : 10;
            $page = intval(input('page')) ? intval(input('page')) : 1;
            $res = Api::getInstance()->sendRequest(['page' => $page, 'pagesize' => $limit,'roomid'   => $roomId,], 'player', 'winrank');
            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
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
