<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>

    <!-- FontAwesome / Bootstrap Icons for Action Buttons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* General CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #ffffff;
            color: #20242a;
        }

        /* Main Container Centering */
        .history-container {
            width: 90%;
            max-width: 1200px;
            margin: 35px auto 50px auto; 
        }

        /* Page Header Layout */
        .page-header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header {
            font-size: 22px;
            font-weight: 700;
            color: #1c53a0;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0; 
        }

        /* Quotation Summary Card (Header Info) */
        .quotation-card {
            width: 100%;
            background: #d8e7fc;
            border: 1.5px solid #343a40;
            border-radius: 12px;
            padding: 25px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            color: #4f535a;
            margin-bottom: 6px;
        }

        .quotation-number {
            font-size: 20px;
            font-weight: 700;
            color: #1c53a0;
        }

        .total-box {
            text-align: right;
        }

        .total-label {
            display: block;
            font-size: 15px;
            color: #4f535a;
            margin-bottom: 5px;
        }

        .total-amount {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        /* Outer Table Container */
        .table-container {
            width: 100%;
            padding: 22px 12px 15px;
            background: #eef7fd;
            border-radius: 12px;
            box-sizing: border-box;
        }

        /* Table Design */
        .tbinvoice-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #ffffff;
            border: 1.5px solid #343a40;
            border-radius: 9px;
            overflow: hidden;
            table-layout: fixed;
        }

        /* Table Header Styles */
        .tbinvoice-table thead th {
            height: 59px;
            background: #dce7ff;
            color: #363738;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
            border-right: 1px solid #b8bec7;
            border-bottom: 1px solid #b8bec7;
            letter-spacing: 0.1px;
        }

        /* Table Body Cell Styles */
        .tbinvoice-table tbody td {
            height: 72px;
            padding: 10px 12px;
            color: #20242a;
            font-size: 13px;
            border-right: 1px solid #b8bec7;
            border-bottom: 1px solid #b8bec7;
            vertical-align: middle;
        }

        /* Remove Outer Border Lines on Last Items */
        .tbinvoice-table th:last-child,
        .tbinvoice-table td:last-child {
            border-right: none;
        }

        .tbinvoice-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Table Column Width Preferences */
        .col-payment { width: 20%; }
        .col-percentage { width: 15%; }
        .col-condition { width: 35%; }
        .col-total { width: 15%; }
        .col-action { width: 15%; }

        /* Column Text Alignments */
        .tbinvoice-table tbody td:nth-child(1) { text-align: left; font-weight: 600; color: #111827; }
        .tbinvoice-table tbody td:nth-child(2) { text-align: center; font-weight: 600; }
        .tbinvoice-table tbody td:nth-child(3) { text-align: left; line-height: 1.5; }
        .tbinvoice-table tbody td:nth-child(4) { text-align: center; font-weight: 600; }

        /* Action Buttons Layout */
        .action-buttons {
            text-align: center;
            white-space: nowrap;
        }

        .action-buttons button,
        .action-buttons a {
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 15px;
            margin: 0 9px;
            transition: 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .view-btn { color: #20242a; }
        .view-btn:hover { color: #5966f2; transform: scale(1.1); }

        .delete-btn { color: #ff3b45; }
        .delete-btn:hover { color: #d9000b; transform: scale(1.1); }

        /* ==========================================
           DELETE MODAL OVERLAY & POPUP STYLES
        ========================================== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active { 
            display: flex; 
        }

        .modal-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .modal-card h3 {
            margin-bottom: 12px;
            color: #1c53a0;
            font-size: 20px;
            font-weight: 700;
        }

        .modal-card p {
            color: #555555;
            font-size: 14px;
            margin-bottom: 24px;
            line-height: 1.4;
        }

        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-cancel {
            background: #e4e6eb;
            color: #333333;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-confirm {
            background: #ff3b45;
            color: #ffffff;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-cancel:hover { background: #d8dadf; }
        .btn-confirm:hover { background: #d9000b; }

        /* Responsive Layout Adjustment */
        @media (max-width: 900px) {
            .table-container { overflow-x: auto; }
            .project-table { min-width: 850px; }
            .filter-card { grid-template-columns: 1fr; }
            .quotation-card { flex-direction: column; align-items: flex-start; gap: 15px; }
            .total-box { text-align: left; }
        }
    </style>
</head>

<body>

    <div class="history-container">

        <!-- Page Header Wrapper -->
        <div class="page-header-wrapper">
            <h1 class="page-header">INVOICE</h1>
            <a href="{{ url('/history') }}" class="btn-cancel" style="text-decoration: none; display: inline-block;"> Back
            </a>
        </div>

        <!-- Quotation Summary Card -->
        <div class="quotation-card">
            <div>
                <span class="section-label">QUOTATION</span>
                <h2 class="quotation-number">{{ $quotation->quotation_no }}</h2>
            </div>
            <div class="total-box">
                <span class="total-label">Total Amount</span>
                <span class="total-amount">RM{{ number_format($finalTotal, 2) }}</span>
            </div>
        </div>

        <!-- Outer Table Container -->
        <div class="table-container">
            <table class="tbinvoice-table">
                <thead>
                    <tr>
                        <th class="col-payment">Payment</th>
                        <th class="col-percentage">Percentage</th>
                        <th class="col-condition">Condition</th>
                        <th class="col-total">Total</th>
                        <th class="col-action">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotation->paymentTerms as $term)
                    <tr>
                        <td>{{ $term->name }}</td>
                        <td>{{ number_format($term->percentage, 0) }}%</td>
                        <td>{{ $term->condition }}</td>
                        <td>RM {{ number_format($term->amount ?? ($finalTotal * ($term->percentage / 100)), 2) }}</td>
                        <td class="action-buttons">
                            @if($term->invoiceCopy)
                                {{-- Invoice already generated for this term --}}
                                <a href="{{ route('invoices.show', ['invoice' => $term->invoiceCopy->invoice_Id, 'autoprint' => 1]) }}" class="view-btn" title="Print Invoice">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                                <a href="{{ route('invoices.show', ['invoice' => $term->invoiceCopy->invoice_Id, 'openEdit' => 1]) }}" class="view-btn" title="Edit Condition & Description">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            @else
                                {{-- No invoice yet for this term --}}
                                <a href="{{ route('invoices.create', ['quotation' => $quotation->quotation_Id, 'term' => $term->id]) }}" class="view-btn" title="Generate Invoice">
                                    <i class="fa-solid fa-file-invoice"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No payment terms configured.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-card">
            <h3>Delete Payment Term?</h3>
            <p>Are you sure you want to delete this payment term? This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button class="btn-confirm" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <!-- Modal Script Trigger -->
    <script>
        function openModal() {
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        function confirmDelete() {
            alert("Item deleted successfully!");
            closeModal();
        }
    </script>

</body>
</html>