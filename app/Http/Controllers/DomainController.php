<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
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
        $domains = DB::table('domains')->orderBy('id')->get();
        $lastChecks = DB::table('domain_checks')->latest()->groupBy('domain_id')->get();
        $domainsWithChecks = $domains->map(function ($domain) use ($lastChecks) {
            $domain->status_code = collect($lastChecks->firstWhere('domain_id', $domain->id))->get('status_code');
            return $domain;
        });
        return view('domains.index', compact('domainsWithChecks'));
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
            DB::table('domains')->insert([
                'name' => $correctUrl,
                'created_at' => $timeNow,
                'updated_at' => $timeNow
            ]);
            flash('Url has been added  ')->success();
        }
        return redirect()->route('domain.index');
    }
}
