<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    public function main()
    {
        $user = DB::table('domains')->get();
        return view('main', compact('user'));
    }

    public function show($id)
    {
        $domain = DB::table('domains')->find($id);
        $checks = DB::table('domain_checks')->where('domain_id', $id)->orderBy('updated_at')->get();
        return view('show', compact(['domain', 'checks']));
    }

    public function index()
    {
        // $last = DB::table('domain_checks')->select('status_code', 'domain_id', 'id')->groupBy('domain_id')->get();
        // dump($last);
        $domains = DB::select('select domains.id as ide, domains.name as nameDom, max(domain_checks.updated_at) as updated from domains
        left join domain_checks on ide = domain_checks.domain_id group by ide, nameDom order by ide');

        return view('index', compact('domains'));
    }

    public function store(Request $request)
    {
        $domain = parse_url($request->input('domain'));
        if (!array_key_exists('scheme', $domain) || !array_key_exists('host', $domain)) {
            flash('Uncorrect URL ')->error();
            return redirect()->route('main');
        }
        $scheme = $domain['scheme'];
        $host = $domain['host'];
        $correctUrl = "{$scheme}://{$host}";
        $timeNow = Carbon::now()->toDateTimeString();
        $countUrl = DB::table('domains')->where('name', $correctUrl)->count();
        if ($countUrl > 0) {
            DB::table('domains')->where('name', $correctUrl)->update(['updated_at' => $timeNow]);
        } else {
            DB::table('domains')->insert(['name' => $correctUrl, 'created_at' => $timeNow, 'updated_at' => $timeNow]);
        }
        flash('Url already exists ')->success();
        return redirect()->route('index');
    }

    public function check(Request $request)
    {
        $idDomain = $request->id;
        $domain = DB::table('domains')->find($idDomain);
        $domainName = $domain->name;
        $client = new Client();
        try {
            $res = $client->request('GET', $domainName);
        } catch (RequestException $e) {
            flash('Error')->error();
            return redirect()->route('show', $idDomain);
        }
        $statusCode = $res->getStatusCode();
        $timeNow = Carbon::now()->toDateTimeString();
        DB::table('domain_checks')->insert(['domain_id' => $idDomain, 'status_code' => $statusCode, 'created_at' => $timeNow, 'h1' => '', 'description' => '', 'updated_at' => $timeNow]);
        flash('Url was checked ')->success();
        return redirect()->route('show', $idDomain);
    }
}
