<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Notifications\InvoiceCreated;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Rules\ValidCNPJ;
use Illuminate\Support\Facades\Validator;

/**
 * InvoiceController handles the invoice creation, update, deletion and viewing requests.
 */
class InvoiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Return a JSON listing of the user's resources.
     *
     * @param Request $request Request instance containing the user data.
     * @return InvoiceResource Collection of invoices.
     */
    public function index(Request $request)
    {
        $invoices = $request->user()->invoices;
        return InvoiceResource::collection($invoices);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request Request instance containing the invoice data.
     * @return InvoiceResource The newly created invoice.
     */
    public function store(Request $request)
    {
        try{
        $validator = Validator::make($request->all(), [
            'numero' => 'required|size:9',
            'valor' => 'required|numeric|min:0.01',
            'data_emissao' => 'required|date|before_or_equal:today',
            'cnpj_remetente' => ['required', new ValidCNPJ()],
            'nome_remetente' => 'required|string|max:100',
            'cnpj_transportador' => ['required', new ValidCNPJ()],
            'nome_transportador' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validatedData = $validator->validated();
        $validatedData['user_id'] = $request->user()->id;
        $invoice = Invoice::create($validatedData);

        $request->user()->notify(new InvoiceCreated($invoice));

            return new InvoiceResource($invoice);
//        return response()->json($invoice, 201);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while processing your request.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request Request instance containing the user and invoice id.
     * @param string $invoice_id
     * @return InvoiceResource The specified invoice.
     */
    public function show(Request $request, $invoice_id)
    {
        try {
            $invoice = Invoice::findOrFail($invoice_id);

            $this->authorize('view', $invoice);

            return new InvoiceResource($invoice);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Invoice not found'], 404);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while processing your request.'], 500);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request Request instance containing the updated invoice data.
     * @param Invoice $invoice The specified invoice to be updated.
     * @return JsonResponse JSON response containing the updated invoice.
     * @throws AuthorizationException If the user is not authorized to update the invoice.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $validator = Validator::make($request->all(), [
            'numero' => 'size:9',
            'valor' => 'numeric|min:0.01',
            'data_emissao' => 'date|before_or_equal:today',
            'cnpj_remetente' => [new ValidCNPJ()],
            'nome_remetente' => 'string|max:100',
            'cnpj_transportador' => [new ValidCNPJ()],
            'nome_transportador' => 'string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $invoice->update($validator->validated());

            return (new InvoiceResource($invoice))->additional(['message' => 'Invoice updated successfully.']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while processing your request.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Invoice $invoice The specified invoice to be deleted.
     * @return JsonResponse JSON response with a success message.
     */
    public function destroy(Invoice $invoice)
    {
        try {
            $this->authorize('delete', $invoice);
            $invoice->delete();

            return response()->json(['message' => 'Invoice deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while trying to delete the Invoice.', 'error' => $e->getMessage()], 500);
        }
    }
}
