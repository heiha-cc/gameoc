<?php
namespace app\admin\controller;

class System extends Main
{
	/**
     * 配置页面展示
    */
    public function siteConfig()
    {
        return $this->fetch();
    }

}
