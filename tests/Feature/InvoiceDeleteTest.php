<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Tests\TestCase;

class InvoiceDeleteTest extends TestCase
{
    public function testInvoiceDelete()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $invoice = Invoice::factory()->create(['user_id' => $user->id]);

        $response = $this->delete("/api/invoices/{$invoice->_id}");

        $response->assertStatus(200);

        $response = $this->get("/api/invoices/{$invoice->_id}");

        $response->assertStatus(404);
    }
}
