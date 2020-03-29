<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainsTest extends TestCase
{
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
        $response = $this->post(route('domain.store', ['domain' => $domain]));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('domains', ['name' => $domain]);
    }

    public function testIndex()
    {
        $domains = [
            'https://laravel.com',
            'https://github.com'
        ];
        DB::table('domains')->insert([
            ['name' => $domains[0]],
            ['name' => $domains[1]]
        ]);
        $response = $this->get(route('domain.index'));

        $response->assertStatus(200);
    }

    public function testShow()
    {
        $name = 'https://yandex.ru';
        DB::table('domains')->insert(['name' => $name]);
        $id = DB::table('domains')->where('name', '=', $name)->value('id');
        $response = $this->get(route('domain.show', $id));

        $response->assertStatus(200);
    }
}
