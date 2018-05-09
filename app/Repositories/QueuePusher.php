<?php
/**
 * Created by PhpStorm.
 * User: loopear
 * Date: 08/09/15
 * Time: 11:54
 */

namespace App\Repositories;

use Illuminate\Foundation\Bus\DispatchesJobs;
use IronMQ;
use Config;

class QueuePusher {
    use DispatchesJobs;
    public static function pushMessageToQueue($job,$data,$queue = null){
        if(Config::get('queue.use_queue')){
            $ironmq = new IronMQ\IronMQ(array(
                "token" => Config::get('queue.connections.iron.token'),
                "project_id" => Config::get('queue.connections.iron.project')
            ));
            $queue = Config::get('queue.connections.iron.queue');
            $ironmq->postMessage($queue, json_encode(['job' => $job,'data' => $data]));
        }else{
            $job = "\\App\\Jobs\\".$job;
            \Bus::dispatch(new $job($data));
        }
    }
} 