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

    public function store($domainId)
    {
        $domain = DB::table('domains')->find($domainId);
        try {
            $response = $this->client->request('GET', $domain->name);
        } catch (RequestException $e) {
            flash('Error')->error();
            return redirect()->route('domain.show', $domain->id);
        }
        $statusCode = $response->getStatusCode();
        $htmlPage = $response->getBody()->getContents();
        $timeNow = Carbon::now()->toDateTimeString();
        $parsedSeoHtml = Seo::parseSeoHtml($htmlPage);
        DB::table('domain_checks')->insert([
            'domain_id' => $domain->id,
            'status_code' => $statusCode,
            'h1' => $parsedSeoHtml['h1'],
            'description' => $parsedSeoHtml['description'],
            'keywords' => $parsedSeoHtml['keywords'],
            'updated_at' => $timeNow,
            'created_at' => $timeNow
        ]);
        flash(' Website has been checked! ')->success();
        return redirect()->route('domains.show', $domain->id);
    }
}
