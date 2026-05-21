<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function checkoutData(): array
    {
        return [
            'customer_name'    => 'Jean Dupont',
            'customer_email'   => 'jean@example.com',
            'customer_phone'   => '0600000000',
            'delivery_address' => '1 rue de la Paix, 75001 Paris',
            'notes'            => '',
        ];
    }

    public function test_guest_cannot_access_checkout(): void
    {
        $this->get(route('checkout.show'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_checkout(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->actingAs($user);
        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1]);

        $this->get(route('checkout.show'))->assertStatus(200);
    }

    public function test_checkout_fails_with_empty_cart(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('checkout.store'), $this->checkoutData())
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_order_created_from_cart(): void
    {
        Mail::fake();

        $user    = User::factory()->create();
        $product = Product::factory()->create(['price' => 99.99, 'stock_quantity' => 5]);

        $this->actingAs($user);

        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 2]);

        $this->post(route('checkout.store'), $this->checkoutData())
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id'        => $user->id,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'total'          => 199.98,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id'   => $product->id,
            'quantity'     => 2,
            'unit_price'   => 99.99,
            'line_total'   => 199.98,
        ]);
    }

    public function test_cart_cleared_after_checkout(): void
    {
        Mail::fake();

        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->actingAs($user);
        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1]);
        $this->post(route('checkout.store'), $this->checkoutData());

        $this->get(route('cart.index'))
            ->assertDontSee($product->name);
    }

    public function test_checkout_fails_when_stock_insufficient(): void
    {
        Mail::fake();

        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 1]);

        $this->actingAs($user);

        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1]);

        $product->update(['stock_quantity' => 0]);

        $this->post(route('checkout.store'), $this->checkoutData())
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_emails_sent_on_order_creation(): void
    {
        Mail::fake();

        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->actingAs($user);
        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1]);
        $this->post(route('checkout.store'), $this->checkoutData());

        Mail::assertSentCount(2);
    }
}
