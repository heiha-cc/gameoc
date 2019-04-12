<?php

namespace app\admin\controller;

use think\Db;
use app\model\Log as LogModel;

class Log extends Main
{
    /**
     * 首页
     */
    public function index()
    {
//        $data = Db::name('Log')
//            ->where($where)
//            ->order('id desc')
//            ->paginate(10, false, [
//                'type'      => 'Layui',
//                'var_page'  => 'page',
//                'query'     => [
//                    'username' => $username,
//                    'start' => $start,
//                    'end' => $end,
//                    'action' => $action
//                ]
//            ]);
//        var_dump(Db::name('Log')->getLastSql());
//        die;
//        $this->assign('lists', $data);
//        $this->assign('username', $username);
//        $this->assign('action', $action);
//        $this->assign('start', $start);
//        $this->assign('end', $end);
        return $this->fetch();
    }

    public function indexData()
    {
        $data     = [
            'code'  => 0,
            'msg'   => '',
            'count' => 0,
            'data'  => []
        ];
        $where    = [];
        $username = $this->request->get('username') ? trim($this->request->get('username')) : '';
        $action   = $this->request->get('action') ? trim($this->request->get('action')) : '';
        $start    = $this->request->get('start') ? $this->request->get('start') : '';
        $end      = $this->request->get('end') ? $this->request->get('end') : '';
        $page     = $this->request->get('page') ? intval($this->request->get('page')) : 1;
        $limit    = $this->request->get('limit') ? intval($this->request->get('limit')) : 10;
        if ($username) {
            $where['username'] = ['like', '%' . $username . '%'];
        }
        if ($action) {
            $where['action'] = ['like', '%' . $action . '%'];
        }
        if ($start) {
            if ($end && $end > $start) {
                $where['logday'] = [['egt', $start], ['lt', $end]];
            } else {
                $where['logday'] = ['egt', $start];
            }
        }

        $logModel = new LogModel();
        $count    = $logModel->getCount($where);
        if (!$count) {
            return json($data);
        }
        $list          = $logModel->getList($where, $page, $limit, '*', ['id' => 'desc']);
        $data['data']  = $list;
        $data['count'] = $count;
        return json($data);
    }
}
