<?php

namespace App\Http\Controllers;

use App\Models\FlutterArtifact;
use Illuminate\Http\Request;

class FlutterArtifactController extends Controller
{
    public function index($tld = '')
    {
        if ($tld == 'dev') {
            return redirect('https://flutterx.com');
        }

        if (request()->clear_cache) {
            cache()->forget('flutter-artifact-list');
            return redirect('/')->with('status', 'App cache has been cleared!');
        }

        if (auth()->check() && auth()->user()->is_admin) {
            $artifacts = FlutterArtifact::latest()->get();
        } else {
            $artifacts = cache('flutter-artifact-list');
        }

        $data = [
            'artifacts' => $artifacts,
            'banner' => getBanner(),
        ];

        if (!isGL()) {
            $data['useBlackHeader'] = true;
        }

        return view('flutter_artifacts.index', $data);
    }

    public function search()
    {
        $data = [];
        $search = strtolower(request()->search);
        $artifacts = FlutterArtifact::approved()->search($search)->get();

        foreach ($artifacts as $artifact)
        {
            $index = strpos(strtolower($artifact->contents), $search);
            $str = substr($artifact->contents, $index, 800);
            $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');

            $obj = new \stdClass;
            $obj->id = $artifact->id;
            $obj->contents = $str;

            $data[] = $obj;
        }

        return response()->json($data);
    }

    public function update()
    {
        \Artisan::call('itsallwidgets:load_artifacts');

        return 'done';
    }

    public function show($tld, $artifact = false)
    {
        if (! $artifact) {
            $artifact = $tld;
        }

        $data = [
            'artifact' => $artifact,
        ];

        if (!isGL()) {
            $data['useBlackHeader'] = true;
        }

        return view('flutter_artifacts.show', $data);
    }

    public function hide($tld, $artifact = false)
    {
        if (! $artifact) {
            $artifact = $tld;
        }

        if (isGL()) {
            $artifact->is_visible = false;
            $artifact->save();
        }

        return redirect(url('/') . '?clear_cache=true');
    }

    public function jsonFeed(Request $request)
    {
        $artifacts = cache('flutter-artifact-list');
        $data = [];

        foreach ($artifacts as $artifact) {
            $data[] = $artifact->toObject();
        }

        return response()->json($data)->withCallback($request->input('callback'));;
    }

    public function sitemap()
    {
        $str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        $str .= '<url><loc>' . config('app.url') . '</loc><lastmod>' . date('Y-m-d') . '</lastmod><changefreq>daily</changefreq><priority>1</priority></url>';

        $artifacts = cache('flutter-artifact-list');

        foreach ($artifacts as $artifact) {
            $str .= '<url>'
            . '<loc>' . $artifact->url() . '</loc>'
            . '<lastmod>' . $artifact->updated_at->format('Y-m-d') . '</lastmod>'
            . '<changefreq>weekly</changefreq>'
            . '<priority>0.5</priority>'
            . '</url>';
        }

        $str .= '</urlset>';

        return response($str)->header('Content-Type', 'application/xml');
    }
}
