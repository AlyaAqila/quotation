{{-- resources/views/dashboard/preview.blade.php --}}

<style>
    /* ==========================================
       QUOTATION PREVIEW — SCREEN DESIGN (SCOPED)
       Sizing matched to view.blade.php exactly
       ========================================== */
    .quote-doc {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: #212529;
        padding: 0;
        position: relative;
        background: #ffffff;
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

    /* Top Header Row Layout (Page 1 Only: Logo on Left, Company Info on Right) */
    .quote-header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-top: -20px;
        position: relative;
        z-index: 10;
        padding: 0 40px;
        margin-bottom: 10px;
    }

    .quote-logo img {
        max-height: 100px;
        width: auto;
        display: block;
    }

    .quote-company-info {
        font-size: 0.80rem; 
        line-height: 1.15;
        color: #212529;
    }

    /* Inner Body Content Area */
    .quote-body-content {
        padding: 10px 40px 100px 40px;
    }

    .quote-title-row {
        margin-top: 10px;
    }

    .quote-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #333;
        letter-spacing: 0.5px;
    }

    .quote-meta {
        font-size: 0.85rem;
        line-height: 1.2;
        color: #333;
    }

    .quote-hr {
        border-top: 2px solid #212529;
        margin: 10px 0 20px 0;
        opacity: 1;
    }

    .quote-box {
        background-color: #dfeefc;
        border-radius: 4px;
        padding: 0.75rem 1rem;
        line-height: 1.2;
        font-size: 0.85rem;
    }

    .quote-box div {
        line-height: 1.2;
        margin-top: 2px !important;
    }

    .quote-box-light {
        background-color: #eef6fb;
    }

    .quote-box-label {
        font-weight: 700;
        color: #1c53a0;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .quote-details-strip {
        display: flex;
        gap: 24px;
        font-size: 0.82rem;
        color: #495057;
        flex-wrap: wrap;
    }

    .quote-table {
        width: 100%;
        border-collapse: collapse;
    }

    .quote-table thead th {
        background-color: #1c6e7a;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.4rem 0.75rem;
        border: none;
    }

    .quote-table tbody td {
        background-color: #f5f5f5;
        padding: 0.35rem 0.75rem;
        font-size: 0.85rem;
        line-height: 1.25;
        border-bottom: none;
    }

    .quote-table tbody td.quote-module-cell {
        background-color: #d6e9ec;
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
        background-color: #ececec;
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

    .quote-totals-table {
        width: 280px;
        font-size: 0.9rem;
    }

    .quote-totals-table td {
        padding: 0.3rem 0.75rem;
    }

    .quote-totals-table tr:not(.quote-grand-total-row) td:last-child {
        background-color: #f0f0f0;
    }

    .quote-grand-total-row td {
        background-color: #1c6e7a;
        color: #ffffff;
        font-weight: 700;
    }

    .quote-section-heading {
        color: #1c53a0;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .quote-two-col {
        font-size: 0.85rem;
        line-height: 1.3;
    }

    .quote-two-col strong {
        font-weight: 700;
    }

    .quote-signature p {
        font-size: 0.9rem;
        line-height: 1.25;
    }

    /* =========================================================
       PRINT SPECIFIC OVERRIDES
       ========================================================= */
    @page {
        size: A4 portrait;
        margin: 0;
    }

    @media print {
        body {
            margin: 0;
            padding: 0;
        }

        .quote-doc,
        .quote-doc * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* Repeating header banner on every page */
        thead.repeat-print-header {
            display: table-header-group !important;
        }

        /* Anchor footer strictly to bottom of every printed page */
        .quote-print-footer {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 100% !important;
            z-index: 1000;
        }

        /* Body spacing for proper page flow */
        .quote-body-content {
            padding-top: 10px !important;
            padding-bottom: 35mm !important;
            padding-left: 15mm !important;
            padding-right: 15mm !important;
        }

        /* Page 2 forced break */
        .page-break-before {
            page-break-before: always !important;
            break-before: page !important;
            padding-top: 25mm !important; /* Clears top image banner on page 2 */
        }

        /* Avoid breaking elements mid-content */
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

<div id="quotationPreviewDocument" class="quote-doc">

    <table class="print-wrapper-table">

        <!-- ===================================================================
             TOP HEADER IMAGE BANNER (Repeats on EVERY page)
             =================================================================== -->
        <thead class="repeat-print-header">
            <tr>
                <td>
                    <div class="quote-print-header">
                        <img src="{{ asset('images/header.jpeg') }}" alt="Header" class="quote-header-img">
                    </div>
                </td>
            </tr>
        </thead>

        <!-- ===================================================================
             BODY CONTENT
             =================================================================== -->
        <tbody>
            <tr>
                <td>
                    <div class="quote-body-content">

                        <!-- LOGO & COMPANY INFO (PAGE 1 ONLY) -->
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
                                <div>Quotation No: <strong id="preview-number">-</strong></div>
                                <div>Date: <strong id="preview-date-issued">-</strong></div>
                            </div>
                        </div>
                        <hr class="quote-hr">

                        <!-- CLIENT NAME / ADDRESS BOX -->
                        <div class="quote-box mb-3">
                            <span class="quote-box-label"></span><span id="preview-client" class="fw-bold">-</span>
                            <div id="preview-client_address" class="mt-1">-</div>
                        </div>

                        <p class="mb-3">Dear Sir/Madam,</p>

                        <!-- PROJECT BOX -->
                        <div class="quote-box mb-2">
                            <span class="quote-box-label">PROJECT</span>
                            <div id="preview-project" class="mt-1 fw-bold">-</div>
                            <div class="mt-1"><strong id="preview-pic">-</strong></div>
                            <div><strong id="preview-pic_no">-</strong></div>
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
                            <tbody id="preview-table-body">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No items added to the quotation yet.</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- TOTALS -->
                        <div class="d-flex justify-content-end mb-4">
                            <table class="quote-totals-table">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end" id="preview-subtotal">RM 0.00</td>
                                </tr>
                                <tr>
                                    <td>SST 8%</td>
                                    <td class="text-end" id="preview-sst">RM 0.00</td>
                                </tr>
                                <tr class="quote-grand-total-row">
                                    <td>Grand Total</td>
                                    <td class="text-end preview-grand-total">RM 0.00</td>
                                </tr>
                            </table>
                        </div>

                        <!-- ADDITIONAL NOTES -->
                        <div id="preview-additional-notes-box" class="quote-box quote-box-light mb-4 d-none">
                            <div id="preview-additional-notes" class="mt-1" style="white-space: pre-wrap;"></div>
                        </div>

                        <!-- ===================================================================
                             PAGE 2 CONTENT: Payment Terms, Payment Info, and Signature
                             =================================================================== -->
                        <div class="page-break-before">

                            <!-- PAYMENT TERMS + PAYMENT INFORMATION -->
                            <div class="row quote-two-col mb-4">
                                <div class="col-6">
                                    <h6 class="quote-section-heading">Payment Terms</h6>
                                    <div id="preview-payment-terms">-</div>
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

                        </div> <!-- END .page-break-before -->

                    </div> <!-- END .quote-body-content -->
                </td>
            </tr>
        </tbody>

    </table>

    <!-- ===================================================================
         FOOTER SECTION (Fixed to bottom of every printed page)
         =================================================================== -->
    <div class="quote-print-footer">
        <img src="{{ asset('images/footer.jpeg') }}" alt="Footer" class="quote-footer-img">
    </div>

</div>