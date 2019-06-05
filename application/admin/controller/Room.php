<?php

namespace app\admin\controller;

class Room extends Main
{
    /**
     * 房间总览
     */
    public function index()
    {
        if ($this->request->isAjax()) {

        }
        return $this->fetch();
    }

    /**
     * 捕鱼
     */
    public function buyu()
    {
        if ($this->request->isAjax()) {

        }
        return $this->fetch();
    }
}
