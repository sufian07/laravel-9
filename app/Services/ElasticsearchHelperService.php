<?php
namespace App\Services;

use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Elasticsearch;

class ElasticsearchHelperService implements ElasticsearchHelperInterface {
    /**
     * Store the email's message body, subject and to address inside elasticsearch.
     *
     * @param  string  $messageBody
     * @param  string  $messageSubject
     * @param  string  $toEmailAddress
     * @return mixed - Return the id of the record inserted into Elasticsearch
     */
    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress): mixed {
        return Elasticsearch::index([
            'body' => [
                'message_body' => $messageBody,
                'message_subject' => $messageSubject,
                'to_email_address' => $toEmailAddress,
            ],
            'index' => 'emails',
            'type' => 'email',
        ]);
    }

    public function listEmail($page = 1, $perPage = 10): mixed {

        return Elasticsearch::search([
            'index' => 'emails',
            'type' => 'email',
            'body'  => [
                "from"=> ($page -1) * $perPage,
                "size"=> $perPage,
                'query' => [
                    "match_all" => (object)[]
                ]
            ]
        ]);
    }

}
