<?php

namespace App\Http\Controllers;

use App\Enums\DonationPaymentStatus;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class DonateController extends Controller
{
    public function donate(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'location' => 'required',
            'amount' => 'numeric|gte:10',
            'designation' => 'required',
            'address' => 'required',
        ]);
        $donation = new Donation();
        $donation->fill($request->only([
            'name',
            'email',
            'location',
            'amount',
            'designation',
            'address',
            'comment'
        ]));
        $donation->payment_status = DonationPaymentStatus::PENDING;
        $donation->save();

        $orderId="ZMA".$donation->id;
        $merchant_id = env('BAMBORA_MERCHANT_ID');
        $vAmount=number_format($request->input('amount'),2,'.','');
        $q = 'merchant_id='.$merchant_id.'&trnAmount='.$vAmount.'&trnOrderNumber='.$orderId.'&declinedPage='.urlencode(url('donate/response')).'&approvedPage='.urlencode(url('donate/response')).'&ref1='.$donation->id;
        $hashValue=sha1($q.env('BAMBORA_HASH_KEY'));
        $url = env('BAMBORA_URL').'?'.$q.'&hashValue='.$hashValue;
        return Response::redirectTo($url);
    }

    public function response(Request $request): RedirectResponse
    {
        if(stripos($_REQUEST['messageText'],'Approved')!== false) {
            $message='Your Payment has been made Successfully!';
            $pay_status = DonationPaymentStatus::PAID;
        }
        else if(stripos($_REQUEST['messageText'],'Canceled')!== false) {
            $message='Your Payment has been Cancelled. Please try Again!';
            $pay_status= DonationPaymentStatus::CANCELLED;
        }
        else{
            $message='You payment Failed. Please try Again!';
            $pay_status= DonationPaymentStatus::FAILED;
        }
        $donation = Donation::find($request->input('ref1'));
        if($donation) {
            $donation->payment_status = $pay_status;
            $donation->payment_transaction_id = $request->input('trnId');
            $donation->payment_meta = $request->toArray();
            $donation->save();
        }
        return Response::redirectToRoute('donate')
            ->with(
                $pay_status == DonationPaymentStatus::PAID ? 'success' : 'info',
                $message
            );
    }
}
