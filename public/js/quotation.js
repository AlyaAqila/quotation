document.addEventListener('DOMContentLoaded', () => {
    // Printable view shortcut trigger (Ctrl + P or Cmd + P)
    window.addEventListener('keydown', (event) => {
        if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
            window.print();
        }
    });

    // Optional client-side dynamic calculation helper
    const calculateTotals = () => {
        let subtotal = 0;
        const rows = document.querySelectorAll('.items-table tbody tr');

        rows.forEach(row => {
            const qtyCell = row.cells[1];
            const priceCell = row.cells[2];
            const totalCell = row.cells[3];

            if (qtyCell && priceCell && totalCell) {
                const qty = parseFloat(qtyCell.textContent.replace(/[^0-9.-]+/g, "")) || 0;
                const price = parseFloat(priceCell.textContent.replace(/[^0-9.-]+/g, "")) || 0;
                
                if (qty && price) {
                    const rowTotal = qty * price;
                    totalCell.textContent = '$' + rowTotal.toFixed(2);
                    subtotal += rowTotal;
                }
            }
        });
    };

    calculateTotals();
});