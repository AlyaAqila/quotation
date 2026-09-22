<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($item) ? 'Edit Item' : 'Create Item' }}</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/newItem.css') }}">
</head>

<body>

    <!-- Navigation Bar -->
    @include('dashboard.navbar')

    <div class="item-container">

        <!-- PAGE TITLE & BUTTON -->
        <div class="page-header-wrapper">
            <h2 class="page-header">
                <i class="bi bi-clipboard-data"></i>
                DATA MANAGEMENT
            </h2>
            <a href="{{ route('admin.index') }}">
                <button type="button" class="back-button" id="backButton">
                    <i class="fa-solid fa-arrow-left"></i> BACK
                </button>
            </a>
        </div>

        <!-- Dynamic Blue Banner -->
        <div class="banner">
            <h2 id="bannerTitle">{{ isset($item) ? 'EDIT ITEM' : 'CREATE NEW ITEM' }}</h2>
            <p id="bannerSub">{{ isset($item) ? 'MODIFY THE DETAILS OF THIS QUOTATION ITEM.' : 'CREATE NEW ITEM THAT WILL BE ADDED TO THE QUOTATION.' }}</p>
        </div>

        <!-- Main Content Area -->
        <main class="content-wrapper">
            <h3 class="section-title">ITEM INFORMATION</h3>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <form id="itemForm" 
                      action="{{ isset($item) ? route('items.update', ['id' => $item->item_id]) : route('items.store') }}" 
                      method="POST">
                    @csrf

                    @if(isset($item))
                        @method('PUT')
                    @endif

                    <input type="hidden" id="itemId" name="item_id" value="{{ $item->item_id ?? '' }}">

                    <div class="form-grid">
                        <!-- Column 1: Dropdowns & Dynamic Inputs -->
                        <div class="form-column">
                            
                            <!-- MODULE SELECT -->
                            <div class="form-group">
                                <label for="chooseModule">CHOOSE MODULE</label>
                                <select id="chooseModule" name="module_id" class="form-select">
                                    <option value="">-- Select Module --</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->module_id }}" 
                                            {{ old('module_id', $item->module_id ?? $item->category?->module_id ?? '') == $module->module_id ? 'selected' : '' }}>
                                            {{ strtoupper($module->module_name) }}
                                        </option>
                                    @endforeach
                                    <option value="NEW_MODULE" class="fw-bold text-primary">+ Add New Module</option>
                                </select>
                            </div>

                            <!-- HIDDEN NEW MODULE INPUT -->
                            <div class="form-group d-none" id="newModuleContainer" style="display: none; margin-top: 8px;">
                                <label for="createModule">NEW MODULE NAME</label>
                                <input type="text" id="createModule" name="new_module_name" placeholder="Type new module name...">
                            </div>

                            <!-- CATEGORY SELECT -->
                            <div class="form-group">
                                <label for="chooseCategory">CHOOSE CATEGORY</label>
                                <select id="chooseCategory" name="category_id" class="form-select">
                                    <option value="">-- Select Category First --</option>
                                    <option value="NEW_CATEGORY" class="fw-bold text-primary">+ Add New Category</option>
                                </select>
                            </div>

                            <!-- HIDDEN NEW CATEGORY INPUT -->
                            <div class="form-group d-none" id="newCategoryContainer" style="display: none; margin-top: 8px;">
                                <label for="createCategory">NEW CATEGORY NAME</label>
                                <input type="text" id="createCategory" name="new_category_name" placeholder="Type new category name...">
                            </div>

                            <!-- SERVICE SELECT -->
                            <div class="form-group">
                                <label for="chooseService">CHOOSE SERVICE</label>
                                <select id="chooseService" name="service_id" class="form-select">
                                    <option value="">-- Select Service First --</option>
                                    <option value="NEW_SERVICE" class="fw-bold text-primary">+ Add New Service</option>
                                </select>
                            </div>

                            <!-- HIDDEN NEW SERVICE INPUT -->
                            <div class="form-group d-none" id="newServiceContainer" style="display: none; margin-top: 8px;">
                                <label for="createService">NEW SERVICE NAME</label>
                                <input type="text" id="createService" name="new_service_name" placeholder="Type new service name...">
                            </div>

                        </div>

                        <!-- Column 2: Item Details & Rates -->
                        <div class="form-column">

                            <!-- ITEM NAME -->
                            <div class="form-group">
                                <label for="itemName">ITEM NAME <span style="color: red;">*</span></label>
                                <input type="text" id="itemName" name="name" 
                                       value="{{ old('name', $item->item_name ?? '') }}" required />
                            </div>

                            <!-- INTERNAL RATE & UNIT (SIDE-BY-SIDE) -->
                            <div class="row g-2 form-group">
                                <div class="col-8">
                                    <label for="internalRate">INTERNAL RATE (MYR) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" id="internalRate" name="internal_rate" 
                                        class="form-control"
                                        value="{{ old('internal_rate', $item->internal_rate ?? '') }}" 
                                        placeholder="0.00" required />
                                </div>

                                <div class="col-4">
                                    <div id="unitSelectContainer">
                                        <label for="chooseUnit">UNIT</label>
                                        <select id="chooseUnit" name="unit_id" class="form-select">
                                            <option value="">-- Unit --</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->unit_id }}" {{ old('unit_id', $item->unit_id ?? '') == $unit->unit_id ? 'selected' : '' }}>
                                                    {{ $unit->unit_name }}
                                                </option>
                                            @endforeach
                                            <option value="NEW_UNIT" class="fw-bold text-primary">+ New Unit</option>
                                        </select>
                                    </div>

                                    <div class="d-none position-relative" id="newUnitContainer">
                                        <label for="createUnit">NEW UNIT</label>
                                        <input type="text" 
                                            id="createUnit" 
                                            name="new_unit_name" 
                                            class="form-control pe-4" 
                                            placeholder="e.g. kg, pcs">
                                        
                                        <button type="button" 
                                                class="btn-close btn-close-custom" 
                                                id="cancelNewUnitBtn" 
                                                aria-label="Close" 
                                                title="Back to select"></button>
                                    </div>
                                </div>
                            </div>

                            <!-- DESCRIPTION / REMARK -->
                            <div class="form-group">
                                <label for="description">REMARK (optional)</label>
                                <textarea id="description" name="description" rows="4">{{ old('description', $item->description ?? '') }}</textarea>
                            </div>

                        </div>
                    </div>

                    <!-- Dynamic Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" id="submitBtn" class="btn btn-purple">
                            {{ isset($item) ? 'UPDATE' : 'SAVE' }}
                        </button>
                        <a href="{{ route('admin.index') }}" class="btn btn-purple text-center style-cancel" id="cancelBtn" style="text-decoration: none;">CANCEL</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Pass Existing Edit Item Data to JS -->
    <script>
    window.editItemData = {!! json_encode([
        'module_id'   => old('module_id', $item->module_id ?? $item->service?->category?->module_id ?? ''),
        'category_id' => old('category_id', $item->category_id ?? $item->service?->category_id ?? ''),
        'service_id'  => old('service_id', $item->service_id ?? ''),
        ]) !!};
    </script>

    <!-- Load item.js after window.editItemData is declared -->
    <script src="{{ asset('js/item.js') }}"></script>
</body>
</html>