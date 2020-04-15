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
        $lastChecksId = DB::table('domain_checks')->select(DB::raw('MAX(id)'))->groupBy('domain_id');
        $checks = DB::table('domain_checks')->whereIn('id', $lastChecksId)->get()->keyBy('domain_id');
        return view('domains.index', compact(['domains', 'checks']));
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
        return redirect()->route('domains.index');
    }
}
