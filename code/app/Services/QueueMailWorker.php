<?php

namespace App\Services;

use App\Contracts\QueueWorker as QueueWorkerContract;

/**
 * Class QueueMailWorker
 */
class QueueMailWorker implements QueueWorkerContract
{
    private $worker;

    private $debug = false;

    /**
     * QueueMailWorker constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->worker = new \GearmanWorker();

        $this->worker->addServer($config['server'], $config['port']);

        $this->worker->addFunction('sendmail', function($job) {
            $this->sendEmail(json_decode($job->workload(), true));
        });
    }

    /**
     * @param bool $debug
     */
    public function setDebug(bool $debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * @param bool $debug
     */
    public function job(bool $debug = false)
    {
        $this->setDebug($debug);

        while (true)
        {
            $this->worker->work();
            if ($this->worker->returnCode() != GEARMAN_SUCCESS) break;
        }
    }

    /**
     * @param array $data
     */
    private function sendEmail(array $data)
    {
        $this->debug($data);

        /**
         * TODO: SMTP MAIL SENDING
         */
    }

    /**
     * @param array $data
     */
    private function debug($data = [])
    {
        if ($this->debug && isset($data['to'])) {
            echo 'Sent to: ' . $data['to'] . PHP_EOL;
        }
    }
}