<?php
namespace socket;
// OM TO AS
define('CMD_MA_QUERY_ROLE_LIST', 1);								//查询玩家列表
define('CMD_MA_GET_ROLE_BASE_INFO', 2);								//获取玩家信息
define('CMD_MA_UNBIND_ROLE_MACHNE', 3);								//主机解绑
define('CMD_MA_UNLOCK_ROLE', 4);										//解除锁定
define('CMD_MA_UNBLOCK_ROLE', 5);									//解除处罚
define('CMD_MA_LOCK_ROLE', 6);										//锁定玩家
define('CMD_MA_BLOCK_ROLE', 7);										//惩罚玩家
define('CMD_MA_UPDATE_ROLE_ACCOUNT_INFO', 8);						//修改玩家帐号信息
define('CMD_MA_SET_ROLE_IP_LOCK', 9);								//设置IP段锁定控制
define('CMD_MA_RESET_LOGIN_PWD', 10);								//重置登录密码
define('CMD_MA_UNBIND_WECHAT', 11);								//解绑微信
define('CMD_MA_REGISTER_ROBOT', 12);								//注册机器人
define('CMD_MA_SET_PASS_TYPE', 13);								//设置登录验证方式
// SendMAQueryRoleList iValueType
define('SRLVT_ROLEID', 1);			//1:按通行证号搜索
define('SRLVT_PLAYER_NAME', 2);			//2:按真实改名搜索
define('SRLVT_IDCARD', 3);				//3:按身份证号搜索
define('SRLVT_ACCOUNT', 4);				//4:按登陆账号搜索
define('SRLVT_LAST_LOGINIP', 5);			//5:根据登陆IP查询
define('SRLVT_QQ', 6);					//6：根据QQ查询
define('SRLVT_PHONE', 7);					//7:根据手机查询
define('SRLVT_MACHINE', 8);				//8:根据机器码查询
define('SRLVT_WECHAT', 9);				//9:根据微信查询

//SendMAUpdateUserAccountInfo iTypeID
define('SURAT_PLAYER_NAME', 1);			//1:修改真实姓名,
define('SURAT_PLAYER_ID_CARD', 2);			//2:修改身份证号,
define('SURAT_PLAYER_PHONE', 3);				//3:修改认证手机
//查询玩家列表
//$iCurPage >= 1 $iPageSize <= 20
//UINT32		iCurPage;				//当前页码
//UINT32		iPageSize;				//每页数量
//UINT32		iValueType;				//查询类型//SeRoleListValueType
//char		szQueryValue[64];		//查询数据
class omToAsSocket {
    private $comm;
    public function __construct()
    {
        $this->comm = new Comm('AS');
    }
    
    function SendMAQueryRoleList($socket, $iCurPage, $iPageSize, $iValueType, $szQueryValue)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iCurPage);
        $in_stream->WeireULong($iPageSize);
        $in_stream->WeireULong($iValueType);
        $in_stream->WeireString($szQueryValue, 64);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_QUERY_ROLE_LIST, $in_stream->len, 0, REQ_OM, REQ_AI);
        $in_head_len = COMM_HEAD_LEN;

        //$in_len = $in_head_len + $in_stream->len;
        //$in = $in_head . $in_stream->data;
        $in = $in_stream->data;
        //socket_write($socket, $in, $in_len);
        $socket->request($in_head,$in);
    }

//获取玩家信息
//UINT32		iRoleID;				//角色ID
    function SendMAGetRoleBaseInfo(&$socket, $iRoleID)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_GET_ROLE_BASE_INFO, $in_stream->len, 0, REQ_OM, REQ_AI);
        $in_head_len = COMM_HEAD_LEN;

        $in_len = $in_head_len + $in_stream->len;
        $in = $in_stream->data;
        //socket_write($socket, $in, $in_len);
        $socket->request($in_head,$in);
    }

//主机解绑
//UINT32		iRoleID;				//角色ID
    function SendMAUnBindRoleMachne($socket, $iRoleID)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_UNBIND_ROLE_MACHNE, $in_stream->len, 0, REQ_OM, REQ_AI);
        //$in_head_len = COMM_HEAD_LEN;

//	$in_len = $in_head_len + $in_stream->len;
//	$in = $in_head . $in_stream->data;
        $in = $in_stream->data;
//	socket_write($socket, $in, $in_len);
        $socket->request($in_head,$in);
    }

//解除锁定
//UINT32		iRoleID;				//角色ID
    function SendMAUnLockRole($socket, $iRoleID)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_UNLOCK_ROLE, $in_stream->len, 0, REQ_OM, REQ_AI);
//	$in_head_len = COMM_HEAD_LEN;
//
//	$in_len = $in_head_len + $in_stream->len;
//	$in = $in_head . $in_stream->data;
        $in = $in_stream->data;
//	socket_write($socket, $in, $in_len);
        $socket->request($in_head,$in);
    }

//解除处罚
//UINT32		iRoleID;				//角色ID
    function SendMAUnBlockRole($socket, $iRoleID)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_UNBLOCK_ROLE, $in_stream->len, 0, REQ_OM, REQ_AI);
//	$in_head_len = COMM_HEAD_LEN;
//
//	$in_len = $in_head_len + $in_stream->len;
//	$in = $in_head . $in_stream->data;
//	socket_write($socket, $in, $in_len);
        $in = $in_stream->data;
        $socket->request($in_head,$in);
    }

//锁定玩家
//UINT32		iRoleID;				//角色ID
//UINT32		iDays;					//锁定天数
    function SendMALockRole($socket, $iRoleID, $iDays)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);
        $in_stream->WeireULong($iDays);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_LOCK_ROLE, $in_stream->len, 0, REQ_OM, REQ_AI);
//	$in_head_len = COMM_HEAD_LEN;
//
//	$in_len = $in_head_len + $in_stream->len;
//	$in = $in_head . $in_stream->data;
//	socket_write($socket, $in, $in_len);
        $in = $in_stream->data;
        $socket->request($in_head,$in);
    }

//惩罚玩家
//UINT32		iRoleID;				//角色ID
//UINT32		iDays;					//锁定天数
    function SendMABlockRole($socket, $iRoleID, $iDays)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);
        $in_stream->WeireULong($iDays);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_BLOCK_ROLE, $in_stream->len, 0, REQ_OM, REQ_AI);
//	$in_head_len = COMM_HEAD_LEN;
//
//	$in_len = $in_head_len + $in_stream->len;
//	$in = $in_head . $in_stream->data;
//	socket_write($socket, $in, $in_len);
        $in = $in_stream->data;
        $socket->request($in_head,$in);
    }

//修改玩家帐号信息
//UINT32		iRoleID;				//角色ID
//UINT32		iTypeID;				//1:修改真实姓名,2:修改身份证号,3:修改认证手机
//char		szValue[64];			//修改的值
    function SendMAUpdateUserAccountInfo($socket, $iRoleID, $iTypeID, $szValue)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);
        $in_stream->WeireULong($iTypeID);
        $in_stream->WeireString($szValue, 64);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_UPDATE_ROLE_ACCOUNT_INFO, $in_stream->len, 0, REQ_OM, REQ_AI);
//	$in_head_len = COMM_HEAD_LEN;
//
//	$in_len = $in_head_len + $in_stream->len;
//	$in = $in_head . $in_stream->data;
//	socket_write($socket, $in, $in_len);
        $in = $in_stream->data;
        $socket->request($in_head,$in);
    }

//设置IP段锁定控制
//UINT32		iRoleID;				//角色ID
//UINT32		iTitleID;				//锁定标志
    function SendMASetRoleIPLock(&$socket, $iRoleID, $iTitleID)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);
        $in_stream->WeireULong($iTitleID);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_SET_ROLE_IP_LOCK, $in_stream->len, 0, REQ_OM, REQ_AI);
//	$in_head_len = COMM_HEAD_LEN;
//
//	$in_len = $in_head_len + $in_stream->len;
//	$in = $in_head . $in_stream->data;
//	socket_write($socket, $in, $in_len);
        $in = $in_stream->data;
        $socket->request($in_head,$in);
    }
//重置登录密码
//UINT32		iRoleID;				//角色ID
//char		szNewPassword[33];	//新密码md5
    function SendMAResetLoginPwd(&$socket, $iRoleID, $szNewPassword)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);
        $in_stream->WeireString($szNewPassword, 33);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_RESET_LOGIN_PWD, $in_stream->len, 0, REQ_OM, REQ_AI);
        /*$in_head_len = COMM_HEAD_LEN;
    
        $in_len = $in_head_len + $in_stream->len;
        $in = $in_head . $in_stream->data;
        socket_write($socket, $in, $in_len);*/
        $socket->request($in_head,$in_stream->data);
    }

    //锁定用户
    function SendMDCtrolRole(&$socket, $iRoleID, $iDays){

        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);
        $in_stream->WeireULong($iDays);


        $in_head =  $this->comm->MakeSendHead(CMD_MA_LOCK_ROLE, $in_stream->len, 0, REQ_OM, REQ_DC);
        $socket->request($in_head,$in_stream->data);
    }

    //解除锁定
    function SendMDCtrolUnlockRole(&$socket, $iRoleID){

        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);
        $in_head =  $this->comm->MakeSendHead(CMD_MA_UNLOCK_ROLE, $in_stream->len, 0, REQ_OM, REQ_AI);
        $socket->request($in_head,$in_stream->data);
    }
//解绑微信
//char		szLoginCode[64];	//帐号
    function SendMAUnBindWeChat($socket, $szLoginCode)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireString($szLoginCode, 64);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_UNBIND_WECHAT, $in_stream->len, 0, REQ_OM, REQ_AI);
        /*$in_head_len = COMM_HEAD_LEN;
    
        $in_len = $in_head_len + $in_stream->len;
        $in = $in_head . $in_stream->data;
        socket_write($socket, $in, $in_len);*/
        $socket->request($in_head,$in_stream->data);
    }

//注册机器人
//char		szLoginCode[64];	//帐号
//char		szPassword[33];		//密码md5
//char		szIP[17]; 		//注册地址
//char		szRealName[64]; 	//真实姓名
//char		IdCard[24]; 		//身份证
//char		szMobilePhone[12]; 	//手机号码
//char		szQQ[12]; 		//QQ号
    function SendMARegisterAccount($socket, $szLoginCode, $szPassword, $szIP, $szRealName, $IdCard, $szMobilePhone, $szQQ)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireString($szLoginCode, 64);
        $in_stream->WeireString($szPassword, 33);
        $in_stream->WeireString($szIP, 17);
        $in_stream->WeireString($szRealName, 64);
        $in_stream->WeireString($IdCard, 24);
        $in_stream->WeireString($szMobilePhone, 12);
        $in_stream->WeireString($szQQ, 12);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_REGISTER_ROBOT, $in_stream->len, 0, REQ_OM, REQ_AI);
        /*$in_head_len = COMM_HEAD_LEN;
    
        $in_len = $in_head_len + $in_stream->len;
        $in = $in_head . $in_stream->data;
        socket_write($socket, $in, $in_len);*/
        $socket->request($in_head,$in_stream->data);
    }
//设置登录验证方式
//UINT32		iRoleID;				//角色ID
//UINT32		iPassType;				//验证类型
    function SendMASetPassType($socket, $iRoleID, $iPassType)
    {
        $in_stream = new PHPStream();
        $in_stream->WeireULong($iRoleID);
        $in_stream->WeireULong($iPassType);

        //echo $in_stream->len;
        //echo $in_stream->data;

        $in_head =  $this->comm->MakeSendHead(CMD_MA_SET_PASS_TYPE, $in_stream->len, 0, REQ_OM, REQ_AI);
        /*     $in_head_len = COMM_HEAD_LEN;
        
            $in_len = $in_head_len + $in_stream->len;
            $in = $in_head . $in_stream->data;
            socket_write($socket, $in, $in_len); */
        $socket->request($in_head,$in_stream->data);
    }
}


