<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DonationPaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Enquiry;
use App\Services\MenuBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(): \Inertia\Response
    {
        $menu = MenuBuilder::build();
        return Inertia::render('Admin/Dashboard',[
            'total_donations' =>  Donation::where('payment_status',DonationPaymentStatus::PAID)->sum('amount'),
            'total_enquiries' => Enquiry::count(),
            'data' => $menu
        ]);
    }
}
