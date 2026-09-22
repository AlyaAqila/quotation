document.addEventListener('DOMContentLoaded', function () {

    // DOM Elements
    const chooseModule = document.getElementById('chooseModule');
    const chooseCategory = document.getElementById('chooseCategory');
    const chooseService = document.getElementById('chooseService');
    const chooseUnit = document.getElementById('chooseUnit');

    const createModule = document.getElementById('createModule');
    const createCategory = document.getElementById('createCategory');
    const createService = document.getElementById('createService');

    const newModuleContainer = document.getElementById('newModuleContainer');
    const newCategoryContainer = document.getElementById('newCategoryContainer');
    const newServiceContainer = document.getElementById('newServiceContainer');
    
    // Unit Containers & Elements
    const unitSelectContainer = document.getElementById('unitSelectContainer');
    const newUnitContainer = document.getElementById('newUnitContainer');
    const createUnit = document.getElementById('createUnit');
    const cancelNewUnitBtn = document.getElementById('cancelNewUnitBtn');
    
    // Target unit text container inside the td
    const rateUnitDisplay = document.getElementById('rateUnitDisplay');

    // ==========================================
    // UPDATE TABLE TD UNIT DISPLAY LIVE
    // ==========================================
    function updateTdUnitDisplay(unitText) {
        if (!rateUnitDisplay) return;
        
        const cleanText = unitText ? unitText.trim() : '';
        if (cleanText !== '' && cleanText !== '-- Unit --' && cleanText !== '+ New Unit') {
            rateUnitDisplay.textContent = ' / ' + cleanText;
        } else {
            rateUnitDisplay.textContent = '';
        }
    }

    // Initialize unit display on load if option selected
    if (chooseUnit && chooseUnit.selectedIndex >= 0) {
        const selectedText = chooseUnit.options[chooseUnit.selectedIndex].text;
        updateTdUnitDisplay(selectedText);
    }

    // ==========================================
    // 1. DYNAMIC VISIBILITY TOGGLE FOR MODULE, CATEGORY & SERVICE
    // ==========================================
    function setupAddNewToggle(selectEl, containerEl, inputEl) {
        if (!selectEl || !containerEl) return;

        function updateVisibility() {
            if (
                selectEl.value === 'NEW_MODULE' || 
                selectEl.value === 'NEW_CATEGORY' || 
                selectEl.value === 'NEW_SERVICE'
            ) {
                containerEl.classList.remove('d-none');
                containerEl.style.setProperty('display', 'block', 'important');
                if (inputEl) inputEl.focus();
            } else {
                containerEl.classList.add('d-none');
                containerEl.style.setProperty('display', 'none', 'important');
                if (inputEl) inputEl.value = '';
            }
        }

        updateVisibility();
        selectEl.addEventListener('change', updateVisibility);
    }

    setupAddNewToggle(chooseModule, newModuleContainer, createModule);
    setupAddNewToggle(chooseCategory, newCategoryContainer, createCategory);
    setupAddNewToggle(chooseService, newServiceContainer, createService);

    // ==========================================
    // 1.1 DYNAMIC VISIBILITY & CANCEL TOGGLE FOR UNIT
    // ==========================================
    if (chooseUnit && newUnitContainer && unitSelectContainer) {
        chooseUnit.addEventListener('change', function () {
            if (this.value === 'NEW_UNIT') {
                unitSelectContainer.classList.add('d-none');
                unitSelectContainer.style.setProperty('display', 'none', 'important');
                
                newUnitContainer.classList.remove('d-none');
                newUnitContainer.style.setProperty('display', 'block', 'important');
                
                if (createUnit) {
                    createUnit.focus();
                    updateTdUnitDisplay(createUnit.value);
                }
            } else {
                const selectedText = this.options[this.selectedIndex] ? this.options[this.selectedIndex].text : '';
                updateTdUnitDisplay(selectedText);
            }
        });

        if (createUnit) {
            createUnit.addEventListener('input', function () {
                updateTdUnitDisplay(this.value);
            });
        }

        if (cancelNewUnitBtn) {
            cancelNewUnitBtn.addEventListener('click', function () {
                newUnitContainer.classList.add('d-none');
                newUnitContainer.style.setProperty('display', 'none', 'important');
                if (createUnit) createUnit.value = '';

                unitSelectContainer.classList.remove('d-none');
                unitSelectContainer.style.setProperty('display', 'block', 'important');
                chooseUnit.value = '';
                
                updateTdUnitDisplay('');
            });
        }
    }

    // ==========================================
    // 2. REUSABLE FETCH FUNCTIONS (RETURNS PROMISES)
    // ==========================================
    function loadCategories(moduleId, selectedCategoryId = null) {
        resetCategoryDropdown();
        resetServiceDropdown();

        if (!moduleId || moduleId === 'NEW_MODULE') {
            return Promise.resolve();
        }

        return fetch(`/api/modules/${moduleId}/categories`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                const categories = Array.isArray(data) ? data : (data.categories || data.data || []);
                chooseCategory.innerHTML = '<option value="">-- Select Category --</option>';

                if (categories.length > 0) {
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        const catId = category.category_id || category.id;
                        option.value = catId;
                        const name = category.category_name || category.name || 'Unnamed Category';
                        option.textContent = name.toUpperCase();
                        
                        if (selectedCategoryId && String(catId) === String(selectedCategoryId)) {
                            option.selected = true;
                        }
                        
                        chooseCategory.appendChild(option);
                    });
                } else {
                    const noOpt = document.createElement('option');
                    noOpt.value = "";
                    noOpt.disabled = true;
                    noOpt.textContent = "-- No existing categories found --";
                    chooseCategory.appendChild(noOpt);
                }

                const newOpt = document.createElement('option');
                newOpt.value = 'NEW_CATEGORY';
                newOpt.className = 'fw-bold text-primary';
                newOpt.textContent = '+ Add New Category';
                chooseCategory.appendChild(newOpt);
            })
            .catch(error => {
                console.error('Error fetching categories:', error);
                chooseCategory.innerHTML = '<option value="">-- Error loading categories --</option>';
            });
    }

    function loadServices(categoryId, selectedServiceId = null) {
        resetServiceDropdown();

        if (!categoryId || categoryId === 'NEW_CATEGORY') {
            return Promise.resolve();
        }

        return fetch(`/api/categories/${categoryId}/services`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                const services = Array.isArray(data) ? data : (data.services || data.data || []);
                chooseService.innerHTML = '<option value="">-- Select Service --</option>';

                if (services.length > 0) {
                    services.forEach(service => {
                        const option = document.createElement('option');
                        const servId = service.service_id || service.id;
                        option.value = servId;
                        const name = service.service_name || service.name || 'Unnamed Service';
                        option.textContent = name.toUpperCase();
                        
                        if (selectedServiceId && String(servId) === String(selectedServiceId)) {
                            option.selected = true;
                        }

                        chooseService.appendChild(option);
                    });
                } else {
                    const noOpt = document.createElement('option');
                    noOpt.value = "";
                    noOpt.disabled = true;
                    noOpt.textContent = "-- No existing services found --";
                    chooseService.appendChild(noOpt);
                }

                const newOpt = document.createElement('option');
                newOpt.value = 'NEW_SERVICE';
                newOpt.className = 'fw-bold text-primary';
                newOpt.textContent = '+ Add New Service';
                chooseService.appendChild(newOpt);
            })
            .catch(error => {
                console.error('Error fetching services:', error);
                chooseService.innerHTML = '<option value="">-- Error loading services --</option>';
            });
    }

    // ==========================================
    // 3. EVENT LISTENERS FOR MANUAL DROPDOWN CHANGES
    // ==========================================
    if (chooseModule) {
        chooseModule.addEventListener('change', function () {
            loadCategories(this.value);
        });
    }

    if (chooseCategory) {
        chooseCategory.addEventListener('change', function () {
            loadServices(this.value);
        });
    }

    // ==========================================
    // 4. AUTOMATIC PRE-FILL ON PAGE LOAD (EDIT MODE)
    // ==========================================
    if (window.editItemData) {
        const initialModule = chooseModule ? chooseModule.value : null;
        const savedCategoryId = window.editItemData.category_id;
        const savedServiceId = window.editItemData.service_id;

        if (initialModule && initialModule !== 'NEW_MODULE') {
            loadCategories(initialModule, savedCategoryId).then(() => {
                if (savedCategoryId && savedCategoryId !== 'NEW_CATEGORY') {
                    loadServices(savedCategoryId, savedServiceId);
                }
            });
        }
    }

    // ==========================================
    // 5. HELPER RESET FUNCTIONS
    // ==========================================
    function resetCategoryDropdown() {
        if (!chooseCategory) return;
        chooseCategory.innerHTML = '<option value="">-- Select Category First --</option><option value="NEW_CATEGORY" class="fw-bold text-primary">+ Add New Category</option>';
        if (newCategoryContainer) {
            newCategoryContainer.classList.add('d-none');
            newCategoryContainer.style.setProperty('display', 'none', 'important');
        }
        if (createCategory) createCategory.value = '';
    }

    function resetServiceDropdown() {
        if (!chooseService) return;
        chooseService.innerHTML = '<option value="">-- Select Service First --</option><option value="NEW_SERVICE" class="fw-bold text-primary">+ Add New Service</option>';
        if (newServiceContainer) {
            newServiceContainer.classList.add('d-none');
            newServiceContainer.style.setProperty('display', 'none', 'important');
        }
        if (createService) createService.value = '';
    }
});