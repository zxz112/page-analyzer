<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Domains;

class DomainsTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    public function testExample()
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
        $name = 'https://mail.ru';
        DB::table('domains')->insert(['name' => $name]);
        $id = DB::table('domains')->where('name', '=', $name)->value('id');
        $response = $this->get(route('show', $id));

        $response->assertStatus(200);
    }
}
