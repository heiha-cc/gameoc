<?php
namespace app\admin\controller;

use think\Db;
use org\Backup;


const PREFIX = 'lotus_';

class DbManage extends Main
{

    public $config = '';                                                        //相关配置
    public $model = '';                                                         //实例化一个model
    public $content;                                                            //内容
    public $dbName = '';                                                        //数据库名
    public $dir_sep = '/';                                                      //路径符号


    //初始化数据
    function _initialize() {
        parent::_initialize();
        header("Content-type: text/html;charset=utf-8");
        set_time_limit(0);                                                      //不超时
        ini_set('memory_limit','500M');
        $this->config = array(
            'path' => config('database')['db_backup'],                                           //备份文件存在哪里
            'isCompress' => 0,                                                  //是否开启gzip压缩      【未测试】
            'isDownload' => 0                                                   //备份完成后是否下载文件 【未测试】
        );
        $this->dbName = config('database')['database'];                                           //当前数据库名称
        //$sql = 'set interactive_timeout=24*3600';                             //空闲多少秒后 断开链接
        //$this->model>execute($sql);
    }


     /**
     * 数据表列表
     */
    public function index()
    {
        $dbtables = DB::query('SHOW TABLE STATUS');
        $total = 0;
        $list = array();
        foreach ($dbtables as $k => $v) {
            $name_arr = explode('_', $v['Name']);
            if ($name_arr[0] == trim(PREFIX, '_')) {
                $v['size'] = format_bytes($v['Data_length'] + $v['Index_length']);
                $list[$k] = $v;
                $total += $v['Data_length'] + $v['Index_length'];
            }
        }
        $this->assign('list', $list);
        $this->assign('total', format_bytes($total));
        $this->assign('tableNum', count($list));
        return $this->fetch();
    }
    /* -
     * +------------------------------------------------------------------------
     * * @ 备份数据 { 备份每张表、视图及数据 }
     * +------------------------------------------------------------------------
     * * @ $tables 需要备份的表数组
     * +------------------------------------------------------------------------
     */
    function backup($tables) {
        if (empty($tables))
            $this->error('没有需要备份的数据表!');
        $this->content  = '/* This file is created by MySQLReback ' . date('Y-m-d H:i:s') . ' */';
        $this->content .= "\r\n SET FOREIGN_KEY_CHECKS=0;"; 
        foreach ($tables as $i => $table) {
            $table = $this->backquote($table);                                  //为表名增加 ``
            $tableRs = Db::query("SHOW CREATE TABLE {$table}");       //获取当前表的创建语句
            if (!empty($tableRs[0]["Create View"])) {
                $this->content .= "\r\n /* 创建视图结构 {$table}  */";
                $this->content .= "\r\n DROP VIEW IF EXISTS {$table};/* MySQLReback Separation */ \r\n " . $tableRs[0]["Create View"] . ";/* MySQLReback Separation */";
            }
            if (!empty($tableRs[0]["Create Table"])) {
                $this->content .= "\r\n /* 创建表结构 {$table}  */";
                $this->content .= "\r\n DROP TABLE IF EXISTS {$table};/* MySQLReback Separation */ \r\n " . $tableRs[0]["Create Table"] . ";/* MySQLReback Separation */";
                $tableDateRow =  Db::query("SELECT * FROM {$table}");
                $valuesArr = array();
                $values = '';
                if (false != $tableDateRow) {
                    foreach ($tableDateRow as &$y) {
                        foreach ($y as &$v) {
                           if ($v=='')                                  //纠正empty 为0的时候  返回tree
                                $v = 'null';                                    //为空设为null
                            else
                                $v = "'" . $v . "'";       //非空 加转意符
                        }
                        $valuesArr[] = '(' . implode(',', $y) . ')';
                    }
                }
                $temp = $this->chunkArrayByByte($valuesArr);
                if (is_array($temp)) {
                    foreach ($temp as $v) {
                        $values = implode(',', $v) . ';/* MySQLReback Separation */';
                        if ($values != ';/* MySQLReback Separation */') {
                            $this->content .= "\r\n /* 插入数据 {$table} */";
                            $this->content .= "\r\n INSERT INTO {$table} VALUES {$values}";
                        }
                    }
                }
//                dump($this->content);
//                exit;
            }
        }
        if (!empty($this->content)) {
            $fileName =  $this->setFile();
        }
        $this->success('备份成功,文件:<br>'.$fileName);
    }


    private function chunkArrayByByte($array, $byte = 5120) {
        $i = 0;
        $sum = 0;
        $return = array();
        foreach ($array as $v) {
            $sum += strlen($v);
            if ($sum < $byte) {
                $return[$i][] = $v;
            } elseif ($sum == $byte) {
                $return[++$i][] = $v;
                $sum = 0;
            } else {
                $return[++$i][] = $v;
                $i++;
                $sum = 0;
            }
        }
        return $return;
    }

        /* -
     * +------------------------------------------------------------------------
     * * @ 给字符串添加 ` `
     * +------------------------------------------------------------------------
     * * @ $str 字符串
     * +------------------------------------------------------------------------
     * * @ 返回 `$str`
     * +------------------------------------------------------------------------
     */
    private function backquote($str) {
        return "`{$str}`";
    }

    /* -
     * +------------------------------------------------------------------------
     * * @ 获取数据库的所有表
     * +------------------------------------------------------------------------
     * * @ $dbName  数据库名称
     * +------------------------------------------------------------------------
     */
    private function getTables($dbName = '') {
        if (!empty($dbName)) {
            $sql = 'SHOW TABLES FROM ' . $dbName;
        } else {
            $sql = 'SHOW TABLES ';
        }
        $result = Db::query($sql);
        $info = array();
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

        /* -
     * +------------------------------------------------------------------------
     * * @ 把数据写入磁盘
     * +------------------------------------------------------------------------
     */
    private function setFile() {
        $recognize = '';
        $recognize = $this->dbName;
        $fileName = $this->trimPath($this->config['path'] . $this->dir_sep . $recognize . '_' . date('Ymd') . '_' . mt_rand(1000, 9999) . '.sql');
        $path = $this->setPath($fileName);
        if ($path !== true) {
            $this->error("无法创建备份目录目录 '$path'");
        }
        if ($this->config['isCompress'] == 0) {
            if (!file_put_contents($fileName, $this->content, LOCK_EX)) {
                $this->error('写入文件失败,请检查磁盘空间或者权限!');
            }
        } else {
            if (function_exists('gzwrite')) {
                $fileName .= '.gz';
                if ($gz = gzopen($fileName, 'wb')) {
                    gzwrite($gz, $this->content);
                    gzclose($gz);
                } else {
                    $this->error('写入文件失败,请检查磁盘空间或者权限!');
                }
            } else {
                $this->error('没有开启gzip扩展!');
            }
        }
        if ($this->config['isDownload']) {
            $this->downloadFile($fileName);
        }
        return $fileName;
    }
    private function trimPath($path) {
        return str_replace(array('/', '\\', '//', '\\\\'), $this->dir_sep, $path);
    }
    private function setPath($fileName) {
        $dirs = explode($this->dir_sep, dirname($fileName));
        $tmp = '';
        foreach ($dirs as $dir) {
            $tmp .= $dir . $this->dir_sep;
            if (!file_exists($tmp) && !@mkdir($tmp, 0777))
                return $tmp;
        }
        return true;
    }
     /* -
     * +------------------------------------------------------------------------
     * * @ 下载备份文件
     * +------------------------------------------------------------------------
     */
    function downloadBak() {
        $file_name = $_GET['file'];
        $file_dir = $this->config['path'];
        if (!file_exists($file_dir . "/" . $file_name)) { //检查文件是否存在
            return false;
            exit;
        } else {
            $file = fopen($file_dir . "/" . $file_name, "r"); // 打开文件
            // 输入文件标签
            header('Content-Encoding: none');
            header("Content-type: application/octet-stream");
            header("Accept-Ranges: bytes");
            header("Accept-Length: " . filesize($file_dir . "/" . $file_name));
            header('Content-Transfer-Encoding: binary');
            header("Content-Disposition: attachment; filename=" . $file_name);  //以真实文件名提供给浏览器下载
            header('Pragma: no-cache');
            header('Expires: 0');
            //输出文件内容
            echo fread($file, filesize($file_dir . "/" . $file_name));
            fclose($file);
            exit;
        }
    }

    function backup_list(){
        $list = $this->MyScandir('data/sqldata',1);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /* -
     * +------------------------------------------------------------------------
     * * @ 获取 目录下文件数组
     * +------------------------------------------------------------------------
     * * @ $FilePath 目录路径
     * * @ $Order    排序
     * +------------------------------------------------------------------------
     * * @ 获取指定目录下的文件列表，返回数组
     * +------------------------------------------------------------------------
     */
    private function MyScandir($FilePath = './', $Order = 0) {
        $FilePath = opendir($FilePath);
        $fileArr = [];
        while ($filename = readdir($FilePath)) {
            if($filename!=='.'&&$filename!=='..'&&$filename!=='.gitignore'){
                array_push($fileArr, ['filename'=>$filename,'size'=>format_bytes( filesize(config('database')['db_backup'].DS.$filename))]);
            }
        }
        $Order == 0 ? sort($fileArr) : rsort($fileArr);
        return $fileArr;
    }


    /**
     * 优化
    */
    public function optimize()
    {
        $strTable = input('table_name');

        if (DB::query("OPTIMIZE TABLE {$strTable} ")) {
            $this->success("数据表" . $strTable.'优化成功');
        }
    }
    
    /**
     * 修复
     */
    public function repair()
    {
        $strTable = input('table_name');
        if (DB::query("REPAIR TABLE {$strTable} ")) {
            $this->success("数据表" . $strTable.'修复成功');
        }
    }

    /*删除*/
    function delSql(){
        $filename = input('filename');
        $res =  unlink(config('database')['db_backup'].DS.$filename);
        if($res){
            $this->success('delete success');
        }else{
            $this->error('delete failure');
        }
    }
}
