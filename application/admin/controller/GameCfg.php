<?php

namespace app\admin\controller;

use app\common\Config as ConfigModel;
use app\common\GameLog;
use think\Exception;


class GameCfg extends Main
{
    protected $model = null;
    public function __construct()
    {
        parent::__construct();
        $this->model = new ConfigModel();
    }
    public function index()
    {
        $logData = [
            'userid' => session('user_id'),
            'username' => session('username'),
            'action' => __METHOD__,
            'request' => '',
            'logday'  => date('Ymd'),
            'recordtime' => date('Y-m-d H:i:s'),
            'status' => 1
        ];
        $list = $this->model->where('group', 'verify_code')->select();
        $list2 = $this->model->where('group', 'email')->select();
        $list3 = $this->model->where('group', 'game')->select();
        $this->assign('lists', $list);
        $this->assign('lists2', $list2);
        $this->assign('lists3', $list3);
        foreach ($list2 as &$v) {
            if ($v['content']) {
                $v['content'] = json_decode($v['content'], true);
            }
        }
        unset($v);
        GameLog::log($logData);
        return $this->fetch();
    }


    public function editCode()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($data['codeid'] && $data['codekey']) {
                $logData = [
                    'userid' => session('user_id'),
                    'username' => session('username'),
                    'action' => __METHOD__,
                    'logday'  => date('Ymd'),
                    'recordtime' => date('Y-m-d H:i:s'),
                    'request'   => json_encode($data, JSON_UNESCAPED_UNICODE)
                ];
                $this->doHandle($data, $logData);
            }
        }
    }

    public function editEmail()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($data) {
                $logData = [
                    'userid' => session('user_id'),
                    'username' => session('username'),
                    'action' => __METHOD__,
                    'logday'  => date('Ymd'),
                    'recordtime' => date('Y-m-d H:i:s'),
                    'request'   => json_encode($data, JSON_UNESCAPED_UNICODE)
                ];
                $this->doHandle($data, $logData);
            }
        }
    }
    public function editGame()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($data) {
                $logData = [
                    'userid' => session('user_id'),
                    'username' => session('username'),
                    'action' => __METHOD__,
                    'logday'  => date('Ymd'),
                    'recordtime' => date('Y-m-d H:i:s'),
                    'request'   => json_encode($data, JSON_UNESCAPED_UNICODE)
                ];
                $this->doHandle($data, $logData);
            }
        }
    }

    private function doHandle($data, $logData) {
        $response = [];
        foreach ($this->model->all() as $v) {
            if (isset($data[$v['name']])) {
                $value = $data[$v['name']];
                if (is_array($value) && isset($value['field'])) {
                    $value = json_encode(ConfigModel::getArrayData($value), JSON_UNESCAPED_UNICODE);
                } else {
                    $value = is_array($value) ? implode(',', $value) : $value;
                }
                $v['value'] = $value;
                $configList[] = $v->toArray();
            }
        }
        $this->model->allowField(true)->saveAll($configList);
        try {
            $this->refreshFile();
        } catch (Exception $e) {

            $response =  ['code' => 1, 'msg' => $e->getMessage()];
            $logData['response'] = json_encode($response, JSON_UNESCAPED_UNICODE);
            $logData['status'] = 0;
            GameLog::log($logData);
            $this->error($e->getMessage());
        }

        $response =  ['code' => 0, 'msg' => '更新成功'];
        $logData['response'] = json_encode($response, JSON_UNESCAPED_UNICODE);
        $logData['status'] = 1;
        GameLog::log($logData);
        $this->success('更新成功');
    }

    /**
     * 刷新配置文件
     */
    protected function refreshFile()
    {
        $config = [];
        foreach ($this->model->all() as $k => $v) {

            $value = $v->toArray();
            if (in_array($value['type'], ['selects', 'checkbox', 'images', 'files'])) {
                $value['value'] = explode(',', $value['value']);
            }
            if ($value['type'] == 'array') {
                $value['value'] = (array)json_decode($value['value'], TRUE);
            }
            $config[$value['name']] = $value['value'];
        }
        file_put_contents(APP_PATH . 'extra' . DS . 'site.php', '<?php' . "\n\nreturn " . var_export($config, true) . ";");
    }
}