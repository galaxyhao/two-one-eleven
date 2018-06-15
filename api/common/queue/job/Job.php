<?php
/**
 * Created by PhpStorm.
 * User: LRHest
 * Date: 2018/3/17
 * Time: 11:19
 */

namespace api\common\queue\job;

use think\Queue;

class Job
{
    public function createLaterJob($delay,$job,$data,$queue){
        $result = Queue::later($delay,$job,$data,$queue);
        return $result;
    }
    public function createNotLaterJob($job,$data,$queue){
        $result = Queue::push($job,$data,$queue);
        return $result;
    }
}