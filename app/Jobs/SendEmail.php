<?php

namespace App\Jobs;

use App\Mail\MotivationEmail;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var ElasticsearchHelperInterface $elasticsearchHelper */
        $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);
        // TODO: Create implementation for storeEmail and uncomment the following line
        $email = $elasticsearchHelper->storeEmail($this->email['body'], $this->email['subject'], $this->email['email']);

        /** @var RedisHelperInterface $redisHelper */
        $redisHelper = app()->make(RedisHelperInterface::class);
        // TODO: Create implementation for storeRecentMessage and uncomment the following line
        $redisHelper->storeRecentMessage($email["_id"], $this->email['subject'], $this->email['body'], $this->email['email']);

        Mail::to($this->email['email'])->send(new MotivationEmail($this->email['subject'], $this->email['body']));
    }
}
