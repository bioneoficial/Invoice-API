<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Models\User;
use Tests\TestCase;

class InvoiceTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
//        Invoice::truncate();
//        User::truncate();
    }


    /**
     * Test the creation of an invoice.
     *
     * @return void
     */
    public function testCanCreateInvoiceInDatabase()
    {
        $invoice = Invoice::factory()->create();

        $this->assertDatabaseHas('invoices', [
            '_id' => $invoice->id
        ]);
    }

    public function testCanGetUserInvoices()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->get('/api/invoices');

        $response->assertStatus(200);
    }

    public function testCanPostInvoiceThroughController()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/invoices', [
            'numero' => '123456789',
            'valor' => 100.00,
            'data_emissao' => now()->format('Y-m-d'),
            'cnpj_remetente' => '98765432000198',
            'nome_remetente' => 'Operating Person',
            'cnpj_transportador' => '98765432000198',
            'nome_transportador' => 'Delivery Man'
        ]);

        $response->assertStatus(201);
    }

    public function testCanGetSpecificInvoice()
    {
        $user = User::factory()->create();
        $invoice = Invoice::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user, 'api');

        $response = $this->get("/api/invoices/{$invoice->_id}");

        $response->assertStatus(200);
    }

    public function testCanUpdateExistingInvoiceThroughController()
    {
        $user = User::factory()->create();
        $invoice = Invoice::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user, 'api');

        $updatedInvoiceData = [
            'numero' => '987654321',
            'valor' => 200.00,
            'data_emissao' => now()->format('Y-m-d'),
            'cnpj_remetente' => '98765432000198',
            'nome_remetente' => 'New Name',
            'cnpj_transportador' => '98765432000198',
            'nome_transportador' => 'Updated Delivery Man'
        ];
        $response = $this->putJson("/api/invoices/{$invoice->_id}", $updatedInvoiceData);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Invoice updated successfully.']);
    }

    public function testCanDeleteInvoiceThroughController()
    {
        $user = User::factory()->create();
        $invoice = Invoice::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user, 'api');

        $response = $this->delete("/api/invoices/{$invoice->_id}");

        $response->assertStatus(200);
    }
}
