<?php
namespace app\admin\model;
use think\Model;
use think\config;
class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;



    public function  getData($cmd)
    {
         $config = config["url"].$cmd;
         $json =http_curl($config['url'],'post',$config);
         $data = json_decode($json);
         if(!empty($data)){
             if($data["code"]==0){
                 return $data['data'];
             }
             else
             {
                 return $data['message'];
             }
         }
    }




}
