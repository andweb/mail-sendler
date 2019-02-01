<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QueueMailSendler extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:send-mail';

    protected function configure()
    {
        $this
            ->setDescription('Send emails from gearman job queue')
            ->setHelp('Help');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Send!');
    }
}