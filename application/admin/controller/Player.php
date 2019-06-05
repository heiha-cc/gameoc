<?php

namespace app\admin\controller;



use think\Cache;

class Player extends Main
{
    /**
     * 在线玩家
     */
    public function online()
    {
        //halt(phpinfo());
        if ($this->request->isAjax()) {

        }
//        halt(Cache::store('redis')->get('my'));
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
     * 超级玩家
     */
    public function super()
    {
        if ($this->request->isAjax()) {

        }
        return $this->fetch();
    }

    /**
     * 新增超级玩家
     */
    public function addSuper()
    {
        if ($this->request->isAjax()) {

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
