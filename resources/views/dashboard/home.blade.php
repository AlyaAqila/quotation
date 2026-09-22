<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Navbar & Home CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>

    <!-- Navigation Bar -->
    @include('dashboard.navbar')

    <!-- Home Page Content Container -->
    <div class="home-container">

        <h2 class="page-title">
            <i class="bi bi-file-earmark-ruled-fill"></i>
            QUOTATION
        </h2>

        <!-- ================================================================== -->
        <!-- 1. EDIT FORM CONTAINER (Visible in Edit Mode) -->
        <!-- ================================================================== -->
        <div id="editFormContainer">

            <!-- PROJECT INFORMATION -->
            <div class="project-card">
                <div class="project-grid">

                    <div class="form-group">
                        <label for="project">PROJECT NAME</label>
                        <input type="text" id="project" name="project">
                    </div>

                    <div class="form-group">
                        <label>PROJECT TYPE</label>
                        <select id="selectType">
                            <option value="">Choose Project Type</option>
                            <option value="1">CP - Coastal Project</option>
                            <option value="2">RP - River Project</option>
                            <option value="3">MP - Maritime Project</option>
                            <option value="4">GP - Geotech Project</option>
                            <option value="5">JP - Jetty Project</option>
                        </select>
                    </div>

                    <!-- COMBINED DATES & PERIOD IN ONE GRID COLUMN (SPANS 2 COLUMNS) -->
                    <div class="form-group date-period-group">
                        <div class="date-period-wrapper">
                            <div class="date-item">
                                <label for="project_start_date">START DATE</label>
                                <input type="date" id="project_start_date" name="project_start_date">
                            </div>

                            <div class="date-item">
                                <label for="project_end_date">END DATE</label>
                                <input type="date" id="project_end_date" name="project_end_date">
                            </div>

                            <div class="period-item">
                                <label for="period">PERIOD</label>
                                <input type="text" id="period" name="period" readonly placeholder="Auto-calculated">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="client">CLIENT</label>
                        <input type="text" id="client" name="client">
                    </div>

                    <div class="form-group">
                        <label for="client_address">CLIENT ADDRESS</label>
                        <textarea id="client_address" name="client_address" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="location">LOCATION</label>
                        <select id="location" name="location">
                            <option value="">Choose Location</option>
                            <option value="Johor">Johor</option>
                            <option value="Kedah">Kedah</option>
                            <option value="Kelantan">Kelantan</option>
                            <option value="Melaka">Melaka</option>
                            <option value="Negeri Sembilan">Negeri Sembilan</option>
                            <option value="Pahang">Pahang</option>
                            <option value="Perak">Perak</option>
                            <option value="Perlis">Perlis</option>
                            <option value="Pulau Pinang">Pulau Pinang</option>
                            <option value="Sabah">Sabah</option>
                            <option value="Sarawak">Sarawak</option>
                            <option value="Selangor">Selangor</option>
                            <option value="Terengganu">Terengganu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>PROJECT NO.</label>
                        <div class="d-flex gap-1">
                            <input type="text" id="number_prefix" class="form-control" readonly placeholder="EHS/--/--/">
                            <input type="text" id="number_running" class="form-control" placeholder="001" maxlength="4" style="max-width:80px;">
                        </div>
                        <input type="hidden" id="number" name="number">
                    </div>

                    <input type="hidden" id="quotation_no" name="quotation_no" value="">

                    <div class="form-group">
                        <label for="pic">PIC</label>
                        <input type="text" id="pic" name="pic">
                    </div>

                    <div class="form-group">
                        <label for="pic_no">NO. OF PIC</label>
                        <input type="text" id="pic_no" name="pic_no">
                    </div>
                </div>
            </div>

            <!-- MODULE TABS & SECTIONS -->
            <div class="module-card">
                <div class="card border-0 shadow-sm overflow-hidden mb-4">
                    <ul class="nav custom-nav-tabs" id="moduleTabs" role="tablist">
                        @foreach($modules as $index => $module)
                        <li class="nav-item">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                    id="tab-{{ $module->module_id }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#module-{{ $module->module_id }}"
                                    type="button"
                                    role="tab"
                                    data-module-name="{{ strtoupper($module->module_name) }}">
                                <i class="ni {{ $module->icon ?? 'bi-folder' }} me-1"></i> {{ strtoupper($module->module_name) }}
                            </button>
                        </li>
                        @endforeach
                    </ul>

                    <div class="tab-content main-body p-3" id="categoryTabsContent">
                        @foreach($modules as $index => $module)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                             id="module-{{ $module->module_id }}" 
                             role="tabpanel"
                             aria-labelledby="tab-{{ $module->module_id }}">
                            @foreach($module->categories as $category)
                                @include('dashboard.section-card', [
                                    'target' => 'cat-' . $module->module_id . '-' . $category->category_id,
                                    'title' => strtoupper($category->category_name ?? $category->name),
                                    'isLast' => $loop->last 
                                ])
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- SUMMARY BREAKDOWN PANEL -->
<div class="row justify-content-between mb-4">

    <!-- PAYMENT TERMS & ADDITIONAL NOTES -->
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm p-3 h-100">

            <!-- PAYMENT TERMS -->
            <div class="d-flex justify-content-between text-uppercase text-muted small fw-bold pb-2 border-bottom">
                <span>PAYMENT TERMS</span>
            </div>

            <div id="paymentTermsList" class="mt-2"></div>

            <button type="button" id="addPaymentTermBtn" class="btn btn-sm btn-outline-primary mt-1 mb-3 align-self-start">
                <i class="bi bi-plus-lg me-1"></i> ADD PAYMENT
            </button>

            <input type="hidden" id="paymentTermsValue" name="payment_terms">

            <!-- ADDITIONAL NOTES -->
            <div class="form-group mt-2">
                <label for="additional_notes" class="text-uppercase text-muted small fw-bold">ADDITIONAL NOTES</label>
                <textarea id="additional_notes" name="additional_notes" rows="4"></textarea>
            </div>

        </div>
    </div>

            <!-- SERVICE BREAKDOWN -->
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm p-3">
                    <div class="d-flex justify-content-between text-uppercase text-muted small fw-bold pb-2 border-bottom">
                            <span>SERVICE BREAKDOWN</span>
                            <span>TOTAL</span>
                    </div>

                    <div id="serviceBreakdownContainer"></div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-2">
                            <span class="fw-bold text-uppercase fs-6">MODULE TOTAL (<span class="active-module-title">{{ strtoupper($modules->first()->module_name ?? 'MODULE') }}</span>)</span>
                            <span class="fw-bold fs-4 text-primary current-module-total">MYR0.00</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- STICKY BOTTOM SUMMARY BAR -->
            <div class="sticky-bottom-summary d-flex justify-content-between align-items-center p-3 rounded mb-4">
                <div class="all-modules-list d-flex align-items-center overflow-x-auto me-3 pe-2" style="max-width: 65%; white-space: nowrap;">
                    <span class="fw-bold text-uppercase text-primary all-module-list">ALL MODULES</span>
                </div>
                <div>
                    <span class="text-uppercase fw-bold text-muted me-3">GRAND TOTAL (ALL MODULES)</span>
                    <span class="fw-bold fs-4 text-primary grand-project-total">MYR 0.00</span>
                </div>
            </div>

            <!-- EDIT MODE BOTTOM BAR -->
            <footer class="bottom-bar">
                <div class="draft-info">
                    <strong>DRAFT MODE</strong>
                    <span class="footer-divider"></span>
                    <span id="lastSaved">Last autosaved at --:--:--</span>
                </div>

                <div class="bottom-actions d-flex gap-2">
                    <button type="button" class="preview-button btn btn-outline-primary" id="previewBtn">
                        <i class="fa-regular fa-eye me-1"></i> PREVIEW
                    </button>

                    <button type="button" id="saveQuotationBtn" class="btn btn-primary">
                        <i class="fa-regular fa-floppy-disk me-1"></i> SAVE / DOWNLOAD PDF
                    </button>
                </div>
            </footer>

        </div> <!-- /#editFormContainer -->

        <!-- ================================================================== -->
        <!-- 2. PREVIEW MODE CONTAINER (Hidden by default, shown via home.js) -->
        <!-- ================================================================== -->
        <div id="previewModeContainer" class="d-none">

            <!-- Preview Action Bar -->
            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm mb-4 sticky-top" style="top: 20px; z-index: 1020;">
                <button type="button" id="backToEditBtn" class="btn btn-secondary fw-bold shadow-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i> BACK TO EDIT
                </button>

            </div>

            <!-- Rendered Document View -->
            <div class="card border border-2 p-5 bg-white shadow-sm" id="quotationPreviewDocument" style="min-height: 800px; max-width: 1000px; margin: 0 auto;">
                <!-- JS dynamically builds the quotation table and project details here -->
                @include('dashboard.qtpreview')
            </div>

        </div> <!-- /#previewModeContainer -->

    </div> <!-- /.home-container -->

        <!-- Quotation Save Success & Print Prompt Dialog -->
        <div id="quotationSaveModal" class="modal" tabindex="-1" style="display: none; background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-success">
                    <i class="bi bi-check-circle-fill me-2"></i>Quotation Saved
                    </h5>
                </div>
                
                <div class="modal-body py-3">
                    <p class="mb-0 text-secondary">
                    The quotation has been successfully saved to the database. Would you like to view and print/export it as a PDF now?
                    </p>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" id="cancelQuotationSaveBtn" class="btn btn-outline-secondary">
                    No, stay here
                    </button>
                    <button type="button" id="confirmQuotationSaveBtn" class="btn btn-primary">
                    Yes, Print / Export PDF
                    </button>
                </div>

                </div>
            </div>
        </div>
        
    <script>
        window.adminModulesTree = @json($adminModulesTree ?? []);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>
</html>