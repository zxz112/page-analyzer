<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
class DomainsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

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
        $html = file_get_contents(__DIR__ . "/../fixtures/testSeo.html");
        $name = 'https://vk.com';
        $mock = new MockHandler([
            new Response(200, [], $html)
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $this->app->instance('GuzzleHttp\Client', $client);
        DB::table('domains')->insert(['name' => $name]);
        $id = DB::table('domains')->where('name', '=', $name)->value('id');
        $this->post(route('check', ['id' => $id]));
        $this->assertDatabaseHas('domain_checks', ['status_code' => 200 , 'domain_id' => $id, 'h1' => 'h1',
        'description' => 'description', 'keywords' => 'keywords']);
    }
}
