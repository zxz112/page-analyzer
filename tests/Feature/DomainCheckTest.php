<?php

namespace Tests\Feature;

use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;

class CheckTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
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
        $this->post(route('domain.check.store', ['id' => $id]));
        $this->assertDatabaseHas('domain_checks', [
            'status_code' => 200,
            'domain_id' => $id,
            'h1' => 'h1',
            'description' => 'description',
            'keywords' => 'keywords'
        ]);
    }
}
