<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Contracts\QueueWorker as QueueWorkerContract;

class AppSendMailCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:send-mail';

    protected $queueWorker;

    public function __construct(QueueWorkerContract $queueWorker)
    {
        parent::__construct();

        $this->queueWorker = $queueWorker;
    }

    protected function configure()
    {
        $this
            ->setDescription('Send emails from gearman job queue')
            ->setHelp('Console worker for sending emails')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('debug', 'd'),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Started at ' . date('d.m.Y H:i:s'));

        if ($input->getOption('debug')) {
            $output->writeln('Debug mode enabled');
        }

        $this->queueWorker->job($input->getOption('debug'));
    }
}