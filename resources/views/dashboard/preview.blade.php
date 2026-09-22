{{-- resources/views/partials/quotation-preview.blade.php --}}
<div id="quotationPreviewDocument">
    <!-- HEADER BLOCK -->
    <div class="d-flex justify-content-between align-items-start pb-4 mb-4 border-bottom">
        <div>
            <h2 class="fw-bold text-uppercase mb-1" style="letter-spacing: 1px;">OFFICIAL QUOTATION</h2>
            <p class="text-muted mb-0">Date Issued: <span id="preview-date-issued">-</span></p>
        </div>
        <div class="text-end">
            <h5 class="fw-bold mb-0 text-muted">TOTAL ESTIMATE</h5>
            <h3 class="fw-bold text-primary mb-0 preview-grand-total">MYR 0.00</h3>
        </div>
    </div>

    <!-- PROJECT DETAILS GRID -->
    <div class="card bg-light border-0 p-3 mb-4">
        <div class="row g-3">
            <div class="col-sm-6 col-md-4">
                <span class="text-muted small text-uppercase d-block fw-semibold">Project</span>
                <strong class="text-dark fs-6" id="preview-project">-</strong>
            </div>
            <div class="col-sm-6 col-md-4">
                <span class="text-muted small text-uppercase d-block fw-semibold">Project No.</span>
                <strong class="text-dark fs-6" id="preview-number">-</strong>
            </div>
            <div class="col-sm-6 col-md-4">
                <span class="text-muted small text-uppercase d-block fw-semibold">Client</span>
                <strong class="text-dark fs-6" id="preview-client">-</strong>
            </div>
            <div class="col-sm-6 col-md-4">
                <span class="text-muted small text-uppercase d-block fw-semibold">Period</span>
                <span class="text-dark" id="preview-period">-</span>
            </div>
            <div class="col-sm-6 col-md-4">
                <span class="text-muted small text-uppercase d-block fw-semibold">PIC</span>
                <span class="text-dark" id="preview-pic">-</span>
            </div>
            <div class="col-sm-6 col-md-4">
                <span class="text-muted small text-uppercase d-block fw-semibold">No. of PIC</span>
                <span class="text-dark" id="preview-pic_no">-</span>
            </div>
        </div>
    </div>

    <!-- ITEMS TABLE -->
    <table class="table table-bordered align-middle mb-4">
        <thead class="table-light">
            <tr>
                <th style="width: 50px;" class="text-center">#</th>
                <th>ITEM DESCRIPTION</th>
                <th style="width: 80px;" class="text-center">QTY</th>
                <th style="width: 80px;" class="text-center">DAYS</th>
                <th style="width: 140px;" class="text-end">RATE</th>
                <th style="width: 150px;" class="text-end">AMOUNT</th>
            </tr>
        </thead>
        <tbody id="preview-table-body">
            <tr>
                <td colspan="6" class="text-center text-muted py-4">No items added to the quotation yet.</td>
            </tr>
        </tbody>
    </table>

    <!-- PAYMENT TERMS & ADDITIONAL NOTES -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card bg-light border-0 p-3 h-100">
                <span class="text-muted small text-uppercase d-block fw-semibold mb-2">Payment Terms</span>
                <div id="preview-payment-terms" class="text-dark" style="font-size: 0.9rem;">-</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light border-0 p-3 h-100">
                <span class="text-muted small text-uppercase d-block fw-semibold mb-2">Additional Notes</span>
                <div id="preview-additional-notes" class="text-dark" style="font-size: 0.9rem; white-space: pre-wrap;">-</div>
            </div>
        </div>
    </div>

    <!-- FOOTER TOTALS -->
    <div class="d-flex justify-content-end pt-3 border-top">
        <div class="text-end">
            <span class="fw-bold text-muted me-3">GRAND TOTAL:</span>
            <span class="fw-bold fs-4 text-primary preview-grand-total">MYR 0.00</span>
        </div>
    </div>
</div>


<!--  -->