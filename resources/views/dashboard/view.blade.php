{{-- resources/views/dashboard/view.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation #{{ $quotation->quotation_no }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        /* =========================================================
           GLOBAL & PREVIEW STYLES
           (sizing / spacing now matched to qtpreview.blade.php)
           ========================================================= */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #212529;
            margin: 0;
            padding: 0;
        }

        /* Screen Preview Container */
        .page-container {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            position: relative;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            padding: 0 !important;
        }

        /* Outer Table Wrapper Layout */
        table.print-wrapper-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.print-wrapper-table td {
            padding: 0;
            border: none;
        }

        /* Header & Footer Image Containers */
        .quote-print-header,
        .quote-print-footer {
            width: 100%;
            display: block;
            line-height: 0;
            position: relative; /* CHANGED: matches qtpreview */
        }

        .quote-header-img,
        .quote-footer-img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Top Header Row Layout (Page 1 Only: Logo on Left, Company Info on Right) */
        .quote-header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: -50px; /* Slight negative margin to overlay header graphic smoothly */
            position: relative;
            z-index: 10;
            padding: 0;
            margin-bottom: 10px; /* CHANGED: added, matches qtpreview */
        }

        .quote-logo img {
            max-height: 100px;
            width: auto;
            display: block;
        }

        .quote-company-info {
            font-size: 0.80rem;   /* CHANGED: was 1rem */
            line-height: 1.15;    /* CHANGED: was 1.4 */
            color: #212529;
        }

        /* Main Content Padding */
        .quote-body {
            padding: 10px 40px 100px 40px; /* CHANGED: bottom was 20px */
        }

        .quote-title-row { margin-top: 10px; }
        .quote-title {
            font-size: 1.4rem;   /* CHANGED: was 1.6rem */
            font-weight: 700;
            color: #333;
            letter-spacing: 0.5px;
        }
        .quote-meta {
            font-size: 0.85rem;
            line-height: 1.2;    /* CHANGED: added */
            color: #333;
        }
        .quote-hr { border-top: 2px solid #212529; margin: 10px 0 20px 0; opacity: 1; }

        .quote-box {
            background-color: #dfeefc !important;
            border-radius: 4px;
            padding: 0.75rem 1rem;
            line-height: 1.2;    /* CHANGED: added */
            font-size: 0.85rem;  /* CHANGED: added */
        }

        /* CHANGED: added, matches qtpreview */
        .quote-box div {
            line-height: 1.2;
            margin-top: 2px !important;
        }

        .quote-box-light { background-color: #eef6fb !important; }
        .quote-box-label { font-weight: 700; color: #1c53a0; font-size: 0.85rem; text-transform: uppercase; }

        .quote-details-strip {
            display: flex;
            gap: 24px;
            font-size: 0.82rem;
            color: #495057;
            flex-wrap: wrap;
        }

        .quote-table { width: 100%; border-collapse: collapse; }
        .quote-table thead th {
            background-color: #1c6e7a !important;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.4rem 0.75rem;   /* CHANGED: was 0.6rem */
            border: none;
        }
        .quote-table tbody td {
            background-color: #f5f5f5 !important;
            padding: 0.35rem 0.75rem;  /* CHANGED: was 0.6rem */
            font-size: 0.85rem;
            line-height: 1.25;         /* CHANGED: added */
            border-bottom: none !important; /* CHANGED: was 4px solid #fff */
        }

        /* ADDED: grouped rows, same as qtpreview */
        .quote-table tbody td.quote-module-cell {
            background-color: #d6e9ec !important;
            color: #1c6e7a;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding-top: 0.45rem;
            padding-bottom: 0.45rem;
        }

        .quote-table tbody td.quote-service-cell {
            font-weight: 600;
            padding-left: 1.5rem;
            background-color: #ececec !important;
            padding-top: 0.4rem;
            padding-bottom: 0.4rem;
        }

        .quote-table tbody td.quote-item-cell {
            padding-left: 2.75rem;
        }

        .quote-module-row,
        .quote-service-row {
            break-after: avoid;
            page-break-after: avoid;
        }        

        .quote-totals-table { width: 280px; font-size: 0.9rem; }
        .quote-totals-table td { padding: 0.3rem 0.75rem; } /* CHANGED: was 0.4rem */
        .quote-totals-table tr:not(.quote-grand-total-row) td:last-child { background-color: #f0f0f0 !important; }
        .quote-grand-total-row td { background-color: #1c6e7a !important; color: #ffffff !important; font-weight: 700; }

        .quote-section-heading { color: #1c53a0; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.5rem; }
        .quote-two-col { font-size: 0.85rem; line-height: 1.3; } /* CHANGED: line-height added */
        .quote-two-col strong { font-weight: 700; }             /* CHANGED: added */
        .quote-signature p { font-size: 0.9rem; line-height: 1.25; } /* CHANGED: line-height added */


        /* =========================================================
           PRINT SPECIFIC OVERRIDES (matched to qtpreview.blade.php)
           ========================================================= */

        /* Remove default browser margins so header touches page top edge */
        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {
            html, body {
                background: #ffffff;
                margin: 0;
                padding: 0;
                width: 100%;
            }

            /* ADDED: remove Bootstrap's grey background and padding in print */
            body.bg-light {
                background-color: #ffffff !important;
                padding: 0 !important;
            }

            .quote-page {
                min-height: 251mm;
                box-sizing: border-box;
            }

            .page-container {
                border: none;
                width: 100%;
                max-width: 100%;
                box-shadow: none;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            /* REPEATING HEADER: banner image repeats on every page */
            thead.repeat-print-header {
                display: table-header-group !important;
            }

            /* CHANGED: footer is now anchored strictly to the bottom of every printed page
               (replaces the old <tfoot> approach and the 100vh table height) */
            .quote-print-footer {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                width: 100% !important;
                z-index: 1000;
            }

            /* CHANGED: body spacing so content clears the fixed footer */
            .quote-body {
                padding-top: 10px !important;
                padding-bottom: 35mm !important;  /* was 20px */
                padding-left: 15mm !important;
                padding-right: 15mm !important;
            }

            /* FORCE PAGE BREAK: Payment Terms, Payment Info and Signature go to page 2 */
            .page-break-before {
                page-break-before: always !important;
                break-before: page !important;
                padding-top: 25mm !important; /* CHANGED: was 15mm, clears top banner on page 2 */
            }

            /* Avoid breaking items mid-element */
            .quote-title-row,
            .quote-box,
            .quote-box-light,
            .quote-details-strip,
            .quote-totals-table,
            .quote-two-col,
            .quote-signature {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .quote-table tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            a {
                text-decoration: none !important;
                color: inherit !important;
            }
        }
    </style>
</head>
<body class="bg-light py-4">

<!-- ACTION BAR (HIDDEN IN PRINT VIEW) -->
<div class="container max-w-4xl mb-3 no-print">
    <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm">
        <a href="{{ url('/history') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to History</a>
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="bi bi-printer-fill"></i> Print / Save PDF
        </button>
    </div>
</div>

<!-- A4 PAGE CONTAINER -->
<div class="page-container">

    <table class="print-wrapper-table">

        <!-- 
            ===================================================================
            HEADER SECTION
            CHANGED: <thead> now holds ONLY the banner image (repeats on every page),
            same as qtpreview. Logo & company info moved to the start of <tbody>.
            ===================================================================
        -->
        <thead class="repeat-print-header">
            <tr>
                <td>
                    <!-- Top Graphic Banner -->
                    <div class="quote-print-header">
                        <img src="{{ asset('images/header.jpeg') }}" alt="Header" class="quote-header-img">
                    </div>
                </td>
            </tr>
        </thead>

        <!-- 
            ===================================================================
            PAGE 1 CONTENT: Quotation Metadata, Client Info, Items & Totals
            ===================================================================
        -->
        <tbody>
            <tr>
                <td>
                    <div class="quote-body">

                        <!-- MOVED from <thead>: LOGO & COMPANY INFO (PAGE 1 ONLY) -->
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

                        <!-- TITLE + QUOTATION NO / DATE -->
                        <div class="d-flex justify-content-between align-items-end quote-title-row">
                            <h2 class="quote-title mb-0">QUOTATION</h2>
                            <div class="text-end quote-meta">
                                <div>Quotation No: <strong>{{ $quotation->quotation_no }}</strong></div>
                                <div>Date: <strong>{{ $quotation->created_at ? $quotation->created_at->format('d M Y') : '-' }}</strong></div>
                            </div>
                        </div>
                        <hr class="quote-hr">

                        <!-- CLIENT NAME / ADDRESS BOX -->
                        <div class="quote-box mb-3">
                            <span class="quote-box-label"><span class="fw-bold">{{ $quotation->project->client->company_name ?? '-' }}</span></span>
                            <div class="mt-1" style="white-space: pre-line;">{{ $quotation->project->client->client_address ?? '-' }}</div>
                        </div>

                        <p class="mb-3">Dear Sir/Madam,</p>

                        <!-- PROJECT BOX -->
                        <div class="quote-box mb-2">
                            <span class="quote-box-label">PROJECT</span>
                            <div class="mt-1 fw-bold">{{ $quotation->project->name ?? '-' }}</div>
                            <!-- CHANGED: PIC and No. of PIC moved inside the box, same as qtpreview -->
                            <div class="mt-1"><strong>{{ $quotation->project->pic_name ?? '-' }}</strong></div>
                            <div><strong>{{ $quotation->project->pic_no ?? '-' }}</strong></div>
                        </div>

                        <!-- ITEMS TABLE -->
                        <table class="table quote-table mb-2">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th class="text-center" style="width: 80px;">Quantity</th>
                                    <th class="text-center" style="width: 80px;">Day/Sample</th>
                                    <th class="text-end" style="width: 120px;">Unit Price(RM)</th>
                                    <th class="text-end" style="width: 130px;">Total(RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- ADDED: group Module > Category+Service > Items (keeps original order) --}}
                                @php
                                    $toRoman = function ($n) {
                                        $out = '';
                                        foreach ([[10,'x'],[9,'ix'],[5,'v'],[4,'iv'],[1,'i']] as [$val, $sym]) {
                                            while ($n >= $val) { $out .= $sym; $n -= $val; }
                                        }
                                        return $out;
                                    };
                                    $groupedByModule = $quotation->items->groupBy(fn($l) => $l->module->module_name ?? 'MODULE');
                                @endphp

                                @forelse($groupedByModule as $moduleName => $moduleLines)
                                    <tr class="quote-module-row">
                                        <td colspan="5" class="quote-module-cell">{{ $loop->iteration }}. {{ $moduleName }}</td>
                                    </tr>

                                    @php
                                        $groups = $moduleLines->groupBy(fn($l) =>
                                            ($l->catalogItem->category->category_name ?? '') . '|' . ($l->catalogItem->service->service_name ?? '')
                                        );
                                    @endphp

                                    @foreach($groups as $groupLines)
                                        @php
                                            $first = $groupLines->first();
                                            $label = collect([
                                                $first->catalogItem->category->category_name ?? '',
                                                $first->catalogItem->service->service_name ?? '',
                                            ])->filter()->unique()->implode(' - ');
                                        @endphp

                                        <tr class="quote-service-row">
                                            <td colspan="5" class="quote-service-cell">{{ $toRoman($loop->iteration) }}) {{ $label ?: '-' }}</td>
                                        </tr>

                                        @foreach($groupLines as $line)
                                            @php
                                                $unitPrice = $line->daily_rate * (1 + (($line->mark_up ?? 0) / 100));
                                                $unit = trim($line->catalogItem->unit->unit_name ?? '');
                                            @endphp
                                            <tr>
                                                <td class="quote-item-cell">
                                                    <div class="d-flex"><span class="me-2">&bull;</span><span>{{ $line->catalogItem->item_name ?? 'Service Item' }}</span></div>
                                                </td>
                                                <td class="text-center">{{ $line->unit_qty }}</td>
                                                <td class="text-center">{{ $line->days }}</td>
                                                <td class="text-end">{{ number_format($unitPrice, 2) }}{{ $unit ? ' / ' . $unit : '' }}</td>
                                                <td class="text-end">{{ number_format($line->line_total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No items added to the quotation yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- TOTALS CALCULATION -->
                        @php
                            $subtotal = $quotation->grand_total;
                            $sst = $subtotal * 0.08;
                            $finalTotal = $subtotal + $sst;
                        @endphp

                        <div class="d-flex justify-content-end mb-4">
                            <table class="quote-totals-table">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end">RM {{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>SST 8%</td>
                                    <td class="text-end">RM {{ number_format($sst, 2) }}</td>
                                </tr>
                                <tr class="quote-grand-total-row">
                                    <td>Grand Total</td>
                                    <td class="text-end">RM {{ number_format($finalTotal, 2) }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- ADDITIONAL NOTES -->
                        {{-- CHANGED: hidden when empty (same as qtpreview) --}}
                        @php $notes = trim($quotation->additional_notes ?? ''); @endphp
                        @if($notes !== '' && $notes !== '-')
                            <div class="quote-box quote-box-light mb-4">
                                <span class="quote-box-label">Additional Notes</span>
                                <div class="mt-1" style="white-space: pre-wrap;">{{ $notes }}</div>
                            </div>
                        @endif
                                                <!-- 
                            ===================================================================
                            PAGE 2 CONTENT: Forced to break onto page 2 using .page-break-before
                            Contains: Payment Terms, Payment Info, and Technical Director Signature
                            ===================================================================
                        -->
                        <div class="page-break-before">
                            
                            <!-- PAYMENT TERMS & PAYMENT INFORMATION -->
                            <div class="row quote-two-col mb-4">
                                <div class="col-6">
                                    <h6 class="quote-section-heading">Payment Terms</h6>
                                    <div>
                                        @forelse($quotation->paymentTerms as $term)
                                            <div class="mb-1">
                                                {{ $term->name }} : <strong>{{ number_format($term->percentage, 0) }}%</strong>
                                                - {{ $term->condition ?? '-' }}
                                            </div>
                                        @empty
                                            <div>-</div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="quote-section-heading">Payment Information</h6>
                                    <div>
                                        <strong>Recipient:</strong> ECO HYDROTECH SOLUTIONS SDN. BHD.<br>
                                        <strong>Bank:</strong> MAYBANK ISLAMIC<br>
                                        <strong>Account Number:</strong> 5630 6496 5609<br>
                                        <strong>Bank's Address:</strong> 1-j, Kuala Terengganu Branch, Kompleks Perdana, Jalan Air Jernih, 20300 Kuala Terengganu, Terengganu
                                    </div>
                                </div>
                            </div>

                            <!-- SIGNATURE BLOCK -->
                            <div class="quote-signature mb-4">
                                <p class="mb-4">Yours sincerely,</p>
                                <img src="{{ asset('images/DrMadihasign.jpeg') }}" alt="Dr Madiha sign" style="max-height: 80px;">
                                <p class="mb-0 fw-bold">Ts. Dr Madiha Mokhtar</p>
                                <p class="mb-0">Technical Director</p>
                                <p class="mb-0">Eco Hydrotech Solutions Sdn. Bhd.</p>
                            </div>

                        </div>

                    </div>
                </td>
            </tr>
        </tbody>

    </table>

    <!-- 
        ===================================================================
        FOOTER SECTION
        CHANGED: moved out of <tfoot> to sit after the table (same as qtpreview).
        Fixed to the bottom of every printed page via CSS in @media print.
        ===================================================================
    -->
    <div class="quote-print-footer">
        <img src="{{ asset('images/footer.jpeg') }}" alt="Footer" class="quote-footer-img">
    </div>

</div>

</body>
</html>