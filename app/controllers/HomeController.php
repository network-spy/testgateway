<?php

use Illuminate\Support\Facades\Validator;
use PGateway\PaymentData;
use PGateway\PGatewayFactory;

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index()
	{
        return View::make('index', [
            'currencyList' => PaymentData::$currencyList
        ]);
	}

    public function paymentProcess()
    {
        $rules = array(
            'amount' => 'required|numeric|min:0',
            'currency' => 'in:'.implode(',', array_keys(PaymentData::$currencyList)),
            'customer_full_name' => 'required|max:128',
            'cc_holder_name' => 'required|max:128',
            'cc_number' => 'required||numeric|regex:/^\d{13,19}$/',
            'cc_expiration' => 'required|regex:/^\d{2}\/\d{4}$/',
            'cc_ccv2' => 'required|numeric|regex:/^\d{3,4}$/'
        );

        $messages = [
            'cc_holder_name.required' => 'Credit card holder name is required',
            'cc_holder_name' => 'Credit card holder name can not be more than 128 letters',
            'cc_number.required' => 'Credit card number is required',
            'cc_number.numeric' => 'Credit card number has to be numeric',
            'cc_number.regex' => 'Wrong credit card number',
            'cc_expiration.required' => 'Credit card expiration date is required',
            'cc_expiration.regex' => 'Wrong credit card expiration date',
            'cc_ccv2.required' => 'Credit card CVV is required',
            'cc_ccv2.numeric' => 'Credit card CVV has to be numeric',
            'cc_ccv2.regex' => 'Wrong credit card CVV'
        ];

        $formData = Input::all();

        $validator = Validator::make($formData, $rules, $messages);
        if ($validator->fails()) {
            return Redirect::route('index')->withErrors($validator)->withInput();
        }

        $currency = PaymentData::$currencyList[ (int)$formData['currency'] ];
        $isAMEX = PaymentData::getCCTypeByNumber($formData['cc_number']) === 'american express';
        if ($isAMEX && $currency !== 'USD') {
            return Redirect::route('index')->withInput()->withErrors([
                'AMEX is possible to use only for USD'
            ]);
        }

        $expirationDate = explode('/', $formData['cc_expiration']);

        $paymentData = new PaymentData();
        $paymentData->setAmount($formData['amount'])
            ->setCurrency($currency)
            ->setCustomerFullName($formData['customer_full_name'])
            ->setCCHolderName($formData['cc_holder_name'])
            ->setCCExpirationMonth($expirationDate[0])
            ->setCCExpirationYear($expirationDate[1])
            ->setCCNumber($formData['cc_number'])
            ->setCCV2($formData['cc_ccv2']);

        $paymentSystem = null;
        if ($isAMEX) { // if credit card type is AMEX, then use Paypal
            $paymentSystem = PGatewayFactory::create('PayPal');
        } else {
            switch($currency) { //if currency is USD, EUR, or AUD, then use Paypal
                case 'USD':
                case 'EUR':
                case 'AUD':
                    $paymentSystem = PGatewayFactory::create('PayPal');
                    break;
                default: //otherwise use Braintree
                    $paymentSystem = PGatewayFactory::create('BrainTree');
            }
        }

        $paymentId = $paymentSystem->pay($paymentData);
        if($paymentId) {
            DB::table('payments_history')->insert([
                'ph_payment_system' => $paymentSystem->getPSName(),
                'ph_payment_id' => $paymentId,
                'ph_date' => (new DateTime())->format('Y-m-d H:i:s'),
                'ph_amount' => $formData['amount'],
                'ph_currency' => $currency,
                'ph_customer_name' => $formData['customer_full_name'],
                'ph_cc_number' => $formData['cc_number']
            ]);
            return View::make('index', [
                'message' => 'Payment was successful',
                'currencyList' => PaymentData::$currencyList
            ]);
        } else {
            return Redirect::route('index')->withInput()->withErrors($paymentSystem->getErrors());
        }
    }

}
