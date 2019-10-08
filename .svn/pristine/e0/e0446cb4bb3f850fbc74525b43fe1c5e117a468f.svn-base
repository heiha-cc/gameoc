<?php
namespace app\admin\controller;



use app\common\Api;

class Linechart extends Main
{  
    /**
     * 在线折线图
    */
    public function online()
    {
        return $this->fetch();
    }

    //大厅
    public function hall()
    {
        $res = Api::getInstance()->sendRequest(['id' => 0], 'game', 'hallonline');
        //时间  ios  安卓
        $dates = $numbers = $numbers2 = [];
        if (isset($res['data']) && $res['data']) {
            foreach ($res['data'] as $v) {
                $dates[] = $v['addtime'];
                $numbers[] = $v['iosusercount'];
                $numbers2[] = $v['androidusercount'];
            }
        }
        return $this->apiReturn($res['code'], ['dates' => $dates, 'numbers' => $numbers,  'numbers2' => $numbers2], $res['message']);
    }

    //游戏内
    public function game()
    {
        $res = Api::getInstance()->sendRequest(['id' => 0], 'game', 'roomonline');
        //时间  ios  安卓
        $dates = $numbers = $numbers2 = [];
        if (isset($res['data']) && $res['data']) {
            foreach ($res['data'] as $v) {
                $dates[] = $v['addtime'];
                $numbers[] = $v['iosusercount'];
                $numbers2[] = $v['androidusercount'];
            }
        }

        return $this->apiReturn(0, ['dates' => $dates, 'numbers' => $numbers, 'numbers2' => $numbers2]);
    }

}
