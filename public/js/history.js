// Wait until the HTML webpage finishes loading
document.addEventListener('DOMContentLoaded', function () {
    
    // Select HTML elements for the Delete Modal
    const deleteModalOverlay = document.getElementById('deleteModalOverlay');
    const deleteQuotationForm = document.getElementById('deleteQuotationForm');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

    // Select HTML elements for Search and Filter
    const searchInput = document.getElementById('searchHistoryInput');
    const monthFilter = document.getElementById('monthFilter');
    const tableRows = document.querySelectorAll('.project-table tbody tr');
    const typeFilter = document.getElementById('typeFilter');
    const locationFilter = document.getElementById('locationFilter');

    const typeCodeMap = {
        '1': 'CP', //coastal project
        '2': 'RP', //river project
        '3': 'MP', //maritime project
        '4': 'GP', //geotech project
        '5': 'JP' //jetty project
    };
    // ==========================================
    // 1. DELETE MODAL FUNCTIONALITY
    // ==========================================
    
    // Listen for clicks on the entire document (Event Delegation)
    document.addEventListener('click', function (e) {
        // Check if the user clicked inside a delete button
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            e.preventDefault(); // Stop default link/button action
            
            // Get the route URL stored in the button's "data-delete-url"
            const deleteUrl = deleteBtn.getAttribute('data-delete-url');

            // Pass the route URL to the modal form's action attribute
            if (deleteQuotationForm && deleteUrl) {
                deleteQuotationForm.setAttribute('action', deleteUrl);
            }

            // Open the popup modal by adding the "active" CSS class
            if (deleteModalOverlay) {
                deleteModalOverlay.classList.add('active');
            }
        }
    });

    // Close modal when user clicks "Cancel" button
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', function () {
            deleteModalOverlay.classList.remove('active');
        });
    }

    // Close modal when user clicks outside the white card area
    if (deleteModalOverlay) {
        deleteModalOverlay.addEventListener('click', function (e) {
            if (e.target === deleteModalOverlay) {
                deleteModalOverlay.classList.remove('active');
            }
        });
    }

    // ==========================================
    // 2. REAL-TIME SEARCH AND MONTH FILTERING
    // ==========================================

    function filterTable() {
        // Read text from search input (converted to lowercase)
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        
        // Read selected month number from dropdown (e.g., "1" for January)
        const selectedMonth = monthFilter ? monthFilter.value : '';
        const selectedType = typeFilter ? typeFilter.value : '';
        const selectedLocation = locationFilter ? locationFilter.value : '';

        // Loop through each table row to check if it matches search & filter
        tableRows.forEach(row => {
            // Skip "No history records found" empty rows
            if (row.children.length < 6) return;

            // Get row text content
            const rowText = row.textContent.toLowerCase();
            
            // Read date column value (5th column -> index 4)
            const dateCell = row.children[4] ? row.children[4].textContent.trim() : ''; 
            
            // Extract month number from "YYYY-MM-DD" formatted string
            let rowMonth = '';
            if (dateCell) {
                const dateParts = dateCell.split('-');
                if (dateParts.length >= 2) {
                    rowMonth = parseInt(dateParts[1], 10).toString(); // Convert "02" to "2"
                }
            }

            const fullRowText = row.textContent;
            const codeMatch = fullRowText.match(/\/([A-Z]{2})\//);
            const rowTypeCode = codeMatch ? codeMatch[1] : '';

            // Check if row matches search text AND selected month
            const matchesSearch = rowText.includes(searchTerm);
            const matchesType = selectedType === '' || rowTypeCode === typeCodeMap[selectedType];
            const matchesMonth = selectedMonth === '' || rowMonth === selectedMonth;
            
            let rowLocation = '';
            const editBtn = row.querySelector('.edit-btn');
            if (editBtn) {
                try {
                    rowLocation = JSON.parse(editBtn.getAttribute('data-project') || '{}').location || '';
                } catch (e) {
                    rowLocation = '';
                }
            }
            const matchesLocation = selectedLocation === '' || rowLocation === selectedLocation;

            // Show row if both conditions pass; otherwise hide it
            row.style.display = (matchesSearch && matchesMonth && matchesType && matchesLocation) ? '' : 'none';
        });
    }

    // Trigger filtering whenever user types in Search or changes Month filter
    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (monthFilter) monthFilter.addEventListener('change', filterTable);
    if (typeFilter) typeFilter.addEventListener('change', filterTable);
    if (locationFilter) locationFilter.addEventListener('change', filterTable);
    
    // ==========================================
    // 3. VIEW QUOTATION MODAL FUNCTIONALITY
    // ==========================================
    
    const previewModalElement = document.getElementById('previewModal');
    const previewModal = previewModalElement ? new bootstrap.Modal(previewModalElement) : null;

    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function () {
            const data = JSON.parse(this.getAttribute('data-quotation'));

            // Helper to populate DOM text content safely
            const setText = (id, value) => {
                const el = document.getElementById(id);
                if (el) el.textContent = value || '-';
            };

            // 1. Populate basic header & client/project details
            setText('preview-number', data.quotation_no);
            setText('preview-client', data.project?.client?.company_name);
            setText('preview-client_address', data.project?.client?.client_address);
            setText('preview-project', data.project?.name);
            setText('preview-period', data.project?.period);
            setText('preview-pic', data.project?.pic_name);
            setText('preview-pic_no', data.project?.pic_no);

            // 2. Populate line items
            const tbody = document.getElementById('preview-table-body');
            if (tbody) {
                tbody.innerHTML = '';
                (data.items || []).forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-center">${index + 1}</td>
                        <td>${item.module?.module_name || item.module?.name || 'Service Item'}</td>
                        <td class="text-center">${item.unit_qty}</td>
                        <td class="text-center">${item.days}</td>
                        <td class="text-end">MYR ${parseFloat(item.daily_rate).toFixed(2)}</td>
                        <td class="text-end">MYR ${parseFloat(item.line_total).toFixed(2)}</td>
                    `;
                    tbody.appendChild(row);
                });
            }

            // 3. Set grand total
            document.querySelectorAll('.preview-grand-total').forEach(el => {
                el.textContent = 'MYR ' + parseFloat(data.grand_total || 0).toFixed(2);
            });

            // 4. Open the modal
            if (previewModal) previewModal.show();
        });
    });

    // ==========================================
    // 4. EDIT PROJECT DETAILS MODAL FUNCTIONALITY
    // ==========================================

    const editProjectModalOverlay = document.getElementById('editProjectModalOverlay');
    const editProjectForm = document.getElementById('editProjectForm');
    const cancelEditBtn = document.getElementById('cancelEditBtn');

    let currentEditRow = null; // tracks which <tr> to update after a successful save

    // Open modal + populate fields when pencil icon is clicked
    document.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-btn');
        if (!editBtn) return;

        e.preventDefault();

        const updateUrl = editBtn.getAttribute('data-update-url');
        const project = JSON.parse(editBtn.getAttribute('data-project') || '{}');

        // Remember the row + URL for the submit handler
        currentEditRow = editBtn.closest('tr');
        editProjectForm.setAttribute('data-update-url', updateUrl);

        // Populate fields with existing data (never leave the form empty)
        document.getElementById('edit_project_name').value = project.name || '';
        document.getElementById('edit_period').value = project.period || '';
        document.getElementById('edit_client_name').value = project.client?.company_name || '';
        document.getElementById('edit_client_address').value = project.client?.client_address || '';
        document.getElementById('edit_number').value = project.number || '';
        document.getElementById('edit_pic').value = project.pic_name || '';
        document.getElementById('edit_pic_no').value = project.pic_no || '';
        document.getElementById('edit_location').value = project.location || '';

        if (editProjectModalOverlay) {
            editProjectModalOverlay.classList.add('active');
        }
    });

    // Close modal via Cancel button
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function () {
            editProjectModalOverlay.classList.remove('active');
        });
    }

    // Close modal when clicking outside the card
    if (editProjectModalOverlay) {
        editProjectModalOverlay.addEventListener('click', function (e) {
            if (e.target === editProjectModalOverlay) {
                editProjectModalOverlay.classList.remove('active');
            }
        });
    }

    // Submit form via AJAX
    if (editProjectForm) {
        editProjectForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const updateUrl = editProjectForm.getAttribute('data-update-url');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const payload = {
                project_name: document.getElementById('edit_project_name').value,
                period: document.getElementById('edit_period').value,
                client_name: document.getElementById('edit_client_name').value,
                client_address: document.getElementById('edit_client_address').value,
                pic: document.getElementById('edit_pic').value,
                pic_no: document.getElementById('edit_pic_no').value,
                location: document.getElementById('edit_location').value,
            };

            fetch(updateUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the row in the table without reloading the page
                    if (currentEditRow) {
                        currentEditRow.children[1].textContent = data.project.name || '-';
                        currentEditRow.children[2].textContent = data.project.number || '-';
                        const clientSpan = currentEditRow.children[3].querySelector('.client-text');
                        if (clientSpan) clientSpan.textContent = data.project.client_name || 'N/A';

                        // NEW: keep the edit button's stored data in sync with the saved values
                        const editBtn = currentEditRow.querySelector('.edit-btn');
                        if (editBtn) {
                            const p = JSON.parse(editBtn.getAttribute('data-project') || '{}');
                            p.name           = data.project.name;
                            p.number         = data.project.number;
                            p.period         = data.project.period;
                            p.pic_name       = data.project.pic_name;
                            p.pic_no         = data.project.pic_no;
                            p.client         = p.client || {};
                            p.client.company_name   = data.project.client_name;
                            p.client.client_address = data.project.client_address;
                            p.location       = data.project.location;
                            editBtn.setAttribute('data-project', JSON.stringify(p));
                        }
                    }

                    editProjectModalOverlay.classList.remove('active');
                } else {
                    alert(data.message || 'Failed to update project.');
                }
            })
            .catch(error => {
                console.error('Update error:', error);
                alert('Something went wrong while updating the project.');
            });
        });
    }
});