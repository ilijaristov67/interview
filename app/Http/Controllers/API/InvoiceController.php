<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = Invoice::all();
        return response()->json([
            'success' => true,
            'invoices' => $invoices,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer',
            'due_date' => 'required|date',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
            'items.*.amount' => 'required|numeric',
        ]);
        $clientId = $request->client_id;
        $date = $request->due_date;
        $totalAmount = 0;

        foreach ($request->items as $item) {
            $totalAmount += $item['amount'];
        }

        $invoice = Invoice::create([
            'client_id' => $clientId,
            'total_amount' => $totalAmount,
            'due_date' => $date,
        ]);

        foreach ($request->items as $item) {
            DB::table('invoices_items')->insert([
                'invoice_id' => $invoice->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'amount' => $item['amount'],
            ]);
        }

        return new InvoiceResource($invoice);
    }


    public function destroy($id)
    {

        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return response()->json([
            'success' => true,
            'message' => 'Invoice Deleted'
        ]);
    }

    public function update(Request $request, $id)
    {

        $invoice = Invoice::findOrFail($id);
        $clientId = $request->client_id;
        $date = $request->due_date;
        $totalAmount = 0;

        foreach ($request->items as $item) {
            $totalAmount += $item['amount'];
        }
        $invoice->update([
            'client_id' => $clientId,
            'total_amount' => $totalAmount,
            'due_date' => $date,
        ]);
        DB::table('invoices_items')->where('invoice_id', $invoice->id)->delete();
        foreach ($request->items as $item) {
            DB::table('invoice_items')->insert([
                'invoice_id' => $invoice->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'amount' => $item['amount'],
            ]);
        }
        return new InvoiceResource($invoice);
    }
}
