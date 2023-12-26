<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Tests\TestCase;

class InvoiceUpdateTest extends TestCase
{
    public function testInvoiceUpdate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $invoice = Invoice::factory()->create(['user_id' => $user->id]);

        $updatedInvoiceData = [
            'numero' => '987654321',
            'valor' => 200.00,
            'data_emissao' => now()->format('Y-m-d'),
            'cnpj_remetente' => '98765432000198',
            'nome_remetente' => 'New Name',
            'cnpj_transportador' => '98765432000198',
            'nome_transportador' => 'Updated Delivery Man'
        ];
        $response = $this->putJson("/api/invoices/{$invoice->id}", $updatedInvoiceData);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Invoice updated successfully.']);
        $this->assertDatabaseHas('invoices', $updatedInvoiceData);
    }
}
