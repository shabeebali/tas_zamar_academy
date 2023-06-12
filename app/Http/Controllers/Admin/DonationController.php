<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class DonationController extends Controller
{
    public function index(Request $request): Response
    {
        $page = (int)$request->input('page',1);
        $sortBy = $request->input('sort');
        $descending = $request->input('descending','0') == 1;
        $rowsPerPage = (int)$request->input('rpp',20);
        $enquiries = Donation::select(['id','name','email','amount','payment_status','created_at']);
        if($sortBy && $sortBy !== 'null') {
            $enquiries->orderBy($sortBy,$descending ? 'DESC' : 'ASC');
        }
        $data = $enquiries->paginate($rowsPerPage);
        Inertia::share('title','Donations');
        return Inertia::render('Admin/Donations/Index',[
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

    public function show($id): array|\Illuminate\Database\Eloquent\Model|Donation|\Illuminate\Database\Eloquent\Collection|null
    {
        return Donation::find($id);
    }

    public function destroy($id): RedirectResponse
    {
        $enquiry = Donation::find($id);
        $enquiry?->delete();
        return Redirect::back()->with('success','Record Deleted Successfully');
    }
}
