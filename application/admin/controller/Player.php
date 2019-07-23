<?php

namespace app\admin\controller;

use app\common\Api;
use app\common\GameLog;
use redis\Redis;
use socket\QuerySocket;
use app\admin\controller\traits\getSocketRoom;


use app\admin\controller\traits\search;


class Player extends Main
{
    use getSocketRoom;
    use search;
    private $socket = null;

    public function __construct()
    {
        parent::__construct();
        $this->socket = new QuerySocket();
    }

    /**
     * 在线玩家
     */
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
            if ($res['data']) {
                foreach ($res['data'] as &$v) {
                    //盈利
                    $v['totalget'] = $v['totalin'] - $v['totalout'];
                    //活跃度
                    $v['huoyue'] = $v['totalin'] != 0 ? round($v['totalwater'] / $v['totalin'], 2) : 0;
                }
                unset($v);
            }
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


    /**
     * 所有玩家
     */
    public function all()
    {
        if ($this->request->isAjax()) {
            $page    = intval(input('page')) ? intval(input('page')) : 1;
            $limit   = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId  = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $roomId  = intval(input('roomid')) ? intval(input('roomid')) : 0;
            $orderby = intval(input('orderby')) ? intval(input('orderby')) : 0;
            $asc     = intval(input('asc')) ? intval(input('asc')) : 0;
            $mobile     = trim(input('mobile')) ? trim(input('mobile')) : '';
            $ipaddr    = trim(input('ipaddr')) ? trim(input('ipaddr')) : '';

            $res = Api::getInstance()->sendRequest([
                'roleid'   => $roleId,
                'roomid'   => $roomId,
                'orderby'  => $orderby,
                'page'     => $page,
                'asc'      => $asc,
                'ipaddr'      => $ipaddr,
                'mobile'      => $mobile,
                'pagesize' => $limit
            ], 'player', 'all');
            if ($res['data']) {
                foreach ($res['data'] as &$v) {
                    //盈利
                    $v['totalget'] = $v['totalin'] - $v['totalout'];
                    //活跃度
                    $v['huoyue'] = $v['totalin'] != 0 ? round($v['totalwater'] / $v['totalin'], 2) : 0;
                }
                unset($v);
            }

            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);

        }
        $selectData = $this->getRoomList();
        $this->assign('selectData', $selectData);
        return $this->fetch();
    }

    /**
     * 游戏日志(玩家列表点击)
     */
    public function gameLog()
    {
        if ($this->request->isAjax()) {
            $page       = intval(input('page')) ? intval(input('page')) : 1;
            $limit      = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId     = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $roomid     = intval(input('roomid')) ? intval(input('roomid')) : 0;
            $strartdate = input('strartdate') ? input('strartdate') : '';
            $enddate    = input('enddate') ? input('enddate') : '';
            $winlost    = intval(input('winlost')) >= 0 ? intval(input('winlost')) : -1;
            $res        = Api::getInstance()->sendRequest([
                'roleid'     => $roleId,
                'roomid'     => $roomid,
                'strartdate' => $strartdate,
                'enddate'    => $enddate,
                'page'       => $page,
                'winlost'    => $winlost,
                'pagesize'   => $limit
            ], 'player', 'game');

            foreach ($res['data'] as &$v) {
                $v['premoney'] = $v['lastmoney'] - $v['money'];
            }
            unset($v);
            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
        }

        $roleId     = intval(input('roleid')) ? intval(input('roleid')) : 0;
        $selectData = $this->getRoomList();
        $this->assign('selectData', $selectData);
        $this->assign('roleid', $roleId);
        return $this->fetch();
    }


    /**
     * 玩家详情(玩家列表点击)
     */
    public function playerDetail()
    {
        if ($this->request->isAjax()) {

        }

        $roleId     = intval(input('roleid')) ? intval(input('roleid')) : 0;
        $selectData = $this->getRoomList();
        $res        = Api::getInstance()->sendRequest([
//            'Id' => '56103750'
'Id' => $roleId
        ], 'player', 'getbank');
        $bankname   = config('site.bank');



        if ($res['data']) {
            $this->assign('username', trim($res['data']['username']));
            $this->assign('roleid', $res['data']['roleid']);
            $this->assign('bankcardno', trim($res['data']['bankcardno']));
            $this->assign('bankname3', trim($res['data']['bankname']));
            $this->assign('mytip', '33');
        } else {
            $this->assign('username', '');
            $this->assign('roleid', $roleId);
            $this->assign('bankcardno', '');
            $this->assign('bankname3', '');
            $this->assign('mytip', '22');
        }

        $this->assign('bankname', $bankname);

        return $this->fetch();
    }

    /**
     * 更新银行卡
     */
    public function updatebank2()
    {

        $request  = $this->request->request();
        $updadata = [
            'roleid'     => input('roleid') ? input('roleid') : '',
            'username'   => input('username') ? input('username') : '',
            'bankcardno' => input('bankcardno') ? input('bankcardno') : '',
            'bankname'   => input('bankname') ? input('bankname') : '',
        ];
        var_dump($updadata);die;
        $res      = Api::getInstance()->sendRequest($updadata, 'player', 'updatebank');
        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);

    }

    public function updatebank($roleid,$username,$bankcardno,$bankname)
    {

        $request  = $this->request->request();
        $updadata = [
            'roleid'     => $roleid,
            'username'   => $username,
            'bankcardno' =>  $bankcardno,
            'bankname'   => $bankname,
        ];
//        var_dump($updadata);die;
        $res      = Api::getInstance()->sendRequest($updadata, 'player', 'updatebank');
        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);

    }


    //更新银行卡
    public function updateSocketBank()
    {
        if ($this->request->isAjax()) {
            $roleid     = input('roleid') ? input('roleid') : '';
            $username   = input('username') ? input('username') : '';
            $bankcardno = input('bankcardno') ? input('bankcardno') : '';
            $len=strlen($bankcardno);
            if($len<13){
                return $this->apiReturn(3, [], '银行卡号少于13为');
            }
            $bankname   = input('bankname') ? input('bankname') : '';
            if (!$username || !$bankcardno || !$bankname || !$roleid) {
                return $this->apiReturn(2, [], '输入不能为空');
            }
            $socket = new QuerySocket();
            $result = $socket->updateBank($roleid, $username, $bankcardno, $bankname);
            GameLog::logData(__METHOD__, $this->request->request(), 1);
            if ($result["iResult"] == 0) {
                ob_clean();
                return $this->apiReturn(0, [], '修改成功!');
            } else {
//                var_dump($result["iResult"]);die;
//                $this->updatebank();
                $this->updatebank($roleid,$username,$bankcardno,$bankname);
                return $this->apiReturn(1, [], '修改成功');
            }

        }

    }
    //查询角色是否锁定
    public function getRoleStatus(){
            $RoleID     = input('roleid') ? input('roleid') : '';
            $socket = new QuerySocket();
            $result = $socket->searchRoleStatus($RoleID);
            //4锁定
            if($result === 3){
//                ob_clean();
                return $this->apiReturn(3, [], '用户未被锁定!');
            }else{
                return $this->apiReturn(4, [], '用户已被锁定');
            }
    }

    //更新角色状态
    public function updateRoleStatus()
    {

        if ($this->request->isAjax()) {
            $roleid     = intval(input('roleid')) ? intval(input('roleid')) : '';
            $day     = intval(input('day')) ? intval(input('day')) : 300;
            $roleStatus=$this->getRoleStatus()->getdata();
            $socket = new QuerySocket();

           if($roleStatus['code']===4){
               //解锁角色
               $result = $socket->unlockRoleStatus($roleid, $day);
               if ($result["iResult"] == 0) {
                   ob_clean();
                   GameLog::logData(__METHOD__, $this->request->request(), 1);
                   return $this->apiReturn(0, [], '角色解锁成功!');
               } else {
                   GameLog::logData(__METHOD__, $this->request->request(), 1);
                   return $this->apiReturn(1, [], '角色解锁失败');
               }
           }else{
               //锁定角色lockRoleStatus

               $result = $socket->lockRoleStatus($roleid, $day);
               if ($result["iResult"] == 0) {
                   ob_clean();
                   GameLog::logData(__METHOD__, $this->request->request(), 1);
                   return $this->apiReturn(0, [], '角色锁定成功!');
               } else {
                   GameLog::logData(__METHOD__, $this->request->request(), 1);
                   return $this->apiReturn(1, [], '角色锁定失败');
               }

           }

        }

    }

    //更新角色状态
    public function updateRoleStatus2()
    {

        if ($this->request->isAjax()) {
            $roleid     = intval(input('roleid')) ? intval(input('roleid')) : '';
            $day     = intval(input('day')) ? intval(input('day')) : 300;
//            $roleStatus=$this->getRoleStatus()->getdata();
//            $roleStatus=json_decode($roleStatus,true);


            $socket = new QuerySocket();
//            $result = $socket->lockRoleStatus($roleid, $day);
            $result = $socket->unlockRoleStatus($roleid);

//           if($roleStatus['code']==4){
//               //解锁角色
//               $result = $socket->unlockRoleStatus($roleid, $day);
//           }else{
//               //锁定角色lockRoleStatus
//
//               $result = $socket->lockRoleStatus($roleid, $day);
//               if ($result["iResult"] == 0) {
//                   ob_clean();
//                   return $this->apiReturn(0, [], '角色锁定成功!');
//               } else {
//                   return $this->apiReturn(1, [], '角色锁定失败');
//               }
//
//           }
            if ($result["iResult"] == 0) {
                ob_clean();
                return $this->apiReturn(0, [], '角色锁定成功!');
            } else {
                return $this->apiReturn(1, [], '角色锁定失败');
            }
            GameLog::logData(__METHOD__, $this->request->request(), 1);


        }

    }

    //设置房间概率信息
    public function setSocketRoomRate()
    {
        $roomid  = intval(input('roomid')) ? intval(input('roomid')) : 0;
        $init    = intval(input('init')) ? intval(input('init')) : 0;
        $current = intval(input('current')) ? intval(input('current')) : 0;

        if (abs($init) > 2000000 || abs($current) > 2000000) {
            return $this->apiReturn(1, [], '库存值不能超过绝对值200万');
        }
        $roomsData = $this->getSocketRoom($this->socket, $roomid);

        $this->socket->setRoom($roomid, $roomsData['nCtrlRatio'], $init, $current, $roomsData['szStorageRatio']);
        GameLog::logData(__METHOD__, $this->request->request(), 1);
        ob_clean();
        return $this->apiReturn(0, [], '修改成功');
    }


    /**
     * Notes: 游戏日志（单独菜单）
     * @return mixed
     */
    public function gamelog2()
    {


        if ($this->request->isAjax()) {
//            var_dump(3);die;

            $page       = intval(input('page')) ? intval(input('page')) : 1;
            $limit      = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId     = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $roomid     = intval(input('roomid')) ? intval(input('roomid')) : 0;
            $strartdate = input('strartdate') ? input('strartdate') : '';
            $enddate    = input('enddate') ? input('enddate') : '';
            $winlost    = intval(input('winlost')) >= 0 ? intval(input('winlost')) : -1;
            $res        = Api::getInstance()->sendRequest([
                'roleid'     => $roleId,
                'roomid'     => $roomid,
                'strartdate' => $strartdate,
                'enddate'    => $enddate,
                'page'       => $page,
                'winlost'    => $winlost,
                'pagesize'   => $limit
            ], 'player', 'game');

            foreach ($res['data']['list'] as &$v) {
                $v['premoney'] = $v['lastmoney'] - $v['money'];
            }
            unset($v);

            return $this->apiReturn($res['code'], $res['data']['list'], $res['message'], $res['total'],['alltotal'=>isset($res['data']['sum'])?$res['data']['sum']:0]);

        }
        $selectData = $this->getRoomList();
        $this->assign('selectData', $selectData);
        return $this->fetch();
    }

    /**
     * 金币日志
     */
    public function coinLog()
    {
        $changeType = config('site.bank_change_type');
        if ($this->request->isAjax()) {
            $page   = intval(input('page')) ? intval(input('page')) : 1;
            $limit  = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $res    = Api::getInstance()->sendRequest([
                'roleid'     => $roleId,
                'strartdate' => '',
                'enddate'    => '',
                'page'       => $page,
                'changetype' => 0,
                'pagesize'   => $limit

            ], 'player', 'coin');

            foreach ($res['data']['list'] as &$v) {
                if ($v['changetype'] == 1 || $v['changetype'] == 3 || $v['changetype'] == 12) {
                    $v['premoney'] = $v['balance'] + $v['changemoney'];
                } else {
                    $v['premoney'] = $v['balance'] - $v['changemoney'];
                }
                foreach ($changeType as $k2 => $v2) {
                    if ($v['changetype'] == $k2) {
                        $v['changename'] = $v2;
                        break;
                    }
                }
            }
            unset($v);
            return $this->apiReturn($res['code'], $res['data']['list'], $res['message'], $res['total']);
        }

        $roleId = intval(input('roleid')) ? intval(input('roleid')) : 0;
        $this->assign('roleid', $roleId);
        $this->assign('changeType', $changeType);
        return $this->fetch();
    }

    /**
     * Notes: 金币日志（单独菜单）
     * @return mixed
     */
    public function coinlog2()
    {
        $changeType = config('site.bank_change_type');
        if ($this->request->isAjax()) {
            $page       = intval(input('page')) ? intval(input('page')) : 1;
            $limit      = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId     = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $strartdate = input('strartdate') ? input('strartdate') : '';
            $enddate    = input('enddate') ? input('enddate') : '';
            $changetype = intval(input('changetype')) ? intval(input('changetype')) : 0;
            $res        = Api::getInstance()->sendRequest([
                'roleid'     => $roleId,
                'strartdate' => $strartdate,
                'enddate'    => $enddate,
                'page'       => $page,
                'changetype' => $changetype,
                'pagesize'   => $limit
            ], 'player', 'coin');

            foreach ($res['data']['list'] as &$v) {
                if ($v['changetype'] == 1 || $v['changetype'] == 3 || $v['changetype'] == 12) {
                    $v['premoney'] = $v['balance'] + $v['changemoney'];
                } else {
                    $v['premoney'] = $v['balance'] - $v['changemoney'];
                }

                foreach ($changeType as $k2 => $v2) {
                    if ($v['changetype'] == $k2) {
                        $v['changename'] = $v2;
                        break;
                    }
                }
            }
            unset($v);
            return $this->apiReturn($res['code'], $res['data']['list'], $res['message'], $res['total'], $res['data']['sum']);
        }


        $this->assign('changeType', $changeType);
        return $this->fetch();
    }


    /**
     * Notes: 超级玩家列表
     * @return mixed
     */
    public function super()
    {
        if ($this->request->isAjax()) {
            $page   = intval(input('page')) ? intval(input('page')) : 1;
            $limit  = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId = intval(input('roleid')) ? intval(input('roleid')) : 0;

            $res = Api::getInstance()->sendRequest(['roleid' => $roleId, 'page' => $page, 'pagesize' => $limit], 'SuperUser', 'list');
            return $this->apiReturn($res['code'], $res['data']['ResultData']['list'], $res['message'], $res['data']['total'],
                [
                    $res['data']['ResultData']['totalinsum'],
                    $res['data']['ResultData']['totaloutsum'],
                ]);
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
            $request  = $this->request->request();
            $validate = validate('Player');
            $validate->scene('addSuper');
            if (!$validate->check($request)) {
                return $this->apiReturn(1, [], $validate->getError());
            }
            $socket = new QuerySocket();
            $res1   = $socket->setSuperPlayer($request['roleid'], $request['rate']);
            if ($res1['iResult'] == 0) {
                $res = Api::getInstance()->sendRequest(['roleid' => $request['roleid'], 'rate' => $request['rate']], 'SuperUser', 'add');
                //log记录
                GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
                return $this->apiReturn($res['code'], $res['data'], $res['message']);
            } else {
                GameLog::logData(__METHOD__, $request, 0, $res1);
                return $this->apiReturn(1, [], '添加失败');
            }

        }
        return $this->fetch();
    }

    /**
     * Notes: 编辑超级玩家
     * @return mixed
     */
    public function editSuper()
    {
        if ($this->request->isAjax()) {
            $request  = $this->request->request();
            $validate = validate('Player');
            $validate->scene('editSuper');
            if (!$validate->check($request)) {
                return $this->apiReturn(1, [], $validate->getError());
            }
            $res = Api::getInstance()->sendRequest(['roleid' => $request['roleid'], 'rate' => $request['rate']], 'SuperUser', 'update');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }

        $roleid = input('roleid');
        $rate   = intval(input('rate')) ? intval(input('rate')) : 0;
        $this->assign('roleid', $roleid);
        $this->assign('rate', $rate);
        return $this->fetch();
    }

    /**
     * Notes: 删除超级玩家
     * @return mixed
     */
    public function deleteSuper()
    {
        $request  = $this->request->request();
        $validate = validate('Player');
        $validate->scene('deleteSuper');
        if (!$validate->check($request)) {
            return $this->apiReturn(1, [], $validate->getError());
        }
        $res = Api::getInstance()->sendRequest(['roleid' => $request['roleid']], 'SuperUser', 'delete');

        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return $this->apiReturn($res['code'], $res['data'], $res['message']);
    }

    /**
     * 给玩家扣款
     */
    public function cutmoney()
    {
        if ($this->request->isAjax()) {
            $page    = intval(input('page')) ? intval(input('page')) : 1;
            $limit   = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId  = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $start   = input('start') ? input('start') : '';
            $end     = input('end') ? input('end') : '';
            $classid = input('classid') ? input('classid') : -1;



            $data = ['page' => $page, 'pagesize' => $limit];
            $data['typeid'] = 1;
            if ($roleId) {
                $data['roleid'] = $roleId;
            }
            if ($classid && $classid != -1) {
                $data['classid'] = $classid;
            }
            if ($start) {
                $data['starttime'] = $start;
                if ($end) {
                    $data['endtime'] = $end;
                }
            }
            $res = Api::getInstance()->sendRequest($data, 'charge', 'list');

            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
        }
        return $this->fetch();
    }

    /**
     * 向玩家扣款
     */
    public function addCutmoney()
    {
        if ($this->request->isAjax()) {
            $request  = $this->request->request();
//            $validate = validate('Player');
//            $validate->scene('addTransfer');
//            if (!$validate->check($request)) {
//                return $this->apiReturn(1, [], $validate->getError());
//            }

//            if($request['totalmoney']<0){
//                return $this->apiReturn(3, [], '转账金额必须为正数');
//            }
            if($request['totalmoney']<0){
                return $this->apiReturn(3, [], '扣款金额必须为正数');
            }

            //加锁
            $key = 'lock_addtransfer_' . $request['roleid'];
            if (!Redis::getInstance()->lock($key)) {
                return $this->apiReturn(2, [], '请勿重复操作');
            }

            $data   = [
                'roleid'     => $request['roleid'],
//                'classid'    => $request['classid'],
                'classid'    => 4,
                'totalmoney' => $request['totalmoney'],
                'uid'        => session('userid'),
                'adduser'    => session('username'),
                'typeid'    => 1,
                'descript'   => $request['descript'] ? $request['descript'] : ''
            ];
            $socket = new QuerySocket();
            $res    = $socket->addRoleMoney($data['roleid'], $data['totalmoney'] * 1000,1);
//            $res['iResult'] = 5;

            if ($res['iResult'] == 0) {
                $res = Api::getInstance()->sendRequest($data, 'charge', 'add');
                //log记录
                GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
                Redis::getInstance()->rm($key);
                return $this->apiReturn($res['code'], $res['data'], $res['message']);
            }else if($res['iResult'] == 5){

                return $this->apiReturn(3, [], '扣款失败账户余额不足！');
            }
            Redis::getInstance()->rm($key);
            return $this->apiReturn(3, [], '添加失败');
        }
        return $this->fetch();
    }


    /**
     * 向玩家转账
     */
    public function transfer()
    {
        if ($this->request->isAjax()) {
            $page    = intval(input('page')) ? intval(input('page')) : 1;
            $limit   = intval(input('limit')) ? intval(input('limit')) : 10;
            $roleId  = intval(input('roleid')) ? intval(input('roleid')) : 0;
            $start   = input('start') ? input('start') : '';
            $end     = input('end') ? input('end') : '';
            $classid = input('classid') ? input('classid') : -1;


            $data = ['page' => $page, 'pagesize' => $limit];
            $data['typeid'] = 0;
            if ($roleId) {
                $data['roleid'] = $roleId;
            }
            if ($classid && $classid != -1) {
                $data['classid'] = $classid;
            }
            if ($start) {
                $data['starttime'] = $start;
                if ($end) {
                    $data['endtime'] = $end;
                }
            }
            $res = Api::getInstance()->sendRequest($data, 'charge', 'list');
            return $this->apiReturn($res['code'], $res['data'], $res['message'], $res['total']);
        }
        return $this->fetch();
    }

    /**
     * 向玩家转账
     */
    public function addTransfer()
    {
        if ($this->request->isAjax()) {
            $request  = $this->request->request();
            $validate = validate('Player');
            $validate->scene('addTransfer');
            if (!$validate->check($request)) {
                return $this->apiReturn(1, [], $validate->getError());
            }

            if($request['totalmoney']<0){
                return $this->apiReturn(3, [], '转账金额必须为正数');
            }

            //加锁
            $key = 'lock_addtransfer_' . $request['roleid'];
            if (!Redis::getInstance()->lock($key)) {
                return $this->apiReturn(2, [], '请勿重复操作');
            }

            $data   = [
                'roleid'     => $request['roleid'],
                'classid'    => $request['classid'],
                'totalmoney' => $request['totalmoney'],
                'uid'        => session('userid'),
                'adduser'    => session('username'),
                'typeid'    => 0,
                'descript'   => $request['descript'] ? $request['descript'] : ''
            ];
            $socket = new QuerySocket();
//            $res    = $socket->addRoleMoney($data['roleid'], $data['totalmoney'] * 1000);
            $res    = $socket->addRoleMoney($data['roleid'], $data['totalmoney'] * 1000,0);
            if ($res['iResult'] == 0) {

                $res = Api::getInstance()->sendRequest($data, 'charge', 'add');
                //log记录
                GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
                Redis::getInstance()->rm($key);
                return $this->apiReturn($res['code'], $res['data'], $res['message']);
            }
            Redis::getInstance()->rm($key);
            return $this->apiReturn(3, [], '添加失败');
        }
        return $this->fetch();
    }


    //玩家重置密码
    public function resetPwd()
    {
        $request  = $this->request->request();
        $roleId   = intval(input('roleid')) ? intval(input('roleid')) : 0;
        $pwd      = input('pwd') ? input('pwd') : '';
        $checkPwd = input('pwd2') ? input('pwd2') : '';

        if (!$roleId || !$pwd || !$checkPwd) {
            return $this->apiReturn(1, [], '必填项不能为空');
        }
        if ($pwd != $checkPwd) {
            return $this->apiReturn(2, [], '两次输入密码不一致');
        }

//        $res = Api::getInstance()->sendRequest([
//            'roleid'   => $roleId,
//            'password' => $pwd
//        ], 'player', 'updatepwd');
//        if ($res['code'] == 0) {
//
//        }
        $socket = new QuerySocket();
        $res = $socket->setPlayerPwd($roleId, $pwd);

        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['iResult']) && $res['iResult'] == 0) ? 1 : 0, $res);
        if (isset($res['iResult']) && $res['iResult'] == 0) {
            return $this->apiReturn($res['iResult'], [], '修改成功');
        } else {
            return $this->apiReturn(1, [], '修改失败');
        }

    }
}
