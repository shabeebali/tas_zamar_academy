<?php

namespace App\Http\Controllers;

use App\Mail\EnquiryMail;
use App\Models\Enquiry;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class EnquiryController extends Controller
{
    public function contact(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'g-recaptcha-response' => [
                'required'
            ],
        ]);
        $res = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify',[
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response')
        ]);
        if($res->ok() && $res->json('success') === true) {
            $model = new Enquiry();
            $model->fill($request->only('name','email','phone','comment'));
            $model->form_origin = 'contact_page';
            $model->save();
            try {
                Mail::to($model->email)
                    ->bcc([
                        'info@tastechnologies.com',
                    ])
                    ->send(new EnquiryMail($model->toArray()));
            } catch (Exception $e) {
                Log::debug($e->getMessage());
            }
        } else {
            Log::error('Google Recaptcha Failed for contact form',[
                'request' => $request->toArray(),
                'google_response' => $res->json()
            ]);
            return Redirect::back()->with(['error' => 'Recaptcha Failed']);
        }
        return Response::redirectTo('thankyou');
    }
}
