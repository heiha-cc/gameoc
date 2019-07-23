<?php

namespace app\admin\controller;

use socket\QuerySocket;
class SychronData extends Main
{
    private $socket = null;

    public function __construct()
    {
        parent::__construct();
        $this->socket = new QuerySocket();
    }

    /**
     * 首页
     */
    public function index()
    {
        $res = $this->socket->sychron();
        if ($res && $res['iResult'] == 0) {
            return $this->apiReturn(0, [], '同步成功');
        } else {
            return $this->apiReturn(1, [], '同步失败');
        }
    }
}
