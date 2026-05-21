<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_empty_cart(): void
    {
        $this->get(route('cart.index'))->assertStatus(200);
    }

    public function test_can_add_product_to_cart(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 2,
        ])->assertRedirect();

        $this->get(route('cart.index'))
            ->assertStatus(200)
            ->assertSee($product->name);
    }

    public function test_cannot_add_inactive_product_to_cart(): void
    {
        $product = Product::factory()->inactive()->create();

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ])->assertRedirect();

        $this->get(route('cart.index'))
            ->assertDontSee($product->name);
    }

    public function test_cannot_add_out_of_stock_product(): void
    {
        $product = Product::factory()->outOfStock()->create();

        $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity'   => 1,
        ])->assertRedirect();

        $this->get(route('cart.index'))
            ->assertDontSee($product->name);
    }

    public function test_can_update_cart_quantity(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1]);

        $this->patch(route('cart.update'), [
            'product_id' => $product->id,
            'quantity'   => 3,
        ])->assertRedirect();

        $this->get(route('cart.index'))->assertSee('3');
    }

    public function test_can_remove_product_from_cart(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1]);

        $this->delete(route('cart.remove', $product->id))->assertRedirect();

        $this->get(route('cart.index'))
            ->assertDontSee($product->name);
    }

    public function test_can_clear_cart(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1]);

        $this->delete(route('cart.clear'))->assertRedirect();

        $this->get(route('cart.index'))
            ->assertDontSee($product->name);
    }
}
