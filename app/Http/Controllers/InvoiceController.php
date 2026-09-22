<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PaymentTerm;
use App\Models\QtInvoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function create($quotationId, $termId)
    {
        // 1. Get the quotation and the specific original term the user clicked
        $quotation = QtInvoice::findOrFail($quotationId);

        $originalTerm = PaymentTerm::where('id', $termId)
            ->where('quotation_Id', $quotationId)
            ->firstOrFail();

        // 2. Check if this term already has an invoice (prevents duplicates)
        $existingCopy = PaymentTerm::where('source_term_id', $originalTerm->id)
            ->whereNotNull('invoice_Id')
            ->first();

        if ($existingCopy) {
            // Already generated before — just show that invoice instead of creating a new one
            return redirect()->route('invoices.show', $existingCopy->invoice_Id);
        }

        // 3. Not generated yet — create the invoice + copy the term
        $invoice = DB::transaction(function () use ($quotation, $originalTerm) {

            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber($quotation->project_Id, $originalTerm->id),
                'quotation_Id'   => $quotation->quotation_Id,
                'invoice_date'   => null,   // set later, on Print
                'printed_date'   => null,
                'due_date'       => null,
                'description'    => null,
                'status'         => 'draft',
                'created_by'     => Auth::id(),
            ]);

            PaymentTerm::create([
                'quotation_Id'   => null,
                'invoice_Id'     => $invoice->invoice_Id,
                'source_term_id' => $originalTerm->id,   // remembers where this came from
                'name'           => $originalTerm->name,
                'percentage'     => $originalTerm->percentage,
                'condition'      => $originalTerm->condition,
                'amount'         => $originalTerm->amount,
                'created_by'     => Auth::id(),
            ]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice->invoice_Id);
    }

    public function show($invoiceId)
    {
        $invoice = Invoice::with(['paymentTerm', 'quotation'])->findOrFail($invoiceId);

        return view('dashboard.invoice', compact('invoice'));
    }

    private function generateInvoiceNumber($projectId, $originalTermId): string
    {
        $year = now()->format('Y');

        $lockedProject = Project::where('project_Id', $projectId)
            ->lockForUpdate()
            ->first();

        // Get ALL original (quotation-side) payment term IDs under this project,
        // ordered by creation - this fixes the numbering regardless of click order
        $termIdsInOrder = PaymentTerm::whereHas('quotation', function ($q) use ($projectId) {
                $q->where('project_Id', $projectId);
            })
            ->whereNull('invoice_Id')   // only original terms, not invoice-side copies
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $position = array_search($originalTermId, $termIdsInOrder);
        $runningNumber = $position !== false ? $position + 1 : count($termIdsInOrder) + 1;

        return sprintf('%s-INV/%d/%03d', $lockedProject->number, $year, $runningNumber);
    }

    public function issue(Request $request, $invoiceId)
    {
        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'due_date'     => 'required|date|after_or_equal:invoice_date',
        ]);

        $invoice = Invoice::findOrFail($invoiceId);

        $invoice->update([
            'invoice_date' => $validated['invoice_date'],
            'due_date'     => $validated['due_date'],
            'printed_date' => now(),
            'status'       => 'unpaid',
            'updated_by'   => Auth::id(),
        ]);

        return response()->json(['success' => true, 'invoice' => $invoice]);
    }

    public function updateDetails(Request $request, $invoiceId)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:1000',
            'condition'   => 'nullable|string|max:1000',
        ]);

        $invoice = Invoice::with('paymentTerm')->findOrFail($invoiceId);

        $invoice->update([
            'description' => $validated['description'] ?? null,
            'updated_by'  => Auth::id(),
        ]);

        if ($invoice->paymentTerm) {
            $invoice->paymentTerm->update([
                'condition'  => $validated['condition'] ?? null,
                'updated_by' => Auth::id(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}