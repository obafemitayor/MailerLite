<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubcriberTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_the_api_returns_unauthorized_response_when_there_is_no_auth_token_in_the_hearder()
    {
        $data = array("email"=>"obafemitayor@gmail.com", "name"=>"obafemi tayo");
        $response = $this->post('http://127.0.0.1:8000/api/addSubscriber', $data);
        $response->assertStatus(401);
    }

    public function test_the_api_validates_email_address_for_subscribed_data()
    {
        $data = array("email"=>"obafemitayor", "name"=>"obafemi tayo");
        $response = $this->withHeaders([
            'authtoken' => 'Value',
        ])->json('POST', 'http://127.0.0.1:8000/api/addSubscriber', $data);
        $response->assertStatus(400);
    }

    public function test_the_api_validates_name_for_subscribed_data()
    {
        $data = array("email"=>"obafemitayor@gmail.com", "name"=>"");
        $response = $this->withHeaders([
            'authtoken' => 'Value',
        ])->json('POST', 'http://127.0.0.1:8000/api/addSubscriber', $data);
        $response->assertStatus(400);
    }

    public function test_the_api_subscribes_user_successfully()
    {
        $data = array("email"=>"obafemitayor@gmail.com", "name"=>"obafemi tayo");
        $response = $this->withHeaders([
            'authtoken' => 'Value',
        ])->json('POST', 'http://127.0.0.1:8000/api/addSubscriber', $data);
        $response->assertStatus(200);
    }

    public function test_the_api_returns_not_found_when_unsubscribed_user_tries_to_subscribe_to_field()
    {
        $data = array("email"=>"obafemi_tayor@gmail.com", "title"=>"obafemi tayo",  "type"=>"type");
        $response = $this->withHeaders([
            'authtoken' => 'Value',
        ])->json('POST', 'http://127.0.0.1:8000/api/addSubscriberField', $data);
        $response->assertStatus(404);
    }
    public function test_the_api_adds_field_to_subscriber()
    {
        $data = array("email"=>"obafemitayor@gmail.com", "title"=>"obafemi tayo",  "type"=>"type");
        $response = $this->withHeaders([
            'authtoken' => 'Value',
        ])->json('POST', 'http://127.0.0.1:8000/api/addSubscriberField', $data);
        $response->assertStatus(200);
    }

    public function test_the_api_returns_unauthorized_for_inactive_subscriber()
    {
        $response = $this->withHeaders([
            'authtoken' => 'Value',
        ])->json('GET', 'http://127.0.0.1:8000/api/getSubscribersField?email=obafemitayorinactive@gmail.com&pageindex=0pagesize=10');
        $response->assertStatus(401);
    }

    public function test_the_api_returns_subscribed_fields_for_subscriber()
    {
        $data = array("email"=>"obafemitayor@gmail.com", "title"=>"obafemi tayo",  "type"=>"type");
        $response = $this->withHeaders([
            'authtoken' => 'Value',
        ])->json('GET', 'http://127.0.0.1:8000/api/getSubscribersField?email=obafemitayor@gmail.com&pageindex=0pagesize=10');
        $response->assertStatus(200);
    }

}
