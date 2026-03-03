<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;

class DeclareRabbitMQQueues extends Command
{
    protected $signature = 'rabbitmq:declare-queues';
    protected $description = 'Declare RabbitMQ queues so workers can consume without errors';

    public function handle(): int
    {
        $queue   = config('queue.connections.rabbitmq.queue', 'default');
        $attempt = 0;

        while (true) {
            try {
                /** @var RabbitMQQueue $connection */
                $connection = Queue::connection('rabbitmq');
                $connection->declareQueue($queue, true, false);
                $this->info("Queue '{$queue}' declared.");
                return self::SUCCESS;
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt >= 30) {
                    $this->error("Could not connect to RabbitMQ after {$attempt} attempts: {$e->getMessage()}");
                    return self::FAILURE;
                }
                $this->warn("RabbitMQ not ready (attempt {$attempt}), retrying in 2s...");
                sleep(2);
            }
        }
    }
}
