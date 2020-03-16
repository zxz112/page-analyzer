<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function testDomainsIndex()
    {
        $response = $this->get(route('index'));

        $response->assertStatus(200);
    }

    // public function testDomainsShow()
    // {

    //     $response = $this->get(route('show'));

    //     $response->assertStatus(200);
    // }
}
