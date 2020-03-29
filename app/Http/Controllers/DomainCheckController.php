<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use App\Seo;

class DomainCheckController extends Controller
{
    protected $client;

    public function __construct(Client $guzzleClient)
    {
        $this->client = $guzzleClient;
    }

    public function store($id)
    {
        $domain = DB::table('domains')->find($id);
        $domainName = $domain->name;
        try {
            $response = $this->client->request('GET', $domainName);
        } catch (RequestException $e) {
            flash('Error')->error();
            return redirect()->route('domain.show', $id);
        }
        $statusCode = $response->getStatusCode();
        $htmlPage = $response->getBody()->getContents();
        $timeNow = Carbon::now()->toDateTimeString();
        $parsedSeoHtml = Seo::parseSeoHtml($htmlPage);
        DB::table('domain_checks')->insert([
            'domain_id' => $id,
            'status_code' => $statusCode,
            'created_at' => $timeNow,
            'h1' => $parsedSeoHtml['h1'],
            'description' => $parsedSeoHtml['description'],
            'keywords' => $parsedSeoHtml['keywords'],
            'updated_at' => $timeNow,
            'created_at' => $timeNow
        ]);
        flash(' Website has been checked! ')->success();
        return redirect()->route('domain.show', $id);
    }
}
