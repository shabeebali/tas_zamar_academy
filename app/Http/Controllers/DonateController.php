<?php

namespace App\Http\Controllers;

use App\Enums\DonationPaymentStatus;
use App\Mail\DonateMail;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            'g-recaptcha-response' => [
                'required'
            ]
        ]);
        $res = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify',[
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response')
        ]);
        if($res->ok() && $res->json('success') === true) {
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

            $orderId = "ZMA" . $donation->id;
            $merchant_id = env('BAMBORA_MERCHANT_ID');
            $vAmount = number_format($request->input('amount'), 2, '.', '');
            $q = 'merchant_id=' . $merchant_id . '&trnAmount=' . $vAmount . '&trnOrderNumber=' . $orderId . '&declinedPage=' . urlencode(url('donate/response')) . '&approvedPage=' . urlencode(url('donate/response')) . '&ref1=' . $donation->id;
            $hashValue = sha1($q . env('BAMBORA_HASH_KEY'));
            $url = env('BAMBORA_URL') . '?' . $q . '&hashValue=' . $hashValue;
            return Response::redirectTo($url);
        }
        return Response::redirectToRoute('donate');
    }

    public function response(Request $request): RedirectResponse
    {
        $donation = Donation::find($request->input('ref1'));
        if(stripos($_REQUEST['messageText'],'Approved')!== false) {
            $message='Your Payment has been made Successfully!';
            $pay_status = DonationPaymentStatus::PAID;
            if($donation) {
                $data = $donation->toArray();
                $data['created_at'] = $donation->created_at->format('d/m/Y');
                Mail::to($donation->email)->bcc([
                    'info@zamarmusicacademy.ca',
                    'info@tastechnologies.com',
                ])
                ->send(new DonateMail($data));
            }
        }
        else if(stripos($_REQUEST['messageText'],'Canceled')!== false) {
            $message='Your Payment has been Cancelled. Please try Again!';
            $pay_status= DonationPaymentStatus::CANCELLED;
        }
        else{
            $message='You payment Failed. Please try Again!';
            $pay_status= DonationPaymentStatus::FAILED;
        }
        if($donation) {
            $donation->payment_status = $pay_status;
            $donation->payment_transaction_id = $request->input('trnId');
            $donation->payment_meta = $request->toArray();
            $donation->save();
        }
        return Response::redirectTo(route('donate').'?status=success')
            ->with(
                $pay_status == DonationPaymentStatus::PAID ? 'success' : 'info',
                $message
            );
    }

    public function testMail($id)
    {
        $donation = Donation::find($id);
        if($donation) {
            $data = $donation->toArray();
            $data['created_at'] = $donation->created_at->format('d/m/Y');
            Mail::to($donation->email)->bcc([
                'info@zamarmusicacademy.ca',
                'info@tastechnologies.com',
            ])
                ->send(new DonateMail($data));
        }
        return 'success';
    }
}
