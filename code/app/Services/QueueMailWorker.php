<?php

namespace App\Services;

use App\Contracts\QueueWorker as QueueWorkerContract;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class QueueMailWorker
 */
class QueueMailWorker implements QueueWorkerContract
{
    /**
     * @var \GearmanWorker
     */
    private $worker;

    /**
     * @var PHPMailer
     */
    private $mailer;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * QueueMailWorker constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;

        $this->worker = new \GearmanWorker();

        $this->worker->addServer($config['queue']['server'], $config['queue']['port']);

        $this->worker->addFunction('sendmail', function($job) {
            $this->sendEmail(json_decode($job->workload(), true));
        });

        /** SMTP PHP mailer */
        $this->mailer = new PHPMailer(true); // Passing `true` enables exceptions
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

        try {
            $this->mailerConfig($this->config['mail']);

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($data['to']);     // Name is optional
            $this->mailer->isHTML(false);               // Set email format to HTML
            $this->mailer->Subject = $data['subject'];
            $this->mailer->Body    = $data['body'];

            $this->mailer->send();

        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $this->mailer->ErrorInfo;
        }
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

    /**
     * @param $config
     * @throws \PHPMailer\PHPMailer\Exception
     */
    private function mailerConfig($config) // $config['email']
    {
        // Server settings
        if ($config['driver'] == 'smtp') {
            $this->mailer->isSMTP();                                // Set mailer to use SMTP
            $this->mailer->SMTPAuth     = true;                     // Enable SMTP authentication
            $this->mailer->Host         = $config['host'];          // Specify main and backup SMTP servers
            $this->mailer->Port         = $config['port'];          // TCP port to connect to
            $this->mailer->Username     = $config['username'];      // SMTP username
            $this->mailer->Password     = $config['password'];      // SMTP password
            $this->mailer->SMTPSecure   = $config['encryption'];    // Enable TLS encryption, `ssl` also accepted
        }
        
        $this->mailer->setFrom($config['from'], $config['from_name']);
    }
}