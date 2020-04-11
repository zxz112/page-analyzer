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
    public function testStore()
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
        $domain = DB::table('domains')->where('name', '=', $name)->first();
        $response = $this->post(route('domains.checks.store', $domain->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $response->assertRedirect();
        $this->assertDatabaseHas('domain_checks', [
            'status_code' => 200,
            'domain_id' => $domain->id,
            'h1' => 'h1',
            'description' => 'description',
            'keywords' => 'keywords'
        ]);
    }
}
