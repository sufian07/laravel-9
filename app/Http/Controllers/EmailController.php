<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Models\User;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class EmailController extends Controller
{
    // TODO: finish implementing send method
    public function send(User $user, Request $request)
    {
        $apiToken = $request->query('api_token');
        $this->authorizer($apiToken, $user->id);
        $validator = Validator::make($request->all(), [
            'emails' => 'present|array',
            "emails.0" => 'required',
            'emails.*.body' => 'required|string',
            'emails.*.subject' => 'required|string',
            'emails.*.email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        foreach($request['emails'] as $email){
            SendEmail::dispatch($email);
        };
        return response()->json([
                'message' => 'Emails are scheduled to be sent successfully',
            ],
            200
        );
    }

    //  TODO - BONUS: implement list method
    public function list(User $user, Request $request)
    {
        $apiToken = $request->query('api_token');
        $page = $request->query('page') ?? 1;
        $perPage = $request->query('per_page') ?? 10;
        $this->authorizer($apiToken, $user->id);
        /** @var ElasticsearchHelperInterface $elasticsearchHelper */
        $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);
        // TODO: Create implementation for storeEmail and uncomment the following line
        $results = $elasticsearchHelper->listEmail($page, $perPage);
        return response()->json([
                'message' => 'Emails found',
                'total' => $results['hits']['total']['value'],
                'page' => $page,
                'per_page' => $perPage,
                'results' => collect($results['hits']['hits'])->map(function ($result) {
                    return [
                        'id' => $result['_id'],
                        'body' => $result['_source']['message_body'],
                        'subject' => $result['_source']['message_subject'],
                        'email' => $result['_source']['to_email_address'],
                    ];

                })
            ],
            200
        );
    }

    private function authorizer($apiToken, $userId){
        $token = PersonalAccessToken::findToken($apiToken)->first();
        $tokenUser = $token->tokenable;
        if($userId !== $tokenUser->id) {
            return response()->json([
                'message'=> 'Unauthorized'
            ], 401);
        }
    }
}
