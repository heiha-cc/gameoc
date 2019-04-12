<?php
namespace app\admin\controller;

use app\common\GameLog;
use geetest\Geetest;
use think\Config;
use think\Controller;
use think\Db;
use think\Session;
use think\Validate;

class User extends Controller
{
    /**
     * 首页
    */
    public function index(){
        return $this->fetch('login');
    }
    /**
     * 登录
    */
    public function login()
    {
        $post     = $this->request->post();
        if(empty($post)){
            $this->redirect('user/index');
        }
        $validate = validate('User');
        $validate->scene('login');
        $username  =  $post['username'];
        $user_id = Db::name('user')
            ->where('username', $username)
            ->value('id');
        if (!$validate->check($post)) {
            $this->error($validate->getError());
        } else {
            //判断验证码

            $checkcode = geetest_chcek_verify($post);
            if (!$checkcode) {
                $this->error('请先验证');
            }

            $sql_password = Db::name('user')
                ->where('username', $post['username'])
                ->value('password');
            if (md5($post['password']) !== $sql_password) {
                $this->error('密码错误');
            } else {
                session('username', $post['username']);
                session('user_id', $user_id);
                Db::name('user')
                    ->where('username',$post['username'])
                    ->update(['last_login_ip'=>$_SERVER['REMOTE_ADDR'],'last_login_time'=>date('Y-m-d h:i:s',time())]);
                $this->success('登陆成功', 'index/index');
            }
        }
    }
    //注销
    public function logOut()
    {
        session('username', null);
        $this->redirect('admin/user/index');
    }

    public function userlist()
    {
        $where = [];
        $username = '';
        if ($this->request->get('username')) {
            $where['username'] = ['like', '%'.trim($this->request->get('username')).'%'];
            $username = $this->request->get('username');
        }
        $data = Db::name('User')
            ->where($where)
            ->order('id asc')
            ->paginate(1, false, [
                'type'      => 'Layui',
                'var_page'  => 'page',
                'path' => 'javascript:searchUser([PAGE],"'.$username.'")',
                //'query'     => $this->request->param()
            ]);
        if ($this->request->get('page')) {
            return json(['code'=>0,'data'=>$data, 'pages' => $data->render()]);
        }
        $this->assign('users', $data);
        $this->assign('username',$username);
        return $this->fetch();
    }

    //增加用户
    public function addUser()
    {
        $request = $this->request;
        if($request->isPost()){
            $post     = $request->post();
            $group_id = $post['group_id'];
            unset($post['group_id']);
            $validate = validate('User');
            $res      = $validate->check($post);
            if ($res !== true) {
                $this->error($validate->getError());
            } else {

                unset($post['check_password']);
                $post['password'] = md5($post['password']);
                $post['last_login_ip'] = '0.0.0.0';
                $post['create_time']   = date('Y-m-d h:i:s', time());
                $db = Db::name('user')
                    ->insert($post);
                $userId = Db::name('user')->getLastInsID();
                Db::name('auth_group_access')
                    ->insert(['uid'=>$userId,'group_id'=>$group_id]);


                $logData = [
                    'userid' => session('user_id'),
                    'username' => session('username'),
                    'action' => __METHOD__,
                    'request' => json_encode($post),
                    'logday'  => date('Ymd'),
                    'recordtime' => date('Y-m-d H:i:s'),
                    'status' => 1
                ];
                GameLog::log($logData);
                $this->success('success');
            }
        }else{
            $auth_group = Db::name('auth_group')
                ->field('id,title')
                ->where('status',1)
                ->order('id desc')
                ->select();
            return $this->fetch('add',['auth_group'=>$auth_group]);
        }

    }
    //编辑提交
    public function editUser($id)
    {
        $request = $this->request;
        if($request->isPost()){
            $post     = $this->request->post();
            if($post['id']==1 ){
                if(session('user_id')!==1)  $this->error('系统管理员无法修改');
            }
            $group_id = $post['group_id'];
            unset($post['group_id']);
            $validate = validate('User');
            if (empty($post['password']) && empty($post['check_password'])) {
                $res = $validate->scene('edit')->check($post);
                if ($res !== true) {
                    $this->error($validate->getError());
                } else {
                    unset($post['password']);
                    unset($post['check_password']);
                    $db = Db::name('user')
                        ->where('id', $post['id'])
                        ->update(
                            [
                                'username' => $post['username'],
                                'email'    => $post['email'],
                            ]);
                    Db::name('auth_group_access')
                        ->where('uid',$post['id'])
                        ->update(['group_id'=>$group_id]);

                    $logData = [
                        'userid' => session('user_id'),
                        'username' => session('username'),
                        'action' => __METHOD__,
                        'request' => json_encode($post),
                        'logday'  => date('Ymd'),
                        'recordtime' => date('Y-m-d H:i:s'),
                        'status' => 1
                    ];
                    GameLog::log($logData);
                    $this->success('编辑成功');
                }
            } else {
                $res = $validate->scene('editPassword')->check($post);
                if ($res !== true) {
                    $this->error($validate->getError());
                } else {
                    $logData = [
                        'userid' => session('user_id'),
                        'username' => session('username'),
                        'action' => __METHOD__,
                        'request' => json_encode($post),
                        'logday'  => date('Ymd'),
                        'recordtime' => date('Y-m-d H:i:s'),
                        'status' => 1
                    ];

                    unset($post['check_password']);
                    $post['password'] = md5($post['password']);
                    $db               = Db::name('user')
                        ->where('id', $post['id'])
                        ->update($post);
                    GameLog::log($logData);
                    $this->success('编辑成功');
                }
            }
        }else{
            $data = Db::name('User')
                ->alias('a')
                ->join('auth_group_access b','b.uid=a.id','left')
                ->field('a.*,b.group_id')
                ->where('id', $id)
                ->find();
            $auth_group = Db::name('auth_group')
                ->field('id,title')
                ->order('id desc')
                ->select();
            $this->assign('auth_group', $auth_group);
            $this->assign('data', $data);
            return $this->fetch('edit');
        }

    }
    //删除用户
    public function deleteUser()
    {
        $id = $this->request->post('id');
        $username =  Db::name('user')
            ->where('id',$id)
            ->value('username');
        if ((int) $id !== 1) {
            if($username!==session('username')){
                 $db = Db::name('user')
                ->where('id', $id)
                ->delete();
                $logData = [
                    'userid' => session('user_id'),
                    'username' => session('username'),
                    'action' => __METHOD__,
                    'request' => json_encode($this->request->post()),
                    'logday'  => date('Ymd'),
                    'recordtime' => date('Y-m-d H:i:s'),
                    'status' => 1
                ];
                GameLog::log($logData);
                $this->success('删除成功');
            }else{
                 $this->error('无法删除当前登录用户');
            }
        } else {
            $this->error('超级管理员无法删除');
        }
    }


    /**
     * geetest生成验证码
     */
    public function geetest_show_verify(){
        $config = Config::get('site');
        $geetest_id=$config['codeid'];
        $geetest_key=$config['codekey'];
        $geetest=new Geetest($geetest_id,$geetest_key);
        $user_id = 'geetest';
        $status = $geetest->pre_process($user_id);
        session('geetest',array(
            'gtserver'=>$status,
            'user_id'=>$user_id
        ));
        echo $geetest->get_response_str();
    }

}
