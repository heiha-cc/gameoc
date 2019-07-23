<?php

namespace app\admin\controller;


use app\common\Api;
use app\common\GameLog;
use socket\QuerySocket;


/**
 * 支付通道
 */
class Gamemanage extends Main
{

    /**
     * 微信客服管理
     */
    public function weixin()
    {

        if ($this->request->isAjax()) {
            $limit = intval(input('limit')) ? intval(input('limit')) : 10;
            $page  = intval(input('page')) ? intval(input('page')) : 1;
            $res   = Api::getInstance()->sendRequest(['page' => $page, 'pagesize' => $limit], 'system', 'wxvip');
            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
        }
        return $this->fetch();
    }

    /**
     * 新增微信
     */
    public function addWeixin()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();


            $insert = [
                'weixinname' => $request['weixinname'] ? $request['weixinname'] : '',
                'type'       => $request['type'] ? $request['type'] : 0,
                'noticetip'  => $request['noticetip'] ? $request['noticetip'] : '',
                'id'         => 0,
            ];


            $res = Api::getInstance()->sendRequest($insert, 'system', 'addvip');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }
        return $this->fetch();
    }

    /**
     * Notes: 编辑微信
     * @return mixed
     */
    public function editWeixin()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();

            $data = [
                'weixinname' => $request['weixinname'] ? $request['weixinname'] : '',
                'type'       => $request['type'] ? $request['type'] : 0,
                'noticetip'  => $request['noticetip'] ? $request['noticetip'] : '',
                'id'         => $request['id'] ? $request['id'] : 0,
            ];
            $res  = Api::getInstance()->sendRequest($data, 'system', 'addvip');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }


        $this->assign('id', input('id'));
        $this->assign('type', input('type') ? input('type') : '');
        $this->assign('weixinname', input('weixinname') ? input('weixinname') : '');
        $this->assign('noticetip', input('noticetip') ? input('noticetip') : '');
        return $this->fetch();
    }

    /**
     * Notes: 删除微信
     * @return mixed
     */
    public function deleteWeixin()
    {
        $request = $this->request->request();
        $res     = Api::getInstance()->sendRequest(['id' => $request['id']], 'system', 'delvip');
        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return $this->apiReturn($res['code'], $res['data'], $res['message']);
    }

    /**
     * 新增ip/机器码
     */
    public function addIp()
    {
        return $this->fetch();
    }


    /**
     * Notes: 删除黑名单
     * @return mixed
     */
    public function deleteIp()
    {
        $request = $this->request->request();
        $res     = Api::getInstance()->sendRequest(['id' => $request['id']], 'game', 'delblack');
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return $this->apiReturn($res['code'], $res['data'], $res['message']);
    }


    /**
     * 黑名单列表
     */
    public function black()
    {
        if ($this->request->isAjax()) {
            $limit = intval(input('limit')) ? intval(input('limit')) : 10;
            $page  = intval(input('page')) ? intval(input('page')) : 1;
            $ipstr = input('roleid') ? input('roleid') : '';

            $res = Api::getInstance()->sendRequest(['page' => $page, 'pagesize' => $limit, 'ipstr' => $ipstr], 'game', 'black');
            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
        }
        return $this->fetch();
    }

    /**
     * 黑名单设置
     */
    public function blacklist()
    {
        if ($this->request->isAjax()) {
            $ip   = input('ip');
            $type = input('type');
            if (!in_array($type, [1, 3, 4]) || !$ip) {
                return $this->apiReturn(1, [], '设置信息有误');
            }
            $socket = new QuerySocket();
            if ($type == 1) {
                //ip
                if (!checkIp($ip)) {
                    return $this->apiReturn(2, [], 'ip有误');
                }

                $res = $socket->setBlackList($ip, $type);
                if ($res && $res['iResult'] == 0) {
                    return $this->apiReturn(0, [], '设置成功');
                } else {
                    return $this->apiReturn(5, [], '设置失败');
                }
            } else if ($type == 3) {
                //ip段
                $ipArr = explode('-', $ip);
                if (count($ipArr) != 2) {
                    return $this->apiReturn(3, [], 'ip段有误');
                }
                foreach ($ipArr as $item) {
                    if (!checkIp($item)) {
                        return $this->apiReturn(4, [], 'ip段有误');
                    }
                }

                $res = $socket->setBlackList($ip, $type);
                if ($res && $res['iResult'] == 0) {
                    return $this->apiReturn(0, [], '设置成功');
                } else {
                    return $this->apiReturn(5, [], '设置失败');
                }
            } else {
//                //通过id封号
//                $day    = 300;
//                $result = $socket->lockRoleStatus($ip, $day);
//                if ($result["iResult"] == 0) {
//                    ob_clean();
//                    GameLog::logData(__METHOD__, $this->request->request(), 1);
//                    return $this->apiReturn(0, [], '角色锁定成功!');
//                } else {
//                    GameLog::logData(__METHOD__, $this->request->request(), 1);
//                    return $this->apiReturn(1, [], '角色锁定失败');
//                }


                $res = $socket->setBlackList($ip, $type);

                if ($res && $res['iResult'] == 0) {
                    return $this->apiReturn(0, [], '设置成功');
                } else {
                    return $this->apiReturn(5, [], '设置失败');
                }

            }


        }
        return $this->fetch();
    }


    public function blacklist2()
    {
        if ($this->request->isAjax()) {
            $ip   = input('ip');
            $type = input('type');
            if (!in_array($type, [1, 3]) || !$ip) {
                return $this->apiReturn(1, [], '设置信息有误');
            }

            if ($type == 1) {
                //ip
                if (!checkIp($ip)) {
                    return $this->apiReturn(2, [], 'ip有误');
                }
            } else {
                //ip段
                $ipArr = explode('-', $ip);
                if (count($ipArr) != 2) {
                    return $this->apiReturn(3, [], 'ip段有误');
                }
                foreach ($ipArr as $item) {
                    if (!checkIp($item)) {
                        return $this->apiReturn(4, [], 'ip段有误');
                    }
                }
            }

            $socket = new QuerySocket();
            $res    = $socket->setBlackList($ip, $type);
            if ($res && $res['iResult'] == 0) {
                return $this->apiReturn(0, [], '设置成功');
            } else {
                return $this->apiReturn(5, [], '设置失败');
            }


        }
        return $this->fetch();
    }

    /**
     * 玩家id对应的ip
     */
    public function searchIpbyId()
    {
        if ($this->request->isAjax()) {
            $roleid2 = input('roleid2') ? input('roleid2') : 0;
            $res     = Api::getInstance()->sendRequest(['id' => $roleid2], 'game', 'getip');
            return $this->apiReturn(0, $res['data'], $res['message'], $res['total']);
        }
        return $this->fetch();
    }

    /**
     * ip对应的玩家数量
     */
    public function searchPlayerNumbyIp()
    {
        if ($this->request->isAjax()) {
            $roleid3 = input('roleid3') ? input('roleid3') : 0;
            $res     = Api::getInstance()->sendRequest(['ip' => $roleid3], 'game', 'ipnum');
            return $this->apiReturn(0, $res['data'], $res['message'], $res['total']);
        }
        return $this->fetch();
    }

    /**
     * 游戏任务配置
     */
    public function task()
    {
        if ($this->request->isAjax()) {
            $limit = intval(input('limit')) ? intval(input('limit')) : 10;
            $page  = intval(input('page')) ? intval(input('page')) : 1;
            $res   = Api::getInstance()->sendRequest(['page' => $page, 'pagesize' => $limit], 'game', 'gametask');
            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
        }
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

    /**
     * 新增游戏任务配置
     */
    public function addTask()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();


            $insert = [
                'roomid'       => intval($request['roomid']) ? intval($request['roomid']) : 0,
                'taskreqround' => intval($request['taskreqround']) ? intval($request['taskreqround']) : 0,
                'taskaward'    => intval($request['taskaward']) ? intval($request['taskaward']) : '',
                //                'taskname'     => $request['taskname'] ? $request['taskname'] : 0,
                'status'       => intval($request['status']) ? intval($request['status']) : 0,
            ];

            $res = Api::getInstance()->sendRequest($insert, 'game', 'addtask');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }
        $selectData = $this->getRoomList();
        $this->assign('selectData', $selectData);
        return $this->fetch();
    }

    /**
     * Notes: 编辑任务
     * @return mixed
     */
    public function editTask()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();
            $data    = [
                'roomid'       => $request['roomid'] ? $request['roomid'] : '',
                'taskreqround' => $request['taskreqround'] ? $request['taskreqround'] : 0,
                'taskaward'    => $request['taskaward'] ? $request['taskaward'] : '',
                'taskname'     => $request['taskname'] ? $request['taskname'] : 0,

            ];
            $res     = Api::getInstance()->sendRequest($data, 'game', 'updatetask');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }


        $this->assign('roomid', input('roomid') ? input('roomid') : 0);
        $this->assign('taskreqround', input('taskreqround') ? input('taskreqround') : 0);
        $this->assign('taskaward', input('taskaward') ? input('taskaward') : 0);
        $this->assign('taskname', input('taskname') ? input('taskname') : '');
        $this->assign('status', input('status') ? input('status') : '');
        return $this->fetch();
    }

    /**
     * Notes: 游戏任务上下架
     * @return mixed
     */
    public function setTaskStatus()
    {
        $request = $this->request->request();


        $res = Api::getInstance()->sendRequest(['roomid' => $request['id'], 'status' => $request['status']], 'game', 'updatetaskstatus');

        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return $this->apiReturn($res['code'], $res['data'], $res['message']);
    }

    /**
     * 游戏公告管理
     */
    public function notice()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();
            $res     = Api::getInstance()->sendRequest(['id' => 2], 'game', 'sysnotice');

            if (isset($res['data']['msgcontent']) && $res['data']['msgcontent']) {
//                if ($request['desc'] == $res['data']) {
                if ($request['desc'] == $res['data']['msgcontent']) {
                    return $this->apiReturn(0, $res['data']['msgcontent'], '更新成功');
                }
            }
            $updateNotice = [
                'content' => $request['desc'] ? $request['desc'] : '',
                'status' => 1,
                'classid' => 2
            ];
            $res          = Api::getInstance()->sendRequest($updateNotice, 'game', 'updatenotice');
            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }
        $res = Api::getInstance()->sendRequest(['id' => 2], 'game', 'sysnotice');
//        $this->assign('notice', $res['data']);
        $this->assign('notice', $res['data']['msgcontent']);
        return $this->fetch();
    }


    /**
     * 保存公告
     */
    public function saveNotice()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();
            $insert  = [
                'weixinname' => $request['weixinname'] ? $request['weixinname'] : '',
                'type'       => $request['type'] ? $request['type'] : 0,
                'noticetip'  => $request['noticetip'] ? $request['noticetip'] : '',
                'id'         => 0,
            ];
            $res     = Api::getInstance()->sendRequest($insert, 'system', 'addvip');
            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }
        return $this->fetch();
    }


    //弹窗公告管理
    public function alert()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();
            $res     = Api::getInstance()->sendRequest(['id' => 3], 'game', 'sysnotice');
            if (isset($res['data']['msgcontent']) && $res['data']['msgcontent']) {
                if ($request['desc'] == $res['data']['msgcontent'] && $request['status']==$res['data']['status']) {
                    return $this->apiReturn(0, $res['data'], '更新成功');
                }
            }
            $updateNotice = [
                'content' => $request['desc'] ? $request['desc'] : '',
                'status' => $request['status'] ? $request['status'] : 0,
                'classid' => 3
            ];
            $res          = Api::getInstance()->sendRequest($updateNotice, 'game', 'updatenotice');
            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }
        $res = Api::getInstance()->sendRequest(['id' => 3], 'game', 'sysnotice');
//        $this->assign('notice', $res['data']);
        $this->assign('notice', $res['data']['msgcontent']);
        $this->assign('status', $res['data']['status']);
        return $this->fetch();
    }


}
