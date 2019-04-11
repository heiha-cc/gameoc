<?php
namespace app\admin\controller;

use think\Db;

class Log extends Main
{
    /**
     * 首页
    */
    public function index(){
        $where = [];
        $username = $this->request->get('username') ? trim($this->request->get('username')) : '';
        $start = $this->request->get('start') ? $this->request->get('start') : '';
        $end = $this->request->get('end') ? $this->request->get('end') : '';
        if ($username) {
            $where['username'] = ['like','%'.$username.'%'];
        }

        if ($start) {
            if ($end && $end>$start) {
                $where['logday'] = [['egt', $start],['lt', $end]];
            } else {
                $where['logday'] = ['egt', $start];
            }
        }


        $data = Db::name('Log')
            ->where($where)
            ->order('id desc')
            ->paginate(10, false, [
                'type'      => 'Layui',
                'var_page'  => 'page',
                'query'     => [
                    'username' => $username,
                    'start' => $start,
                    'end' => $end
                ]
            ]);
//        var_dump(Db::name('Log')->getLastSql());
//        die;
        $this->assign('lists', $data);
        $this->assign('username', $username);
        $this->assign('start', $start);
        $this->assign('end', $end);
        return $this->fetch('index');
    }

    //做异步分页！！！

}
