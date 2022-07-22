<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TelrGateway\TelrManager;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout');
    }

    public function store(Request $request)
    {

        $telrManager = new TelrManager();

        $billingParams = [
                'first_name' => $request->fname,
                'sur_name' => $request->lname,
                'address_1' =>  $request->address,
                'address_2' => 'None',
                'city' => $request->city,
                'region' => $request->area,
                'zip' => $request->zip,
                'country' => $request->country,
                'email' => $request->email,
            ];

            return $telrManager->pay(1, $request->amount , 'Telr Testing Youtube ...', $billingParams)->redirect();
        // return $telrManager->pay('ORDER_ID_GOES_HERE', 'TOTAL_AMOUNT', 'DESCRIPTION ...', $billingParams)->redirect();

    }

    public function success(Request $request)
    {
        $telrManager = new \TelrGateway\TelrManager();

        $transaction = $telrManager->handleTransactionResponse($request);

        $card_last_4 = $transaction->response['order']['card']['last4'];
        $name = $transaction->response['order']['customer']['name']['forenames']." ".$transaction->response['order']['customer']['name']['surname'];

        dd($transaction->response);
        return view('success')->with([
            'request'   =>  $request,
            'card_last_4'   =>  $card_last_4,
            'name'  =>  $name,
        ]);
    }

    public function cancel(Request $request)
    {
        $telrManager = new \TelrGateway\TelrManager();

        $transaction = $telrManager->handleTransactionResponse($request);
        dd($transaction->response);
        return view('cancel');
    }

    public function declined(Request $request)
    {
        $telrManager = new \TelrGateway\TelrManager();

        $transaction = $telrManager->handleTransactionResponse($request);
        dd($transaction->response);
        return view('decline');
    }
}
