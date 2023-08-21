<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $page = (int)$request->input('page', 1);
        $sortBy = $request->input('sort');
        $descending = $request->input('descending', '0') == 1;
        $rowsPerPage = (int)$request->input('rpp', 20);
        $pages = Page::select(['title', 'url_key', 'id']);
        if ($sortBy && $sortBy !== 'null') {
            $pages->orderBy($sortBy, $descending ? 'DESC' : 'ASC');
        }
        $data = $pages->paginate($rowsPerPage);
        Inertia::share('title', 'Pages');
        return Inertia::render('Admin/Pages/Index', [
            'items' => $data->items(),
            'pagination' => [
                'page' => $page,
                'sort' => $sortBy ?? '',
                'rpp' => $rowsPerPage,
                'total' => $data->total(),
                'descending' => $descending
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        Inertia::share('title', 'Create Page');
        return Inertia::render('Admin/Pages/Create', [
            'pageTitle' => 'Create Page',
            'tiny_mce_url' => asset('assets/js/tinymce/tinymce.min.js'),
            'model' => [
                'title' => '',
                'content' => '',
                'url_key' => '',
                'meta_title' => '',
                'meta_keywords' => '',
                'meta_description' => ''
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        //dd($request->toArray());
        $request->validate([
            'title' => 'required',
            'url_key' => 'required|unique:pages',
        ]);

        $page = Page::create($request->only([
            'title',
            'content',
            'url_key',
            'meta_title',
            'meta_keywords',
            'meta_description'
        ]));

        return Response::redirectToRoute('admin.pages.index')->with('success', 'Page Saved Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): \Inertia\Response
    {
        $page = Page::find($id);
        Inertia::share('title', 'Edit Page');
        return Inertia::render('Admin/Pages/Create', [
            'pageTitle' => 'Create Page',
            'tiny_mce_url' => asset('assets/js/tinymce/tinymce.min.js'),
            'model' => [
                'id' => $page->id,
                'title' => $page->title,
                'content' => $page->content ?? '',
                'url_key' => $page->url_key,
                'meta_title' => $page->meta_title ?? '',
                'meta_keywords' => $page->meta_keywords ?? '',
                'meta_description' => $page->meta_description ?? ''
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        // dd($request->toArray());
        $page = Page::find($id);
        $request->validate([
            'title' => 'required',
            'url_key' => 'required|unique:pages,url_key,' . $id,
        ]);

        $page->fill($request->only([
            'title',
            'content',
            'url_key',
            'meta_title',
            'meta_keywords',
            'meta_description'
        ]));
        $page->save();

        return Response::redirectToRoute('admin.pages.index')->with('success', 'Page Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $page = Page::find($id);
        $page?->delete();
        return Redirect::back()->with('success', 'Page Deleted Successfully');
    }
}
