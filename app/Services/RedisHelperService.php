<?php
namespace App\Services;

use App\Utilities\Contracts\RedisHelperInterface;
use Elasticsearch;
use Illuminate\Support\Facades\Redis;

class RedisHelperService implements RedisHelperInterface {
    /**
     * Store the email's message body, subject and to address inside elasticsearch.
     *
     * @param  string  $messageBody
     * @param  string  $messageSubject
     * @param  string  $messageBody
     * @param  string  $toEmailAddress
     * @return mixed - Return the id of the record inserted into Elasticsearch
     */
    public function storeRecentMessage(mixed $id, string $messageSubject, string $messageBody, string $toEmailAddress): void{
        Redis::set('email:'.$id,  [
            'message_body' => $messageBody,
            'message_subject' => $messageSubject,
            'to_email_address' => $toEmailAddress,
        ]);
    }
}
