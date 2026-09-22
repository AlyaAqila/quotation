<?php

namespace App\Http\Controllers;

use App\Models\QtInvoice;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Fetch records from the 'qt_invoice' table and send to history view.
     * Supports keyword search and filtering by month.
     */
    public function index(Request $request)
    {
        // 1. Initialize query
        $query = QtInvoice::with(['project.client']);

        // 2. Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('quotation_no', 'like', "%{$search}%")
                ->orWhereHas('project', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('number', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($cq) use ($search) {
                            $cq->where('company_name', 'like', "%{$search}%");
                        });
                });
            });
        }

        // 3. Month Filter
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->input('month'));
        }

        // 4. Fetch ordered records (newest first)
        $quotations = $query->orderBy('created_at', 'desc')->get();

        return view('dashboard.history', compact('quotations'));
    }

    /**
     * Delete a row from 'qt_invoice' table by quotation_Id.
     */
    public function destroy($id)
    {
        // Find row where quotation_Id matches $id
        $invoice = QtInvoice::where('quotation_Id', $id)->firstOrFail();
        
        // Delete child line items first to prevent orphan records
        $invoice->items()->delete();
        
        // Delete parent quotation row from database
        $invoice->delete();

        return redirect()->back()->with('success', 'Quotation deleted successfully!');
    }
}