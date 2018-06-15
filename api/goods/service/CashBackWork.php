<?php
/**
 * Created by PhpStorm.
 * User: 91641
 * Date: 2018/6/15
 * Time: 14:31
 */

namespace api\goods\service;


use api\common\queue\work\WorkBase;
use think\queue\Job;

class CashBackWork extends WorkBase
{

    /**
     * fire方法是消息队列默认调用的方法
     * @param Job $job 当前的任务对象
     * @param array|mixed $data 发布任务时自定义的数据
     */
    public function fire(Job $job, $data)
    {

    }
}