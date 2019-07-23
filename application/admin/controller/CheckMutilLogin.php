<?php

namespace app\admin\controller;

use think\Controller;
use app\model\User as userModel;

class CheckMutilLogin extends Controller
{
    public function check()
    {
        $userid = session('userid');
        $model = new userModel();
        if ($userid) {
            $userInfo = $model->getRowById($userid);
            if ($userInfo['session_id'] != session_id()) {
                return json(['code' => 1, 'msg' => '您的账号已在其他地方登录']);
            } else {
                return json(['code' => 0]);
            }
        }
        return json(['code' => 2]);
    }
}
