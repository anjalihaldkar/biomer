<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Razorpay Gateway
        PaymentGateway::updateOrCreate(
            ['gateway_name' => 'razorpay'],
            [
                'display_name' => 'Razorpay',
                'logo_url' => null,
                'is_enabled' => false,
                'environment' => 'sandbox',
                'api_key' => '',
                'secret_key' => '',
                'additional_config' => [],
            ]
        );

        // Cashfree Gateway
        PaymentGateway::updateOrCreate(
            ['gateway_name' => 'cashfree'],
            [
                'display_name' => 'Cashfree',
                'logo_url' => null,
                'is_enabled' => false,
                'environment' => 'sandbox',
                'api_key' => '',
                'secret_key' => '',
                'additional_config' => [],
            ]
        );

        // COD Gateway
        PaymentGateway::updateOrCreate(
            ['gateway_name' => 'cod'],
            [
                'display_name' => 'Cash on Delivery',
                'logo_url' => null,
                'is_enabled' => false,
                'environment' => 'production',
                'api_key' => '',
                'secret_key' => '',
                'additional_config' => [],
            ]
        );

        $this->command->info('Payment Gateway seeder executed successfully!');
    }
}
