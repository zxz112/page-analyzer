<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DiDom\Document;

class DomainController extends Controller
{
    protected $client;

    public function __construct(Client $guzzleClient)
    {
        $this->client = $guzzleClient;
    }
    
    public function main()
    {
        return view('domains.main');
    }

    public function show($id)
    {
        $domain = DB::table('domains')->find($id);
        $checks = DB::table('domain_checks')->where('domain_id', $id)->orderBy('updated_at')->get();
        return view('domains.show', compact(['domain', 'checks']));
    }

    public function index()
    {
        // DB::table('domain_checks')->select('domain_id', 'status_code')->groupBy('domain_id')->latest();
        $LastChecked = DB::table('domain_checks')->whereRaw('id in (SELECT MAX(id) FROM domain_checks GROUP BY domain_id)');
        $domains = DB::table('domains')->select('domains.id', 'name', 'status_code')->leftJoinSub($LastChecked, 'lastCheck', function ($join) {
            $join->on('domains.id', '=', 'lastCheck.domain_id');
        })->get();
        // DB::select('Select * from domain_checks where id in (SELECT MAX(id) FROM domain_checks GROUP BY domain_id) right JOIN domains on domains.id = domain_checks.domain_id');
        // $lastStatus = DB::table('domain_checks')->select('domain_id', 'status_code')->groupBy('domain_id', 'status_code');
        // $domains = DB::table('domains')->leftJoinSub($lastStatus, 'last_status', function ($join) {
        //     $join->on('domains.id', '=', 'last_status.domain_id');
        // })->get();
        // $lastStatus = DB::table('domain_checks')->select('domain_id', 'status_code')->groupBy('domain_id')->orderBy('updated_at');
        // $domains = DB::select('SELECT domains.id, domains.name, 
        // (SELECT status_code FROM domain_checks WHERE domains.id = domain_checks.domain_id ORDER BY updated_at DESC LIMIT 1) 
        // AS status FROM domains 
        // left JOIN domain_checks ON domains.id = domain_checks.domain_id GROUP BY domains.id');
        return view('domains.index', compact('domains'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required|url',
        ]);
        $domain = parse_url($request->input('domain'));
        $scheme = $domain['scheme'];
        $host = $domain['host'];
        $correctUrl = "{$scheme}://{$host}";
        $timeNow = Carbon::now()->toDateTimeString();
        $countUrl = DB::table('domains')->where('name', $correctUrl)->count();
        if ($countUrl > 0) {
            DB::table('domains')->where('name', $correctUrl)->update(['updated_at' => $timeNow]);
            flash('Url already exists ')->success();
        } else {
            DB::table('domains')->insert(['name' => $correctUrl, 'created_at' => $timeNow, 'updated_at' => $timeNow]);
            flash('Url has been added  ')->success();
        }
        return redirect()->route('index');
    }

    public function check(Request $request)
    {
        $idDomain = $request->id;
        $domain = DB::table('domains')->find($idDomain);
        $domainName = $domain->name;
        try {
            $response = $this->client->request('GET', $domainName);
        } catch (RequestException $e) {
            flash('Error')->error();
            return redirect()->route('show', $idDomain);
        }
        $statusCode = $response->getStatusCode();
        $htmlPage = $response->getBody()->getContents();
        $timeNow = Carbon::now()->toDateTimeString();
        $parsedSeoHtml = $this->parseSeoHtml($htmlPage);
        DB::table('domain_checks')->insert(['domain_id' => $idDomain, 'status_code' => $statusCode, 'created_at' => $timeNow, 'h1' => $parsedSeoHtml['h1'],
        'description' => $parsedSeoHtml['description'], 'keywords' => $parsedSeoHtml['keywords'], 'updated_at' => $timeNow, 'created_at' => $timeNow]);
        flash(' Website has been checked! ')->success();
        return redirect()->route('show', $idDomain);
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
