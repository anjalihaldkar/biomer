<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CashfreePaymentTest extends TestCase
{
    use RefreshDatabase;

    private Customer $customer;
    private PaymentGateway $gateway;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
        ]);

        $this->gateway = PaymentGateway::create([
            'gateway_name' => 'cashfree',
            'display_name' => 'Cashfree Payment',
            'is_enabled' => true,
            'environment' => 'sandbox',
            'api_key' => 'TEST_KEY',
            'secret_key' => 'TEST_SECRET',
        ]);

        $this->product = Product::create([
            'name' => 'Test Biological Product',
            'slug' => 'test-biological-product',
            'sku' => 'TEST-SKU',
            'base_price' => 500.00,
            'status' => 'active',
            'manage_stock' => false,
        ]);
    }

    public function test_create_cashfree_order_sets_session_correctly(): void
    {
        Http::fake([
            'https://sandbox.cashfree.com/pg/orders' => Http::response([
                'payment_session_id' => 'fake_session_123',
                'order_id' => 'BB-123456',
            ], 200)
        ]);

        $cart = [
            [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'price' => 500.00,
                'quantity' => 1,
            ]
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->withSession(['cart' => $cart])
            ->postJson(route('order.cashfree'), [
                'name' => 'John Doe',
                'phone' => '1234567890',
                'email' => 'john@example.com',
                'address' => '123 Main St',
                'city' => 'Anytown',
                'state' => 'State',
                'pincode' => '123456',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'payment_session_id', 'cashfree_order_id']);

        // Check if cashfree_pending_order_id is bound in the user's session
        $this->assertTrue(session()->has('cashfree_pending_order_id'));
    }

    public function test_verify_cashfree_payment_fails_without_bound_session_order_id(): void
    {
        // Act as customer but do not start checkout step 1 (so session is empty of pending order ID)
        $response = $this->actingAs($this->customer, 'customer')
            ->withSession(['checkout_data' => []])
            ->getJson(route('order.cashfree.verify', ['order_id' => 'BB-123456']));

        $response->assertStatus(422)
            ->assertJsonPath('error', 'Invalid or expired payment session. Please start checkout again.');
    }

    public function test_verify_cashfree_payment_fails_when_order_id_mismatch(): void
    {
        // Act as customer and set a different pending order ID in session
        $response = $this->actingAs($this->customer, 'customer')
            ->withSession(['cashfree_pending_order_id' => 'BB-DIFFERENT'])
            ->getJson(route('order.cashfree.verify', ['order_id' => 'BB-123456']));

        $response->assertStatus(422)
            ->assertJsonPath('error', 'Invalid or expired payment session. Please start checkout again.');
    }

    public function test_verify_cashfree_payment_success_when_session_matches(): void
    {
        Http::fake([
            'https://sandbox.cashfree.com/pg/orders/BB-123456' => Http::response([
                'order_status' => 'PAID',
                'order_id' => 'BB-123456',
            ], 200),
            'https://sandbox.cashfree.com/pg/orders/BB-123456/payments' => Http::response([
                [
                    'cf_payment_id' => 'cf_pay_789',
                ]
            ], 200)
        ]);

        $cart = [
            [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'price' => 500.00,
                'quantity' => 1,
            ]
        ];

        $checkoutData = [
            'name' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'State',
            'pincode' => '123456',
            'notes' => '',
        ];

        $response = $this->actingAs($this->customer, 'customer')
            ->withSession([
                'cart' => $cart,
                'checkout_data' => $checkoutData,
                'cashfree_pending_order_id' => 'BB-123456',
            ])
            ->getJson(route('order.cashfree.verify', ['order_id' => 'BB-123456']));

        $response->assertStatus(200);

        // Verify session pending order ID is cleared
        $this->assertFalse(session()->has('cashfree_pending_order_id'));
    }
}
