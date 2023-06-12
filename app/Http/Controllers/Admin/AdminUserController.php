<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $page = (int)$request->input('page',1);
        $sortBy = $request->input('sort');
        $descending = $request->input('descending','0') == 1;
        $rowsPerPage = (int)$request->input('rpp',20);
        $users = Admin::with(['roles'])->whereHas('roles', function (Builder $q) {
            $q->where('name','<>','super-admin');
        });
        if($sortBy && $sortBy !== 'null') {
            $users->orderBy($sortBy,$descending ? 'DESC' : 'ASC');
        }
        $data = $users->paginate($rowsPerPage);
        Inertia::share('title','Users');
        return Inertia::render('Admin/Users/Index',[
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

    public function create(): \Inertia\Response
    {
        Inertia::share('title','Create User');
        return Inertia::render('Admin/Users/Create',[
            'pageTitle' => 'Create User',
            'model' => [
                'name' => '',
                'email' => '',
                'password' => '',
                'roles' => [],
            ]
        ]);
    }
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        //dd($request->toArray());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:8',
            'roles' => 'required'
        ]);

        $admin = new Admin($request->only([
            'name',
            'email',
        ]));
        $admin->password = Hash::make($request->input('password'));
        $admin->save();
        $admin->syncRoles($request->input('roles'));

        return Response::redirectToRoute('admin.users.index')->with('success','User Created Successfully');
    }
}
