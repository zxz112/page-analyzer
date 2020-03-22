<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

class DomainsTest extends TestCase
{    
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected function setUp():void
    {
        parent::setUp();
        $path = '/home/ilya/php-project-lvl3/tests/fixtures';
        $body = file_get_contents($path);
        $mock = new MockHandler([
            new Response(200, [], $body)
        ]);
        $handler = HandlerStack::create($mock);
        $this->app->bind('GuzzleHttp\Client', function ($app) use ($handler) {
            return new Client(['handler' => $handler]);
        });
    }

    public function testMain()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testStore()
    {
        $domain = 'https://mail.ru';
        $response = $this->post(route('store', ['domain' => $domain]));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('domains', ['name' => $domain]);
    }

    public function testIndex()
    {
        $response = $this->get(route('index'));

        $response->assertStatus(200);
    }

    public function testShow()
    {
        $name = 'https://yandex.ru';
        DB::table('domains')->insert(['name' => $name]);
        $id = DB::table('domains')->where('name', '=', $name)->value('id');
        $response = $this->get(route('show', $id));

        $response->assertStatus(200);
    }

    public function testCheck()
    {
        $name = 'https://vk.com';
        DB::table('domains')->insert(['name' => $name]);
        $id = DB::table('domains')->where('name', '=', $name)->value('id');
        $body = file_get_contents('/home/ilya/php-project-lvl3/tests/fixtures');
        // $mock = new MockHandler([
        //     new Response(200, [], $body)
        // ]);
        // $handler = HandlerStack::create($mock);
        // $this->app->bind('GuzzleHttp\Client', function ($app) use ($handler) {
        //     return new Client(['handler' => $handler]);
        // });
        // $this->mockHttpRequest([
        //     'statusCode' => 200,
        //     'headers' => [],
        //     'body' => $body
        //  ]);

        $this->post(route('check', ['id' => 5]));
        $this->assertDatabaseHas('domain_checks', ['status_code' => 200, 'domain_id' => $id, 'h1' => 'h1', 
        'description' => 'description', 'keywords' => 'keywords']);
    }

    /**
     * @param array $options
     */
    private function mockHttpRequest(array $options)
    {
        $this->app->bind(ClientInterface::class, function () use ($options) {
            $response = new Response(
                $options['statusCode'],
                $options['headers'],
                $options['body']
            );
            $mock = new MockHandler([$response]);
            $handlerStack = HandlerStack::create($mock);

            return new Client(['handler' => $handlerStack]);
        });
    }
}