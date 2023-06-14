<?php

namespace App\Http\Middleware;

use App\Services\MenuBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
                'roles' => Auth::user() ? Auth::user()->roles->pluck('name') : []
            ],
            'flash' => [
              'success' => fn () => $request->session()->get('success'),
              'info' => fn () => $request->session()->get('info')
            ],
            'sidebar_links' => MenuBuilder::build()
        ]);
    }
}
