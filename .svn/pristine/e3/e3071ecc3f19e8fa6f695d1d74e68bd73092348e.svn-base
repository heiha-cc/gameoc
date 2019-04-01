<?php
namespace app\admin\model;
use think\Model;
use app\admin\model\basemodel;

class GameCfg extends BaseModel
{
    protected $autoWriteTimestamp = true;


    public function getlist(){
        $cmd = config("configlist");
        $data = $this->getData($cmd);
        if(!empty($data)){
            return  $data;
        }

    }


}
