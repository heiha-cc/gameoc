<?php

namespace app\admin\controller;

use app\common\Api;
use think\Request;
use app\common\GameLog;
use socket\QuerySocket;
use socket\MasterBLL;

use app\admin\controller\traits\search;

use app\admin\controller\traits\getSocketRoom;



class Yunwei extends Main
{
    use getSocketRoom;
    use search;
    private $socket = null;

    public function __construct()
    {
        parent::__construct();
        $this->socket = new QuerySocket();
    }
    //房间配置
    public function roomctrl()
    {
        if ($this->request->isAjax()) {
            $page     = intval(input('page')) ? intval(input('page')) : 1;
            $limit    = intval(input('limit')) ? intval(input('limit')) : 10;
            $kindid   = input('kindid') ? input('kindid') : 0;
            $serverip = input('serverip') ? input('serverip') : '';
            $roomname = input('roomname') ? input('roomname') : '';

            $data = [
                'serverip' => $serverip,
                'roomname' => $roomname,
                'kindid'   => $kindid,
                'page'     => $page,
                'pagesize' => $limit
            ];
            $res  = Api::getInstance()->sendRequest($data, 'room', 'roomlist');

            return $this->apiReturn($res['code'], isset($res['data']['ResultData']) ? $res['data']['ResultData'] : [], $res['message'], isset($res['data']['total']) ? $res['data']['total'] : 0);
        }
        $kindList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'kindlist');
        $this->assign('kindlist', $kindList['data']);
        return $this->fetch();
    }


    //添加房间
    public function addroom()
    {
        if ($this->request->isAjax()) {
            $RoomID               = input('RoomID');
            $KindID               = input('KindID');
            $RoomType             = input('RoomType');
            $LuckyEggTaxRate      = input('LuckyEggTaxRate');
            $ExpMoney             = input('ExpMoney');
            $ServerID             = input('ServerID');
            $RoomName             = input('RoomName');
            $MaxTableCount        = input('MaxTableCount');
            $MaxPlayerCount       = input('MaxPlayerCount');
            $EnterPrompt          = input('EnterPrompt');
            $RulePrompt           = input('RulePrompt');
            $AllowLook            = input('AllowLook');
            $StartMode            = input('StartMode');
            $StartForMinUser      = input('StartForMinUser');
            $CanJoinWhenPlaying   = input('CanJoinWhenPlaying');
            $MaxLookUser          = input('MaxLookUser');
            $AutoRun              = input('AutoRun');
            $MaxSitTime           = input('MaxSitTime');
            $SetFlag              = input('SetFlag');
            $CustomField          = input('CustomField');
            $RoomWealthMin        = input('RoomWealthMin');
            $RoomNumMax1          = input('RoomNumMax1');
            $RobotJoinWhenPlaying = input('RobotJoinWhenPlaying');
            $CellScore            = input('CellScore');
            $CellScoreType        = input('CellScoreType');
            $RoomNumMax2          = input('RoomNumMax2');
            $TableWealthMin       = input('TableWealthMin');
            $TableSchemeId        = input('TableSchemeId');
            $data                 = [
                'roomid'               => $RoomID,
                'SetFlag'              => $SetFlag,
                'RobotJoinWhenPlaying' => $RobotJoinWhenPlaying,
                'KindID'               => $KindID,
                'RoomType'             => $RoomType,
                'ServerID'             => $ServerID,
                'MaxPlayerCount'       => $MaxPlayerCount,
                'MaxTableCount'        => $MaxTableCount,
                'TableSchemeId'        => $TableSchemeId,
                'RoomName'             => $RoomName,
                'EnterPrompt'          => $EnterPrompt,
                'RulePrompt'           => $RulePrompt,
                'CanJoinWhenPlaying'   => $CanJoinWhenPlaying,
                'AllowLook'            => $AllowLook,
                'RoomWealthMin'        => $RoomWealthMin,
                'TableWealthMin'       => $TableWealthMin,
                'AutoRun'              => $AutoRun,
                'StartMode'            => $StartMode,
                'StartForMinUser'      => $StartForMinUser,
                'MaxLookUser'          => $MaxLookUser,
                'CellScoreType'        => $CellScoreType,
                'CellScore'            => $CellScore,
                'MaxSitTime'           => $MaxSitTime,
                'CustomField'          => $CustomField,
                'ExpMoney'             => $ExpMoney,
                'LuckyEggTaxRate'      => $LuckyEggTaxRate,
                'RoomNumMax1'          => $RoomNumMax1,
                'RoomNumMax2'          => $RoomNumMax2,

                'MaxStartTime'    => 0,
                'MaxFreeTime'     => 0,
                'AllowChatOption' => 0,
                'PlayCountMax'    => 0,
                'FleeCountMax'    => 0,
                'RoleLevelMin'    => 0,
                'MatchTypeID'     => 0,
                'MatchModel'      => 0,
                'MaxMatchs'       => 0,
                'MaxMatchNumber'  => 0,
                'MatchStartTime'  => '',
                'MatchEndTime'    => '',
                'MatchTimeStatus' => 0,
                'GetPrizeType'    => 0,
                'GetStatus'       => 0,
                'SpIDList'        => '',
            ];


            $res = Api::getInstance()->sendRequest($data, 'room', 'addroom');
            return json(['code' => $res['code']]);
        }
        $tableList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'tablelist');
        $this->assign('tablelist', $tableList['data']);
        $kindList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'kindlist');
        $this->assign('kindlist', $kindList['data']);
        $serverList = Api::getInstance()->sendRequest(['id' => 1], 'room', 'serverlist');
        $this->assign('serverlist', $serverList['data']);
        return $this->fetch();
    }


    //删除房间
    public function deleteroom()
    {
        $roomId = intval(input('RoomID'));
        $data   = ['code' => 0, 'msg' => ''];
        if (!$roomId) {
            $data['code'] = 1;
            $data['msg']  = '请选择要删除的房间';
            return json($data);
        }
        $del = ['id' => $roomId];
        $res = Api::getInstance()->sendRequest($del, 'room', 'delroom');
        GameLog::logData(__METHOD__, $roomId, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return json(['code' => $res['code'], 'msg' => $res['message']]);
    }


    //编辑房间
    public function geteditroom()
    {
        $roomId = intval(input('roomid'));
        $res    = Api::getInstance()->sendRequest(['id' => $roomId], 'room', 'roominfo');
        $data   = [
            'res' => $res['data'],
        ];
        return json(['code' => 0, 'data' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
    }


    public function editroom()
    {
        $data      = input('data');
        $data      = json_decode(js_unescape($data), true);
        $tableList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'tablelist');
        $this->assign('tablelist', $tableList['data']);
        $kindList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'kindlist');
        $this->assign('kindlist', $kindList['data']);
        $serverList = Api::getInstance()->sendRequest(['id' => 1], 'room', 'serverlist');
        $this->assign('serverlist', $serverList['data']);

        //$roomType = 2; //金币房间   0000010
        $cheat    = 16; //防作弊    0010000
        $hundred  = 64; //百人房间  1000000
        $exp      = 8;  //体验房    0001000
        $ischeat = $ishundred = $isexp = 0;
        //位运算判断
        if (($data['res']['RoomType']&$cheat) > 0) {
            $ischeat = 1;
        }
        if (($data['res']['RoomType']&$hundred) > 0) {
            $ishundred = 1;
        }
        if (($data['res']['RoomType']&$exp) > 0) {
            $isexp = 1;
        }

        $data['res']['isCheat'] = $ischeat;
        $data['res']['isHundred'] = $ishundred;
        $data['res']['isExp'] = $isexp;

        $this->assign('roomdata', $data['res']);
        ob_clean();
        return $this->fetch();
    }


    //复制房间
    public function copyroom()
    {
        $data      = input('data');
        $data      = json_decode(js_unescape($data), true);
        $tableList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'tablelist');
        $this->assign('tablelist', $tableList['data']);
        $kindList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'kindlist');
        $this->assign('kindlist', $kindList['data']);
        $serverList = Api::getInstance()->sendRequest(['id' => 1], 'room', 'serverlist');
        $this->assign('serverlist', $serverList['data']);

        //$roomType = 2; //金币房间   0000010
        $cheat    = 16; //防作弊    0010000
        $hundred  = 64; //百人房间  1000000
        $exp      = 8;  //体验房    0001000
        $ischeat = $ishundred = $isexp = 0;
        //位运算判断
        if (($data['res']['RoomType']&$cheat) > 0) {
            $ischeat = 1;
        }
        if (($data['res']['RoomType']&$hundred) > 0) {
            $ishundred = 1;
        }
        if (($data['res']['RoomType']&$exp) > 0) {
            $isexp = 1;
        }

        $data['res']['isCheat'] = $ischeat;
        $data['res']['isHundred'] = $ishundred;
        $data['res']['isExp'] = $isexp;

        $this->assign('roomdata', $data['res']);
        ob_clean();
        return $this->fetch();
    }

    //获取游戏信息
    public function getKindInfo()
    {
        $kindid = intval(input('kindid')) ? intval(input('kindid')) : 0;
        $data   = [
            'code' => 0,
            'msg'  => '',
            'data' => ''
        ];
        if (!$kindid) {
            $data['code'] = 1;
            $data['msg']  = '请选择游戏';
            return json($data);
        }
        $kindInfo     = Api::getInstance()->sendRequest(['id' => $kindid], 'room', 'kindinfo');
        $data['data'] = isset($kindInfo['data'][0]['CustomField']) ? $kindInfo['data'][0]['CustomField'] : '';
        return json($data);
    }

    /**
     * 房间机器人管理
     */
    public function robotroom()
    {
        if ($this->request->isAjax()) {
            $page  = intval(input('page')) ? intval(input('page')) : 1;
            $limit = intval(input('limit')) ? intval(input('limit')) : 10;
            $data  = ['page' => $page, 'pagesize' => $limit];
            $res   = Api::getInstance()->sendRequest($data, 'room', 'roomrobot');
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
     * Notes: 新增房间机器人管理
     * @return mixed
     */
//    public function addSuper()
    public function addRobot()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();
            $insert  = [
                'roomid'           => intval($request['roomid']) ? intval($request['roomid']) : 0,
                'maxcount'         => intval($request['maxcount']) ? intval($request['maxcount']) : 0,
                'robotwinweighted' => intval($request['robotwinweighted']) ? intval($request['robotwinweighted']) : '',
                'robotwinmoney'    => intval($request['robotwinmoney']) ? intval($request['robotwinmoney']) : 0,
                'servicetables'    => intval($request['servicetables']) ? intval($request['servicetables']) : 0,

                'addwinpre'     => intval($request['victory']) ? intval($request['victory']) : 0,
                'mintakescore'  => intval($request['minnum']) ? intval($request['minnum']) : 0,
                'maxtakescore'  => intval($request['maxnum']) ? intval($request['maxnum']) : 0,
                'minplaydraw'   => intval($request['mingame']) ? intval($request['mingame']) : 0,
                'maxplaydraw'   => intval($request['maxgame']) ? intval($request['maxgame']) : 0,
                'minreposetime' => intval($request['mintime']) ? intval($request['mintime']) : 0,
                'maxreposetime' => intval($request['maxtime']) ? intval($request['maxtime']) : 0,
                'minleavepre'   => intval($request['win']) ? intval($request['win']) : 0,
                'maxleavepre'   => intval($request['lost']) ? intval($request['lost']) : 0,
            ];
//            var_dump($insert);die;

            $res = Api::getInstance()->sendRequest($insert, 'room', 'addroomrobot');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }
        $selectData = $this->getRoomList();
        $this->assign('selectData', $selectData);
        return $this->fetch();

    }

    /**
     * Notes: 编辑房间机器人管理
     * @return mixed
     */
//    public function editSuper()
    public function editRobot()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();
            $data    = [
                'roomid'           => intval($request['roomid']) ? intval($request['roomid']) : 0,
                'maxcount'         => intval($request['maxcount']) ? intval($request['maxcount']) : 0,
                'robotwinweighted' => intval($request['robotwinweighted']) ? intval($request['robotwinweighted']) : '',
                'robotwinmoney'    => intval($request['robotwinmoney']) ? intval($request['robotwinmoney']) : 0,
                'servicetables'    => intval($request['servicetables']) ? intval($request['servicetables']) : 0,

                'addwinpre'     => intval($request['victory']) ? intval($request['victory']) : 0,
                'mintakescore'  => intval($request['minnum']) ? intval($request['minnum']) : 0,
                'maxtakescore'  => intval($request['maxnum']) ? intval($request['maxnum']) : 0,
                'minplaydraw'   => intval($request['mingame']) ? intval($request['mingame']) : 0,
                'maxplaydraw'   => intval($request['maxgame']) ? intval($request['maxgame']) : 0,
                'minreposetime' => intval($request['mintime']) ? intval($request['mintime']) : 0,
                'maxreposetime' => intval($request['maxtime']) ? intval($request['maxtime']) : 0,
                'minleavepre'   => intval($request['win']) ? intval($request['win']) : 0,
                'maxleavepre'   => intval($request['lost']) ? intval($request['lost']) : 0,

            ];
//            var_dump($data);die;
            $res = Api::getInstance()->sendRequest($data, 'room', 'addroomrobot');

            //log记录
            GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }

        $roomid = intval(input('roomid'));
        $res    = Api::getInstance()->sendRequest(['id' => $roomid], 'room', 'roomrobotinfo');
        $this->assign('roomid', $res['data']['roomid']);
        $this->assign('maxcount', $res['data']['maxcount']);
        $this->assign('robotwinweighted', $res['data']['robotwinweighted']);
        $this->assign('robotwinmoney', $res['data']['robotwinmoney']);
        $this->assign('servicetables', $res['data']['servicetables']);
        $this->assign('addwinpre', $res['data']['addwinpre']);
        $this->assign('mintakescore', $res['data']['mintakescore']);
        $this->assign('maxtakescore', $res['data']['maxtakescore']);
        $this->assign('minplaydraw', $res['data']['minplaydraw']);
        $this->assign('maxplaydraw', $res['data']['maxplaydraw']);
        $this->assign('minreposetime', $res['data']['minreposetime']);
        $this->assign('maxreposetime', $res['data']['maxreposetime']);
        $this->assign('minleavepre', $res['data']['minleavepre']);
        $this->assign('maxleavepre', $res['data']['maxleavepre']);
        return $this->fetch();
    }

    /**
     * 激活房间机器人。
     */
    public function activeRoomRobot()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();
            $data    = [
                'roomid' => intval($request['roomid']),

            ];
            $socket  = new QuerySocket();
            $res     = $socket->DCActiveRoomRobot($data['roomid']);
            GameLog::logData(__METHOD__, $this->request->request(), 1);
            if (isset($res['iResult'])) {
//                var_dump(44);die;
//                $res = Api::getInstance()->sendRequest($data, 'charge', 'add');
                //log记录
//                GameLog::logData(__METHOD__, $this->request->request(), 1);
                return $this->apiReturn(0, [], '激活成功');

            }
//            Redis::getInstance()->rm($key);
            return $this->apiReturn(3, [], '激活失败');
        }
        return $this->fetch();
    }

    /**
     * Notes: 删除房间机器人
     * @return mixed
     */
    public function deleteRobot()
    {
        $request = $this->request->request();
        $res     = Api::getInstance()->sendRequest(['id' => $request['roomid']], 'room', 'delroomrobot');

        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return $this->apiReturn($res['code'], $res['data'], $res['message']);
    }


    /**
     * Notes:机器人账号管理
     */
    public function robotuser()
    {
        if ($this->request->isAjax()) {
            $page     = intval(input('page')) ? intval(input('page')) : 1;
            $limit    = intval(input('limit')) ? intval(input('limit')) : 10;
            $userid   = input('userid') ? input('userid') : 0;

            $data = [
                'userid'   => $userid,
                'page'     => $page,
                'pagesize' => $limit
            ];
            $res  = Api::getInstance()->sendRequest($data, 'room', 'robotuser');
            if ($res['data']) {
                foreach ($res['data'] as &$v) {
                    $str = '';
                    if (($v['servicetime']&1)) {
                        $str .=' 0';
                    }
                    if (($v['servicetime']&2)) {
                        $str .=' 1';
                    }
                    if (($v['servicetime']&4)) {
                        $str .=' 2';
                    }
                    if (($v['servicetime']&8)) {
                        $str .=' 3';
                    }
                    if (($v['servicetime']&16)) {
                        $str .=' 4';
                    }
                    if (($v['servicetime']&32)) {
                        $str .=' 5';
                    }
                    if (($v['servicetime']&64)) {
                        $str .=' 6';
                    }
                    if (($v['servicetime']&128)) {
                        $str .=' 7';
                    }
                    if (($v['servicetime']&256)) {
                        $str .=' 8';
                    }
                    if (($v['servicetime']&512)) {
                        $str .=' 9';
                    }
                    if (($v['servicetime']&1024)) {
                        $str .=' 10';
                    }
                    if (($v['servicetime']&2048)) {
                        $str .=' 11';
                    }
                    if (($v['servicetime']&4096)) {
                        $str .=' 12';
                    }
                    if (($v['servicetime']&8192)) {
                        $str .=' 13';
                    }
                    if (($v['servicetime']&16384)) {
                        $str .=' 14';
                    }
                    if (($v['servicetime']&32768)) {
                        $str .=' 15';
                    }
                    if (($v['servicetime']&65536)) {
                        $str .=' 16';
                    }
                    if (($v['servicetime']&131072)) {
                        $str .=' 17';
                    }
                    if (($v['servicetime']&262144)) {
                        $str .=' 18';
                    }
                    if (($v['servicetime']&524288)) {
                        $str .=' 19';
                    }
                    if (($v['servicetime']&1048576)) {
                        $str .=' 20';
                    }
                    if (($v['servicetime']&2097152)) {
                        $str .=' 21';
                    }
                    if (($v['servicetime']&4194304)) {
                        $str .=' 22';
                    }
                    if (($v['servicetime']&8388608)) {
                        $str .=' 23';
                    }

                    $str = trim($str);
                    $v['str'] = $str;

                    $type = '';
                    if ($v['servicegender'] & 1) {
                        $type .= ' 相互模拟';
                    }
                    if ($v['servicegender'] & 2) {
                        $type .= ' 被动陪打';
                    }
                    if ($v['servicegender'] & 4) {
                        $type .= ' 主动陪玩';
                    }
                    $v['type'] = trim($type);

                }
                unset($v);
            }

            return $this->apiReturn($res['code'], isset($res['data']) ? $res['data'] : [], $res['message'], isset($res['total']) ? $res['total'] : 0);
        }

        return $this->fetch();
    }


    /**
     * Notes:删除机器人账号
     */
    public function deleterobotuser()
    {
        $request = $this->request->request();
        $res     = Api::getInstance()->sendRequest(['id' => $request['userid']], 'room', 'delrobotuser');
        //log记录
        GameLog::logData(__METHOD__, $request, (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
        return $this->apiReturn($res['code'], $res['data'], $res['message']);
    }

    /**
     * Notes:编辑机器人账号
     */
    public function editrobotuser()
    {
        $this->assign('userid', input('userid'));
        $this->assign('servicetime', input('servicetime'));
        $this->assign('servicegender', input('servicegender'));
        return $this->fetch();
    }

    /**
     * Notes:新增/修改机器人账号
     */
    public function addrobotuser()
    {
        if ($this->request->isAjax()) {
            $userid = intval(input('userid'));
            $servicetime = input('servicetime');
            $servicegender = input('servicegender');

            $res     = Api::getInstance()->sendRequest([
                'userid' => $userid,
                'servicetime' => $servicetime,
                'servicegender' => $servicegender,
                'roomid' => 0,
                'mintakescore' => 0,
                'maxtakescore' => 0,
                'minplaydraw' => 0,
                'maxplaydraw' => 0,
                'minreposetime' => 0,
                'maxreposetime' => 0,
            ], 'room', 'addrotbotuser');
            GameLog::logData(__METHOD__, $this->request->request(), (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }

        return $this->fetch();
    }

    //批量修改机器人账号
    public function updateallrotbot()
    {
        if ($this->request->isAjax()) {
            $servicetime = input('servicetime');
            $servicegender = input('servicegender');

            $res     = Api::getInstance()->sendRequest([
                'servicetime' => $servicetime,
                'servicegender' => $servicegender,
                'roomid' => 0,
                'mintakescore' => 0,
                'maxtakescore' => 0,
                'minplaydraw' => 0,
                'maxplaydraw' => 0,
                'minreposetime' => 0,
                'maxreposetime' => 0,
            ], 'room', 'updateallrotbot');
            GameLog::logData(__METHOD__, $this->request->request(), (isset($res['code']) && $res['code'] == 0) ? 1 : 0, $res);
            return $this->apiReturn($res['code'], $res['data'], $res['message']);
        }

        return $this->fetch();
    }

    /**
     * Notes: 激活房间机器人
     * @return mixed
     */
    public function robotroomactive()
    {

        if ($this->request->isAjax()) {
            $pageSize  = intval(input('limit')) ? intval(input('limit')) : 10;
            $limit = intval(input('page')) ? intval(input('page')) : 1;
            $request = $this->request->request();
            $socket  = new QuerySocket();
            $arrResult     = $socket->getRoomRobotInfo($limit,$pageSize);
            $iRecordsCount = ($arrResult['iTotalPage']-1)*$pageSize + ($arrResult['iCurPage'] == $arrResult['iTotalPage'] ? $arrResult['iRoomCount']:$pageSize);
            $showData = array();
            $i = 0;
            foreach($arrResult['RoomRobotInfoList'] as $key => $val){
                $arrResult['RoomRobotInfoList'][$key]['UpdateTime'] = date("Y-m-d h:i:s",$val['UpdateTime']);
                $roomRobot    = Api::getInstance()->sendRequest(['id' => $val['RoomID']], 'room', 'activeroom');
                if($roomRobot){
                    $showData[$i] = $arrResult['RoomRobotInfoList'][$key];
                    $showData[$i]['MaxCount'] = $roomRobot['data']['maxcount'];
                    $showData[$i]['RobotWinWeighted'] = $roomRobot['data']['robotwinweighted'];
                    $showData[$i]['RobotNeedWinMoney'] = $roomRobot['data']['robotneedwinmoney'];
                    $showData[$i]['RoomName'] = $roomRobot['data']['roomname'];
                    $i++;
                }else{
                    $showData[$i] = $arrResult['RoomRobotInfoList'][$key];
                    $showData[$i]['MaxCount'] = 0;
                    $showData[$i]['RobotWinWeighted'] = 0;
                    $showData[$i]['RobotNeedWinMoney'] = 0;
                    $showData[$i]['RoomName'] = $roomRobot['data']['roomname'];
                    $i++;
                }

            }
            $arrResult['RoomRobotInfoList'] = $showData;
            return $this->apiReturn(0, $arrResult['RoomRobotInfoList'], 0, $iRecordsCount);
        }
        return $this->fetch();
    }

    /**
     * Notes: 机器人数量
     * @return mixed
     */

    //玩家牌型
    public function robotnum()
    {
        $roomlist = Api::getInstance()->sendRequest(['id' => 0], 'room', 'kind');
        if ($this->request->isAjax()) {
            $roomId = intval(input('roomId')) ? intval(input('roomId')) : 0;
//            $nRoomId = intval(input('roomId')) ? intval(input('roomId')) : 0;
            $page = intval(input('page')) ? intval(input('page')) : 1;
            $limit = intval(input('limit')) ? intval(input('limit')) : 15;

//            $res = $this->socket->getProfitPercent($roomId);
            $res = $this->socket->getRobotNum($roomId);

            if ($res) {
                if ($roomlist['data']) {
                    foreach ($res as &$v) {
                        foreach ($roomlist['data'] as $v2) {
                            if ($v['nRoomId'] == $v2['roomid']) {
                                $v['roomname'] = $v2['roomname'];
                            }
                        }

                        $v['lTotalRunning'] /= 1000;
                        $v['lTotalProfit'] /= 1000;
                    }
                    unset($v);
                }
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
            return $this->apiReturn(0, $result, '', $count);
        }
        //$roomList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'kind');
        $kindList = Api::getInstance()->sendRequest(['id' => 0], 'room', 'kindlist');
        $this->assign('roomlist', $roomlist['data']);
        $this->assign('kindlist', $kindList['data']);
        return $this->fetch();
    }

    //获取房间库存概率信息
    public function getSocketRoomData()
    {
        $roomid = input('roomid');
//        $roomsData = $this->getSocketRoom($this->socket, $roomid);
        $roomsData = $this->getSocketNum($this->socket, $roomid);
        return $this->apiReturn(0, $roomsData, 'success');
    }
    //设置房间机器人
    public function setSocketRoomStorage()
    {
        if ($this->request->isAjax()) {
            $request = $this->request->request();
            $roomid = $request['roomid'];
            $storage = json_decode($request['data'], true);
            ksort($storage);

            $storageStr = '';
            foreach ($storage as $k => $v) {
                if (abs($k) > 2000000) {
                    return $this->apiReturn(1, [], '机器人数量不能超过绝对值200万');
                }
//                $storageStr .= $k . '#' . $v . '#';
                $storageStr .=   $v . '#';
            }
            $storageStr = rtrim($storageStr, '#');
//            print_r( $storageStr);die;
//
//            $roomsData = $this->getSocketRoom($this->socket, $roomid);
            $roomsData = $this->getSocketNum($this->socket, $roomid);
//            var_dump($roomsData['nCtrlRatio']);die;
//            $this->socket->setRoom($roomid, $roomsData['nCtrlRatio'], $roomsData['nInitStorage'], $roomsData['nCurrentStorage'], $storageStr);
//            $this->socket->setNum($roomid, $roomsData['nCtrlRatio'], $roomsData['nInitStorage'], $roomsData['nCurrentStorage'], $storageStr);
            $this->socket->setNum($roomid,  $storageStr);

            GameLog::logData(__METHOD__, $request, 1);
            ob_clean();
            return $this->apiReturn(0, [], '修改成功');
        }

        $roomid = intval(input('roomid')) ? intval(input('roomid')) : 0;
//        $roomsData = $this->getSocketRoom($this->socket, $roomid);
        $roomsData = $this->getSocketNum($this->socket, $roomid);


        $roomsData['szStorageRatio'] = trim($roomsData['szStorageRatio']);

        $array = [];
        if ($roomsData['szStorageRatio']) {

            $storage = explode('#', $roomsData['szStorageRatio']);

//            $info = array_chunk($storage, 1);
            $info = $storage;
            if ($info) {


                foreach ($info as $k => $v) {

                    if($k < 24){
                        $array[] = [
//                            'rate' => $v[0],
                            'rate' => $v,
                            'storage' => $k
                        ];
                    }


                }
            }
            $this->assign('num', count($info));
        }else{
            $this->assign('num', 0);
        }


        $this->assign('lists', $array);
        $this->assign('thisroomid', $roomid);
        return $this->fetch('setstorage');
    }

    public function getSocketNum2($socket, $roomid)
    {
//        $roomInfo = $socket->getRoomInfo($roomid);
        $roomInfo = $socket->getRoomNum($roomid);
        if ($roomInfo) {
            $roomInfo = $roomInfo[0];
        } else {
            $roomInfo = [
                'nServerID'       => $roomid,
                'nCtrlRatio'      => 0,
                'nInitStorage'    => 0,
                'nCurrentStorage' => 0,
                'szStorageRatio'  => ''
            ];
        }

//        $roomInfo['nCurrentStorage'] /= 1000;
//        $roomInfo['nInitStorage']    /= 1000;
        $roomInfo['currentwinrate']   = 0;

        $storageInfo = [];
        if (isset($roomInfo['szStorageRatio']) && trim($roomInfo['szStorageRatio']) != '') {
            $storage = explode('#', $roomInfo['szStorageRatio']);
            $info    = array_chunk($storage, 2);


            foreach ($info as $k1 => $v1) {
                if (intval($roomInfo['nCurrentStorage']) < intval($v1[0])) {
                    $roomInfo['currentwinrate'] = $v1[1];
                    break;
                }
            }

            foreach ($info as $k => $v) {



                if ($k == 0) {
                    $storageInfo[$k] = [
                        'rate'    => $v[1],
                        'storage' => '<' . $v[0]
                    ];
                } else {
                    $storageInfo[$k] = [
                        'rate'    => $v[1],
                        'storage' => $info[$k - 1][0] . '~' . $info[$k][0]
                    ];
                }

            }
        }
        $roomInfo['storage'] = $storageInfo;
        return $roomInfo;
    }








}
