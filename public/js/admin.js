document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const createButton = document.getElementById("createButton");

    // =========================================================
    // 1. Real-time Search Filter Logic
    // =========================================================
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            const searchValue = searchInput.value.toLowerCase().trim();
            const rows = document.querySelectorAll(".data-body tr");

            rows.forEach(function (row) {
                // Ignore empty placeholder rows during search filtering
                if (row.classList.contains("empty-row")) {
                    return;
                }

                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    }

    // =========================================================
    // 2. Animation Feedback for Create Button
    // =========================================================
    if (createButton) {
        createButton.addEventListener("click", function () {
            createButton.classList.add("clicked");
            setTimeout(function () {
                createButton.classList.remove("clicked");
            }, 180);
        });
    }

    // =========================================================
    // 3. Event Delegation for Action Buttons (Edit / Delete)
    // =========================================================
    // Uses Event Delegation to ensure animation and confirmation work 
    // dynamically even after filtering or table re-renders.
    document.addEventListener("click", function (event) {
        const button = event.target.closest(".action-button");

        if (button) {
            // Click animation feedback
            button.classList.add("clicked");
            setTimeout(function () {
                button.classList.remove("clicked");
            }, 180);

            // Confirmation check if the button is a Delete action
            if (button.classList.contains("delete-button") || button.dataset.action === "delete") {
                const confirmed = confirm("Are you sure you want to delete this item?");
                if (!confirmed) {
                    event.preventDefault(); // Cancel form submission if rejected
                }
            }
        }
    });
});

