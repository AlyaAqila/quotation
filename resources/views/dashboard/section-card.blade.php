<div class="card border-0 shadow-sm mb-3 section-block" 
     data-section-id="{{ $category->category_id ?? $category->id }}" 
     data-module-id="{{ $module->module_id ?? $module->id }}">
    <!-- SECTION HEADER BAR -->
    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3 border-0">
        <button class="btn p-0 text-start fw-bold text-uppercase text-dark custom-dropdown-btn d-flex align-items-center gap-2" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#collapse-{{ $target }}" 
                aria-expanded="true">
            <i class="bi bi-chevron-down toggle-icon text-primary"></i>
            <span>{{ $title }}</span>
        </button>

        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small fw-bold">Subtotal: <strong class="text-dark">MYR <span class="section-subtotal">0.00</span></strong></span>
            <button type="button" 
                    class="btn btn-sm btn-outline-primary fw-bold text-uppercase add-item-btn"
                    data-module-id="{{ $module->module_id ?? $module->id }}" 
                    data-section-id="{{ $category->category_id ?? $category->id }}">
                <i class="bi bi-plus-lg me-1"></i> ADD ITEM
            </button>
        </div>
    </div>

    <!-- COLLAPSIBLE SECTION BODY -->
    <div class="collapse show" id="collapse-{{ $target }}">
        <div class="card-body p-3 section-items-container item-list" id="container-{{ $module->module_id ?? $module->id }}-{{ $category->category_id ?? $category->id }}">
            {{-- Dynamically inserted quotation-item-card elements will load here --}}
        </div>
    </div>
</div>

<!-- ITEM CARD TEMPLATE (REUSED BY JS) -->
<template id="item-card-template">
    <div class="card quotation-item-card p-3 mb-3 bg-white border position-relative rounded" data-item-row="true">
        <!-- DELETE TRASH ICON -->
        <button type="button" class="btn btn-sm text-danger position-absolute top-0 end-0 m-2 remove-item-btn btn-delete-item" title="Delete Item">
            <i class="bi bi-trash-fill fs-6"></i>
        </button>

        <div class="row g-3">
            <!-- CATEGORY / SERVICE DROPDOWN -->
            <div class="col-md-6 col-lg-3">
                <label class="form-label fw-bold text-uppercase">SERVICE</label>
                <select name="items[__INDEX__][service_id]" class="form-select form-select-sm item-service select-service">
                    <option value="">Select Service...</option>
                </select>
            </div>

            <!-- ITEM DROPDOWN -->
            <div class="col-md-6 col-lg-3">
                <label class="form-label fw-bold text-uppercase">ITEM</label>
                <select name="items[__INDEX__][item_id]" class="form-select form-select-sm item-name select-item">
                    <option value="">Select Item...</option>
                </select>
            </div>

            <!-- DAILY RATE -->
            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label fw-bold text-uppercase">RATE (MYR)</label>
                <input type="number" step="0.01" name="items[__INDEX__][daily_rate]" class="form-control form-control-sm item-rate input-daily-rate" value="0.00">
            </div>

            <!-- UNIT QTY -->
            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label fw-bold text-uppercase">UNIT QTY</label>
                <input type="number" name="items[__INDEX__][unit_qty]" class="form-control form-control-sm item-qty input-unit-qty" value="1" min="1">
            </div>

            <!-- DAYS -->
            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label fw-bold text-uppercase">DAY/SAMPLE</label>
                <input type="number" name="items[__INDEX__][days]" class="form-control form-control-sm item-days input-days" value="1" min="1">
            </div>

            <!-- MARK-UP (%) -->
            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label fw-bold text-uppercase">MARK-UP (%)</label>
                <input type="number" step="0.01" name="items[__INDEX__][mark_up]" class="form-control form-control-sm item-markup input-markup" value="0">
            </div>

            <!-- INTERNAL RATE WITH UNIT DISPLAY -->
            <div class="col-md-6 col-lg-3">
                <label class="form-label fw-bold text-uppercase">INTERNAL RATE (MYR)</label>
                <div class="input-group input-group-sm">
                    <input type="text" 
                           name="items[__INDEX__][internal_rate]" 
                           class="form-control form-control-sm input-internal-rate" 
                           placeholder="0.00" 
                           readonly>
                    <span class="input-group-text bg-light text-muted rate-unit-display d-none" id="rateUnitDisplay___INDEX__"></span>
                </div>
            </div>
        </div>

        <!-- LINE ITEM FOOTER TOTAL -->
        <div class="item-card-footer d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
            <span class="text-uppercase text-muted small fw-bold">LINE ITEM TOTAL</span>
            <span class="fw-bold text-dark fs-6 line-item-total line-total">MYR 0.00</span>
        </div>

        <!-- HIDDEN KEYS -->
        <input type="hidden" name="items[__INDEX__][module_id]" class="input-module-id">
        <input type="hidden" name="items[__INDEX__][category_id]" class="input-section-id">
    </div>
</template>