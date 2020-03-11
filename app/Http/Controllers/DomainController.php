<?php

namespace App\Http\Controllers;

require '../vendor/autoload.php';

use Carbon\Carbon;

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
        return view('show', compact('domain'));
    }

    public function index()
    {
        $domains = DB::table('domains')->get();
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
}
