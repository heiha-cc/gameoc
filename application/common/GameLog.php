<?php
namespace app\common;

use app\admin\model\Log;

//操作日志
class GameLog
{
    public static function log($data)
    {
        $model = new Log();
        $model->data($data)->save();
    }
}