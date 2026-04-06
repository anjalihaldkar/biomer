<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function company()
    {
        return view('settings/company');
    }
    
    public function currencies()
    {
        return view('settings/currencies');
    }
    
    public function language()
    {
        return view('settings/language');
    }
    
    public function notification()
    {
        return view('settings/notification');
    }
    
    public function notificationAlert()
    {
        return view('settings/notificationAlert');
    }
    
    public function paymentGateway()
    {
        $gateways = \App\Models\PaymentGateway::all();
        return view('settings/paymentGateway', compact('gateways'));
    }

    /**
     * Update payment gateway settings
     */
    public function updatePaymentGateway(Request $request)
    {
        $request->validate([
            'razorpay_enabled' => 'boolean',
            'razorpay_environment' => 'in:sandbox,production',
            'razorpay_key_id' => 'nullable|string',
            'razorpay_key_secret' => 'nullable|string',
            
            'cashfree_enabled' => 'boolean',
            'cashfree_environment' => 'in:sandbox,production',
            'cashfree_app_id' => 'nullable|string',
            'cashfree_secret_key' => 'nullable|string',
            
            'cod_enabled' => 'boolean',
        ]);

        // Update Razorpay
        \App\Models\PaymentGateway::updateOrCreate(
            ['gateway_name' => 'razorpay'],
            [
                'is_enabled' => $request->boolean('razorpay_enabled'),
                'environment' => $request->input('razorpay_environment'),
                'api_key' => $request->input('razorpay_key_id'),
                'secret_key' => $request->input('razorpay_key_secret'),
            ]
        );

        // Update Cashfree
        \App\Models\PaymentGateway::updateOrCreate(
            ['gateway_name' => 'cashfree'],
            [
                'is_enabled' => $request->boolean('cashfree_enabled'),
                'environment' => $request->input('cashfree_environment'),
                'api_key' => $request->input('cashfree_app_id'),
                'secret_key' => $request->input('cashfree_secret_key'),
            ]
        );

        // Update COD
        \App\Models\PaymentGateway::updateOrCreate(
            ['gateway_name' => 'cod'],
            [
                'is_enabled' => $request->boolean('cod_enabled'),
            ]
        );

        return redirect()->back()->with('success', 'Payment gateway settings updated successfully!');
    }
    
    public function theme()
    {
        return view('settings/theme');
    }
    
}
