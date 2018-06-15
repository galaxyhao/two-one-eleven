<?php
/**
 * Created by PhpStorm.
 * User: LRHest
 * Date: 2018/3/13
 * Time: 20:32
 */

namespace api\common\queue\work;


use think\queue\Job;

abstract class WorkBase
{
    /**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public abstract function fire(Job $job, $data);

    public function logError($msg,$data = []){
        $message = "[ ".date('Y-m-d H:i:s')." ] ". $msg.PHP_EOL.
            "[ data ]".var_export($data,true).PHP_EOL.
            "---------------------------------------- ".PHP_EOL;
        $err_file = RUNTIME_PATH.'api/queuelog'.DS.'error'.DS.date('Ym').DS.date('d').'.log';
        $this->save($err_file,$message);
    }
    public function logInfo($msg,$data = []){
        $message = "[ ".date('Y-m-d H:i:s')." ] ".$msg.PHP_EOL.
            "[ data ]".var_export($data,true).PHP_EOL.
            "----------------------------------------".PHP_EOL;
        $info_file = RUNTIME_PATH.'api/queuelog'.DS.'info'.DS.date('Ym').DS.date('d').'.log';
        $this->save($info_file,$message);
    }
    protected function save($file,$message){
        $path = dirname($file);
        !is_dir($path) && mkdir($path, 0755, true);
        return error_log($message,3,$file);
    }
    public function outMsg($message){
        $t = ' [ '.date('Y-m-d H:i:s').' ] ';
        printf($t.$message.PHP_EOL);
    }
    //失败处理事件
    public function failed($data){
        $this->logError('任务执行失败',$data);
    }
}