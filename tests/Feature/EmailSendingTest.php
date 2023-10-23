<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class EmailSendingTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEmailSending()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed');
        $faker = \Faker\Factory::create();
        $email = $faker->email();

        $registerResponse = $this->postJson("/api/register", [
            "name"      => $faker->firstname(),
            "email"     => $email,
            "password"  =>  "123456"
        ])->json();

        $loginResponse = $this->postJson("/api/login", [
            "email"     => $email,
            "password"  =>  "123456"
        ])->json();

        $this->assertEquals($registerResponse['user']['id'], $loginResponse['user']['id']);
        // Create a user instance for testing
        $requestData = [
            'emails' => [
                [
                    'body' => 'Test email body',
                    'subject' => 'Test email subject',
                    'email' => 'test@example.com',
                ],
            ],
        ];
        $response = $this->postJson("/api/{$loginResponse['user']['id']}/send?api_token={$loginResponse['token']}", $requestData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Emails are scheduled to be sent successfully',
            ]);

        $listResponse = $this->getJson("/api/{$loginResponse['user']['id']}/list?api_token={$loginResponse['token']}", $requestData);
        $listResponse->assertStatus(200)
            ->assertJson([
                "message"=> "Emails found",
            ]);
    }
}
