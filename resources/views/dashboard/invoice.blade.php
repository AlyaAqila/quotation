<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice {{ $invoice->invoice_number }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ==========================================
           COLOR ADJUSTMENT & BASE STYLES
           ========================================== */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f4f7fb;
            color: #212529;
        }

        .btn-cancel {
            background: #e4e6eb;
            color: #333333;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: 0.2s ease;
            display: inline-flex;
            align-items: center;
        }

        .btn-cancel:hover {
            background: #d8dadf;
            text-decoration: none;
            color: #333333;
        }

        /* Container Card Setup */
        .invoice-slip {
            background: #ffffff;
            max-width: 900px;
            margin: 0 auto;
            border: 1px solid #d8dde3;
            border-radius: 4px;
            position: relative;
            padding: 0 !important; /* Reset outer padding for graphic banners */
            overflow: hidden;
        }

        /* Header & Footer Graphic Banner Containers */
        .quote-print-header,
        .quote-print-footer {
            width: 100%;
            display: block;
            line-height: 0;
            position: relative;
        }

        .quote-header-img,
        .quote-footer-img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Inner Body Area Content Padding */
        .invoice-body-content {
            padding: 0 30px 20px 30px;
        }

        /* Top Header Overlay Row (Logo on Left, Company Details on Right) */
        .quote-header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: -20px;   /* CHANGED: was -35px */
            position: relative;
            z-index: 10;
            margin-bottom: 10px; /* CHANGED: was 15px */
        }

        .quote-logo img {
            max-height: 100px;   /* CHANGED: was 80px */
            width: auto;
            display: block;
        }

        .quote-company-info {
            font-size: 0.80rem;
            line-height: 1.15;   /* CHANGED: was 1.4 */
            color: #212529;
        }

        .invoice-slip .quote-title {
            color: #333333;
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 0.5px;
        }

        .invoice-slip .quote-meta {
            font-size: 0.85rem;
            line-height: 1.2;
            color: #333333;
        }

        .invoice-slip .quote-hr {
            border-top: 2px solid #1c53a0;
            margin: 10px 0 20px 0;
            opacity: 1;
        }

        .invoice-slip .text-primary {
            color: #1c53a0 !important;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .invoice-slip .border {
            border-color: #d8dde3 !important;
        }

        .invoice-slip .row.mb-4 .border {
            background-color: #ffffff;
            border-radius: 4px;
            padding: 0.6rem 0.8rem !important;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .invoice-slip .bill-to-box strong {
            color: #111827;
        }

        .invoice-slip .info-title {
            color: #1c53a0 !important;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .invoice-slip .bg-light {
            background-color: #eef6fb !important;
            border-radius: 4px;
            padding: 0.75rem 1rem;
        }

        .invoice-slip table.table-bordered {
            border-color: #d8dde3;
        }

        .invoice-slip table thead.table-primary th {
            background: #1c53a0 !important;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 0.6rem 0.75rem;
            border-color: #1c53a0;
        }

        .invoice-slip table tbody td {
            vertical-align: top;
            font-size: 0.9rem;
        }

        .invoice-slip .table-light td {
            background: #1c53a0 !important;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .signature-line {
            border-bottom: 2px dashed #6c757d;
            margin-bottom: 8px;
        }

        .no-print .btn {
            border-radius: 6px;
            font-weight: 600;
        }

        .invoice-slip .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }

        .invoice-slip .meta-box {
            background-color: #ffffff;
            border: 1px solid #d8dde3 !important;
            border-radius: 4px;
            padding: 10px 14px !important;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .invoice-slip .project-box {
            background-color: #eef6fb !important;
            border: 1px solid #d8dde3 !important;
            border-radius: 4px;
            padding: 8px 14px !important;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }

        /* Printable Wrapper Table Layout */
        table.print-wrapper-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.print-wrapper-table td {
            padding: 0;
            border: none;
        }

        /* ==========================================
           PRINT ENGINE OVERRIDES & LAYOUT RULES
           ========================================== */
        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {
            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            .container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .invoice-slip {
                border: none !important;
                max-width: 100% !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            /* Repeating Print Structure */
            thead.repeat-print-header {
                display: table-header-group !important;
            }

            /* CHANGED: footer anchored to the bottom of every printed page (same as qtpreview) */
            .quote-print-footer {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                width: 100% !important;
                z-index: 1000;
            }

            /* ADDED: keep content clear of the fixed footer */
            .invoice-body-content {
                padding-bottom: 35mm !important;
            }

            /* Prevent logical breaking across physical pages */
            .meta-grid,
            .project-box,
            .table tr,
            .row.mb-4,
            .row.mt-5 {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            /* ADDED: page 2 block, same idea as qtpreview */
            .page-break-before {
                page-break-before: always !important;
                break-before: page !important;
            }

        }
    </style>
</head>
<body>

<div class="container my-4">

    {{-- CONTROLS (hidden when printing) --}}
    <div class="d-flex justify-content-end gap-2 mb-3 no-print">
        <a href="{{ route('quotations.invoice', $invoice->quotation_Id) }}" class="btn-cancel" style="text-decoration: none; display: inline-block;"> Back
        </a>
        @if(!$invoice->printed_date)
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#issueModal">
                <i class="fa-solid fa-file-invoice"></i> Set Dates & Issue
            </button>
        @else
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="fa-solid fa-pen"></i> Edit Condition & Description
            </button>
            <button type="button" class="btn btn-success" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Print
            </button>
        @endif
    </div>

    {{-- INVOICE SLIP --}}
    <div class="invoice-slip border" id="invoiceSlip">
        <table class="print-wrapper-table">

            <!-- 1. TOP HEADER BANNER GRAPHIC (REPEATS ON PRINT) -->
            <thead class="repeat-print-header">
                <tr>
                    <td>
                        <div class="quote-print-header">
                            <img src="{{ asset('images/header.jpeg') }}" alt="Header" class="quote-header-img">
                        </div>
                    </td>
                </tr>
            </thead>

            <!-- INNER BODY CONTENT -->
            <tbody>
                <tr>
                    <td>
                        <div class="invoice-body-content">

                            <!-- 2. OVERLAY LOGO (LEFT) & COMPANY ADDRESS DETAILS (RIGHT) -->
                            <div class="quote-header-content">
                                <div class="quote-logo">
                                    <img src="{{ asset('images/logo.jpeg') }}" alt="Eco Hydrotech Solutions Logo">
                                </div>
                                <div class="text-end quote-company-info">
                                    <strong>ECO HYDROTECH SOLUTIONS SDN. BHD. (1688434-T)</strong><br>
                                    Institute of Oceanography and Environment<br>
                                    Universiti Malaysia Terengganu<br>
                                    21030, Kuala Nerus, Terengganu<br>
                                    Malaysia
                                </div>
                            </div>

                            <!-- INVOICE TITLE + INVOICE NO / DATE -->
                            <div class="d-flex justify-content-between align-items-end quote-title-row">
                                <div>
                                    <h2 class="quote-title mb-0">INVOICE</h2>
                                </div>
                                <div class="text-end quote-meta">
                                    <div>Invoice No: <strong>{{ $invoice->invoice_number }}</strong></div>
                                    <div>Date: <strong>{{ $invoice->printed_date ? \Carbon\Carbon::parse($invoice->printed_date)->format('d F Y') : 'DRAFT - NOT YET PRINTED' }}</strong></div>
                                </div>
                            </div>
                            <hr class="quote-hr">

                            <!-- BILL TO & INVOICE DETAILS GRID -->
                            <div class="meta-grid">
                                <div class="meta-box">
                                    <div class="info-title">BILL TO</div>
                                    <strong>{{ $invoice->quotation->project->client->company_name ?? '-' }}</strong><br>
                                    <div>{!! nl2br(e($invoice->quotation->project->client->client_address ?? '-')) !!}</div>
                                    <div class="mt-2">
                                        <strong>Attn: {{ $invoice->quotation->project->pic_name ?? '-' }}</strong><br>
                                        <span class="text-muted" style="font-size: 0.78rem;">Project & Admin Executive</span>
                                    </div>
                                </div>

                                <div class="meta-box">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="info-title">Invoice Date</div>
                                            {{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y') : '-' }}
                                        </div>
                                        <div class="col-6">
                                            <div class="info-title">Due Date</div>
                                            {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d F Y') : '-' }}
                                        </div>
                                        <div class="col-6 mt-2">
                                            <div class="info-title">Payment Terms</div>
                                            @if($invoice->invoice_date && $invoice->due_date)
                                                Net {{ \Carbon\Carbon::parse($invoice->invoice_date)->diffInDays($invoice->due_date) }} Days
                                            @else
                                                -
                                            @endif
                                        </div>
                                        <div class="col-6 mt-2">
                                            <div class="info-title">Your Ref.</div>
                                            {{ $invoice->po_number ?? ($invoice->quotation->po_number ?? '-') }}
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="info-title">Our Ref.</div>
                                            {{ $invoice->quotation->quotation_no }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PROJECT DETAILS -->
                            <div class="project-box">
                                <span class="info-title">PROJECT</span><br>
                                <strong>{{ $invoice->quotation->project->name }}</strong>
                            </div>

                            <!-- ITEMS TABLE -->
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 5%;">No.</th>
                                        <th style="width: 65%;">Description</th>
                                        <th class="text-center" style="width: 10%;">Qty</th>
                                        <th class="text-end" style="width: 20%;">Amount (RM)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <strong>{{ $invoice->paymentTerm->name }} ({{ $invoice->paymentTerm->percentage }}%)</strong><br>
                                            {!! nl2br(e($invoice->description ?? $invoice->quotation->project->name)) !!}<br>
                                            <small class="text-muted">Pursuant to Quotation Ref. {{ $invoice->quotation->quotation_no }}</small>
                                        </td>
                                        <td class="text-center">1</td>
                                        <td class="text-end fw-bold">{{ number_format($invoice->paymentTerm->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                        <td class="text-end fw-bold">{{ number_format($invoice->paymentTerm->amount, 2) }}</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end fw-bold">TOTAL DUE</td>
                                        <td class="text-end fw-bold">{{ number_format($invoice->paymentTerm->amount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <p class="mb-4" style="font-size: 0.9rem;"><strong>Amount in words:</strong> Ringgit Malaysia {{ \App\Helpers\NumberToWords::convert($invoice->paymentTerm->amount) }} Only.</p>


                        <div class="page-break-before">

                            <!-- PAYMENT DETAILS & NOTES -->
                            <div class="row mb-4">
                                <div class="col-6 border p-3">
                                    <div class="fw-bold text-primary mb-1">PAYMENT DETAILS</div>
                                    <strong>Account Name:</strong> ECO HYDROTECH SOLUTIONS SDN. BHD.<br>
                                    <strong>Bank:</strong> MAYBANK ISLAMIC<br>
                                    <strong>Account No.:</strong> 5630 6496 5609
                                </div>
                                <div class="col-6 border p-3">
                                    <div class="fw-bold text-primary mb-1">PAYMENT NOTE</div>
                                    {!! nl2br(e($invoice->paymentTerm->condition ?? '-')) !!}<br>
                                    <small>Kindly quote the invoice number in the payment reference.</small>
                                </div>
                            </div>

                            <!-- SIGNATURE SECTION -->
                            <div class="row mt-5 pt-3">
                                <div class="col-6">
                                    <div class="fw-bold text-primary mb-4">Prepared by:</div>
                                    <div style="height: 60px;">
                                        <img src="{{ asset('images/signatures/prepared_by.png') }}" alt="Signature" style="max-height: 50px;" onerror="this.style.display='none'">
                                    </div>
                                    <div class="signature-line w-75"></div>
                                    <strong>Zur Mayassarah Binti Zolkemri</strong><br>
                                    <small class="text-muted">Operations & Administration Executive</small>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold text-primary mb-4">Approved by:</div>
                                    <div style="height: 60px;">
                                        <img src="{{ asset('images/signatures/approved_by.png') }}" alt="Signature" style="max-height: 50px;" onerror="this.style.display='none'">
                                    </div>
                                    <div class="signature-line w-75"></div>
                                    <strong>Ts. Dr. Mardiha Mokhtar</strong><br>
                                    <small class="text-muted">Technical Director</small>
                                </div>
                            </div>

                        </div>

                        </div> <!-- END .invoice-body-content -->
                    </td>
                </tr>
            </tbody>

        </table>

        <!-- CHANGED: footer moved out of <tfoot>, fixed to page bottom in print -->
        <div class="quote-print-footer">
            <img src="{{ asset('images/footer.jpeg') }}" alt="Footer" class="quote-footer-img">
        </div>

    </div> <!-- end #invoiceSlip -->

    </div>
</div>

{{-- ISSUE MODAL --}}
<div class="modal fade" id="issueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="issueForm">
                <div class="modal-header"><h5 class="modal-title">Set Invoice Dates</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Invoice Date</label>
                        <input type="date" class="form-control" name="invoice_date" required>
                    </div>
                    <div class="mb-3">
                        <label>Due Date</label>
                        <input type="date" class="form-control" name="due_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Confirm & Issue</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm">
                <div class="modal-header"><h5 class="modal-title">Edit Condition & Description</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea class="form-control" name="description">{{ $invoice->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Payment Note (Condition)</label>
                        <textarea class="form-control" name="condition">{{ $invoice->paymentTerm->condition }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.getElementById('issueForm')?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const res = await fetch('{{ route("invoices.issue", $invoice->invoice_Id) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        });
        if (res.ok) location.reload();
        else alert('Failed to issue invoice.');
    });

    document.getElementById('editForm')?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const res = await fetch('{{ route("invoices.updateDetails", $invoice->invoice_Id) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        });
        if (res.ok) {
            window.location.href = '{{ route("invoices.show", $invoice->invoice_Id) }}';
        } else {
            alert('Failed to update.');
        }
    });

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('autoprint') === '1') {
        setTimeout(() => window.print(), 300);
    }
    if (urlParams.get('openEdit') === '1') {
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }
});
</script>

</body>
</html>