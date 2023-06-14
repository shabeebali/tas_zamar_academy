<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function edit($id): \Inertia\Response
    {
        $user = Admin::find($id);
        Inertia::share('title','Edit User');
        $data = $user->only(['id','name','email']);
        $roles = $user->roles;
        $data['roles'] = [];
        foreach ($roles as $role)
        {
            if($role->name !== 'super-admin') {
                $data['roles'][] = $role->name;
            }
        }
        return Inertia::render('Admin/Users/Create',[
            'pageTitle' => 'Edit User',
            'model' => $data
        ]);
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $user = Admin::find($id);
        //dd($request->toArray());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,'.$id,
            'roles' => 'required'
        ]);

        $user->fill($request->only([
            'name',
            'email',
        ]));
        $user->save();
        $isSuperAdmin = false;
        if($user->hasRole('super-admin')){
            $isSuperAdmin = true;
        }
        $user->syncRoles($request->input('roles'));
        if($isSuperAdmin) {
            $user->assignRole('super-admin');
        }
        $user->save();
        return Response::redirectToRoute('admin.users.index')->with('success','User Updated Successfully');
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'your_password' => [
                'required',
                function($attribute, $value, $fail) {
                    $authUser = Auth::user();
                    if(!Hash::check($value,$authUser->password)) {
                        $fail('Incorrect password');
                    }
                }
            ],
            'new_password' => 'required',
        ]);

        $user = Admin::find($id);
        $user->password = Hash::make($request->input('new_password'));
        return Response::redirectToRoute('admin.users.index')->with('success','User Updated Successfully');
    }
}
