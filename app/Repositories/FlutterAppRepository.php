<?php

namespace app\Repositories;

use App\Models\FlutterApp;

class FlutterAppRepository
{
    public function getAll()
    {
        return FlutterApp::orderBy('id', 'desc');
    }

    /**
     * Find a app by it slug
     *
     * @param string $slug
     * @return void
     */
    public function getBySlug($slug)
    {
        return FlutterApp::where('slug', $slug)->firstOrFail();
    }

    /**
     * Find a app by it primary key
     *
     * @param integer $id
     * @return Collection
     */
    public function findById($id)
    {
        return FlutterApp::findOrFail($id);
    }

    /**
     * Create a new app.
     *
     * @param FlutterApp $app
     */
    public function store($app)
    {
        $app['slug'] = str_slug($app['title']);

        return FlutterApp::create($app);
    }

    /**
     * Update a app.
     *
     * @param FlutterApp $app
     */
    public function update($slug, $app)
    {
        return FlutterApp::where('slug', $slug)
                        ->update($app);
    }

    /**
     * Show a specified app.
     *
     * @param FlutterApp $slug
     */
    public function show($slug)
    {
        return FlutterApp::where('slug', $slug)->firstOrFail();
    }

    /**
     * Approve a specified app
     *
     * @param integer $id
     * @return Collection
     */
    public function approve($id)
    {
        return FlutterApp::where('id', $id)
                        ->update(['status' => 1]);
    }

    /**
     * Delete a specified app
     *
     * @param  FlutterApp $app
     * @return
     */
    public function delete($app)
    {
        return FlutterApp::destroy($app);
    }
}
