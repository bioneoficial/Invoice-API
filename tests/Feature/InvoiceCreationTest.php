<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class InvoiceCreationTest extends TestCase
{
    /**
     * Test the process of user login and invoice creation.
     *
     * @return void
     */
    public function testUserCanLoginAndCreateInvoice()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $invoiceData = [
            'numero' => '123456789',
            'valor' => 100.00,
            'data_emissao' => now()->format('Y-m-d'),
            'cnpj_remetente' => '98765432000198',
            'nome_remetente' => 'Operating Person',
            'cnpj_transportador' => '98765432000198',
            'nome_transportador' => 'Delivery Man'
        ];

        $response = $this->postJson('/api/invoices', $invoiceData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('invoices', $invoiceData);
    }
}
