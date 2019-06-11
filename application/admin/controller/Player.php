<?php

namespace app\admin\controller;

use app\common\Api;
use app\common\GameLog;
use redis\Redis;
class Player extends Main
{
    /**
     * 在线玩家
     */
    public function online()
    {
        if ($this->request->isAjax()) {

        }

        return $this->fetch();
    }

    /**
     * 所有玩家
     */
    public function all()
    {
        if ($this->request->isAjax()) {

        }
        return $this->fetch();
    }

    /**
     * Notes: 超级玩家
     * @return mixed
     */
    public function super()
    {
        if ($this->request->isAjax()) {
            $page   = intval(input('page')) ?? 1;
            $limit  = intval(input('limit')) ?? 20;
            $roleId = input('roleid') ?? '';
            $res = Api::getInstance()->sendRequest(['roleid' => $roleId, 'page' => $page, 'pagesize' => $limit], 'SuperUser', 'list');
            $res = ['code' => 0, 'data' => [['roleid'=>111,'nickname' => 'laowang', 'totalin'=>111,'totalout'=>222,'rate'=>22]], 'message' => 'cg', 'count' => 1];

            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['count']);
        }
        return $this->fetch();
    }

    /**
     * Notes: 新增超级玩家
     * @return mixed
     */
    public function addSuper()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();
            $validate = validate('Player');
            $validate->scene('addSuper');
            if (!$validate->check($request)) {
                return $this->apiReturn(1, [], $validate->getError());
            }

            $res = Api::getInstance()->sendRequest(['roleid' => $request['roleid'], 'rate' => $request['rate']], 'SuperUser', 'add');
            $res = json_decode($res, true);
            $res = ['code' => 0, 'data' => [], 'message' => '成功'];

            //log记录
            GameLog::logData(__METHOD__, $request, $res['code'] == 0 ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }
        return $this->fetch();
    }


    /**
     * 向玩家转账
     */
    public function transfer()
    {
        if ($this->request->isAjax()) {

        }
        return $this->fetch();
    }

    /**
     * 向玩家转账
     */
    public function addTransfer()
    {
        if ($this->request->isAjax()) {

        }
        return $this->fetch();
    }

    /**
     * 设置玩家胜率
     */
    public function setPlayerRate()
    {
        return $this->fetch();
    }
}
