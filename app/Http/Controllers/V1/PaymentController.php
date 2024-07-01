<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment\Flutterwave;
use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use App\Services\Payment\FlutterwaveService;
use App\Services\Payment\MonnifyService;
use App\Services\Payment\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(request()->query('reference')) {

            $paystack = new PaystackService;

            $clientResponse = json_encode(request()->all());

            $response = $paystack->processPayment(json_decode($clientResponse));

            if ($response) {
                session()->flash('success', 'Payment Successful & Wallet Funded');
                return redirect()->route('dashboard');
            }

        }

        if (request()->query('status') == 'successful') {
            
            $flutter = new FlutterwaveService;

            $clientResponse = json_encode(request()->all());

            $response = $flutter->processPayment(json_decode($clientResponse));

           if ($response) {
                session()->flash('success', 'Payment Successful & Wallet Funded');
                return redirect()->route('dashboard');
           }
        }

        if (request()->query('paymentReference')) {
            $monnify = new MonnifyService;
            $response = json_encode(request()->all());
            $response = $monnify->processPayment(json_decode($response));
            
            if ($response) {
                session()->flash('success', 'Payment Successful & Wallet Funded');
                return redirect()->route('dashboard');
            }
        }

        return view('pages.payment.index', [
            'payments'  =>  PaymentGateway::whereStatus(true)->get(),
            'accounts'  => VirtualAccount::whereUserId(auth()->id())->get()
        ]);
    }

    public function process(Request $request)
    {
       $request->validate([
            'amount'    =>  'required|numeric',
            'gateway'   =>  'required',
       ]);

       if ($request->gateway !== 'paystack' && $request->gateway !== 'flutterwave' && $request->gateway !== 'monnify') {
            session()->flash('error', 'Unable to process your payment. Please try again.');
            return redirect()->route('payment.index');
       }

       if ($request->gateway === 'paystack') return $this->payWithPaystack($request);

       if ($request->gateway === 'flutterwave') return $this->payWithFlutterwave($request);

       if ($request->gateway === 'monnify') return $this->payWithMonnify($request);
    }


    private function payWithPaystack(Request $request)
    {
        $paystack = new PaystackService;
        $response = $paystack->createPaymentIntent(
            $request->amount,
            route('payment.index'),
            auth()->user(),
            [
                'token'   =>  $request->_token,
            ]
        );

        $response = json_decode($response);

        if (isset($response->status)) {
            if ($response->status) {
                return redirect()->to($response->paymentLink);
            }
            session()->flash('error', 'Unable to process your payment. Please try again.');
            return redirect()->route('payment.index');
        }
        session()->flash('error', 'Unable to process your payment. Please try again.');
        return redirect()->route('payment.index');
    }

    private function payWithFlutterwave(Request $request)
    {
        $flutter = new FlutterwaveService();
        $response = $flutter->createPaymentIntent(
            $request->amount,
            route('payment.index'),
            auth()->user(),
            [
                'token'   =>  $request->_token,
            ] 
        );
        $response = json_decode($response);
        if (isset($response->status)) {
            if ($response->status == 'success') {
                return redirect()->to($response->paymentLink);
            }
            session()->flash('error', 'Unable to process your payment. Please try again.');
            return redirect()->route('payment.index');
        }
        session()->flash('error', 'Unable to process your payment. Please try again.');
        return redirect()->route('payment.index');
    }

    private function payWithMonnify(Request $request)
    {
        $monnify = new MonnifyService();
        $response = $monnify->createPaymentIntent(
            $request->amount,
            route('payment.index'),
            auth()->user(),
            [
                'token'   =>  $request->_token,
            ] 
        );

        $response = json_decode($response);

        if (isset($response->status)) {
            if ($response->message == 'success') {
                return redirect()->to($response->paymentLink);
            }
            session()->flash('error', 'Unable to process your payment. Please try again.');
            return redirect()->route('payment.index');
        }

        session()->flash('error', 'Unable to process your payment. Please try again.');
        return redirect()->route('payment.index');
    }


}
