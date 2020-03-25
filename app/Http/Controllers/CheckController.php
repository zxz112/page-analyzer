<?php

namespace App\Http\Controllers;

use DiDom\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

class CheckController extends Controller
{
    protected $client;

    public function __construct(Client $guzzleClient)
    {
        $this->client = $guzzleClient;
    }

    public function store(Request $request)
    {
        $idDomain = $request->id;
        $domain = DB::table('domains')->find($idDomain);
        $domainName = $domain->name;
        try {
            $response = $this->client->request('GET', $domainName);
        } catch (RequestException $e) {
            flash('Error')->error();
            return redirect()->route('domain.show', $idDomain);
        }
        $statusCode = $response->getStatusCode();
        $htmlPage = $response->getBody()->getContents();
        $timeNow = Carbon::now()->toDateTimeString();
        $parsedSeoHtml = $this->parseSeoHtml($htmlPage);
        DB::table('domain_checks')->insert(['domain_id' => $idDomain, 'status_code' => $statusCode, 'created_at' => $timeNow, 'h1' => $parsedSeoHtml['h1'],
        'description' => $parsedSeoHtml['description'], 'keywords' => $parsedSeoHtml['keywords'], 'updated_at' => $timeNow, 'created_at' => $timeNow]);
        flash(' Website has been checked! ')->success();
        return redirect()->route('domain.show', $idDomain);
    }

    public function parseSeoHtml($htmlPage)
    {
        $document = new Document($htmlPage);
        $h1Html = $document->first('h1');
        $h1 = $h1Html ? $h1Html->text() : '';
        $descriptionHtml = $document->first('meta[name=description]');
        $description = $descriptionHtml ? $descriptionHtml->getAttribute('content') : '';
        $keywordsHtml = $document->first('meta[name=keywords]');
        $keywords = $keywordsHtml ? $keywordsHtml->getAttribute('content') : '';
        return [
            'h1' => $h1,
            'description' => $description,
            'keywords' => $keywords
        ];
    }
}
