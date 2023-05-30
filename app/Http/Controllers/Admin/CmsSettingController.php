<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class CmsSettingController extends Controller
{
    public function view(): \Inertia\Response
    {
        Inertia::share('title','CMS Settings');
        $headerContent = Settings::getByKey('header_content', '');
        $footerContent = Settings::getByKey('footer_content', '');
        return Inertia::render('Admin/CmsSettings',[
            'header_content' => $headerContent,
            'footer_content' => $footerContent
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        Settings::putByKey('header_content',$request->input('header_content',''));
        Settings::putByKey('footer_content',$request->input('footer_content',''));
        return Redirect::back()->with('success','Settings Saved Successfully');
    }
}
