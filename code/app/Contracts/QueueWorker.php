<?php

namespace App\Contracts;

/**
 * Interface QueueWorker
 */
interface QueueWorker
{
    /**
     * @param bool $debug
     */
    public function job(bool $debug = false);
}