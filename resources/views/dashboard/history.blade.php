<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>History</title>

    <!-- Third-Party CSS Libraries (Bootstrap & Font Icons) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Custom Style Files -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/history.css') }}">
</head>

<body>

    <!-- Load Top Navigation Bar -->
    @include('dashboard.navbar')

    <!-- Main Content Layout Wrapper -->
    <div class="history-container">

        <!-- Page Heading Section -->
        <div class="page-header-wrapper">
            <h2 class="page-header">
                <i class="bi bi-clock-history"></i>
                HISTORY
            </h2>
        </div>
        
        <!-- Search Input and Filter Bar -->
        <div class="filter-card">
            <!-- Text Input for Keyword Searching -->
            <div class="search-box">
                <label>
                    SEARCH
                    <i class="bi bi-search"></i>
                </label>
                <input
                    type="text"
                    id="searchHistoryInput"
                    placeholder="Search project...">
            </div>

            <!-- Dropdown for LocationS Filtering -->
            <div class="location-box">
                <label>
                    FILTER BY LOCATION
                    <i class="bi bi-geo-alt"></i>
                </label>
                <select id="locationFilter">
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

            <div class="type-box">
                <label>
                    PROJECT TYPE
                    <i class="bi bi-tags"></i>
                </label>

                <select id="typeFilter">
                    <option value="">Choose Project Type</option>
                    <option value="1">CP - Coastal Project</option>
                    <option value="2">RP - River Project</option>
                    <option value="3">MP - Maritime Project</option>
                    <option value="4">GP - Geotech Project</option>
                    <option value="5">JP - Jetty Project</option>
                </select>
            </div>

            <!-- Dropdown for Month Filtering -->
            <div class="month-box">
                <label>
                    FILTER BY MONTH
                    <i class="bi bi-calendar3"></i>
                </label>
                <select id="monthFilter">
                    <option value="">Choose Month</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>

        </div>

        <!-- History Data Table Container -->
        <div class="history-content">
            <div class="table-container">
                <table class="project-table">
                    <thead>
                        <tr>
                            <th class="col-number">#</th>
                            <th class="col-project">PROJECT</th>
                            <th class="col-project-no">PROJECT NO.</th>
                            <th class="col-client">CLIENT</th>
                            <th class="col-date">DATE</th>
                            <th class="col-action">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($quotations as $index => $item)
                        <tr>
                            <!-- Row Index -->
                            <td>{{ $loop->iteration }}</td>
                            
                            <!-- Project Name -->
                            <td>{{ $item->project?->name ?? $item->project?->project_name ?? 'N/A' }}</td>
                            
                            <!-- Project Number -->
                            <td>{{ $item->project?->number ?? $item->project?->number ?? $item->quotation_no }}</td>
                            
                            <!-- Client Name -->
                            <td>
                                <span class="client-text">
                                    {{ $item->project?->client?->company_name ?? $item->project?->client?->name ?? 'N/A' }}
                                </span>
                            </td>
                            
                            <!-- Date Created -->
                            <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') : 'N/A' }}</td>
                            
                            <!-- Actions -->
                            <td class="action-buttons">

                                @if($item->project)
                                <button type="button" class="edit-btn" title="Edit"
                                        data-update-url="{{ route('projects.update', $item->project->project_Id) }}"
                                        data-project="{{ json_encode($item->project) }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                @endif
                                
                                <a href="{{ route('quotations.show', $item->quotation_Id) }}" class="view-btn" title="View">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('quotations.invoice', $item->quotation_Id) }}" class="inv-btn" title="Invoice">
                                    <i class="bi bi-archive"></i>
                                </a>
                                

                                <button type="button" class="delete-btn" 
                                        title="Delete" 
                                        data-id="{{ $item->quotation_Id }}" 
                                        data-delete-url="{{ route('quotations.destroy', $item->quotation_Id) }}">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">No history records found.</td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Hidden Confirmation Modal Pop-up for Deleting Records -->
    <div id="deleteModalOverlay" class="modal-overlay">
        <div class="modal-card">
            <h3>Delete Quotation</h3>
            <p>Are you sure you want to delete this quotation? This action cannot be undone.</p>
            
            <!-- Delete Form: Action URL is set dynamically by history.js -->
            <form id="deleteQuotationForm" method="POST" action="">
                @csrf
                @method('DELETE')
                
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="cancelDeleteBtn">Cancel</button>
                    <button type="submit" class="btn-confirm">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Project Details Modal -->
    <div id="editProjectModalOverlay" class="modal-overlay">
        <div class="modal-card modal-card-lg">
            <h3>Edit Project Details</h3>

            <form id="editProjectForm">
                <div class="project-card">
                    <div class="project-grid">

                        <div class="form-group">
                            <label for="edit_project_name">PROJECT NAME</label>
                            <input type="text" id="edit_project_name" name="project_name">
                        </div>

                        <div class="form-group">
                            <label for="edit_period">PERIOD</label>
                            <input type="text" id="edit_period" name="period" placeholder="e.g. 45 Days">
                        </div>

                        <div class="form-group">
                            <label for="edit_client_name">CLIENT</label>
                            <input type="text" id="edit_client_name" name="client_name">
                        </div>

                        <div class="form-group">
                            <label for="edit_client_address">CLIENT ADDRESS</label>
                            <textarea id="edit_client_address" name="client_address" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="edit_location">LOCATION</label>
                            <select id="edit_location" name="location">
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
                            <input type="text" id="edit_number" readonly>
                        </div>

                        <div class="form-group">
                            <label for="edit_pic">PIC</label>
                            <input type="text" id="edit_pic" name="pic">
                        </div>

                        <div class="form-group">
                            <label for="edit_pic_no">NO. OF PIC</label>
                            <input type="text" id="edit_pic_no" name="pic_no">
                        </div>

                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="cancelEditBtn">Cancel</button>
                    <button type="submit" class="btn-confirm">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- PREVIEW MODAL CONTAINER (Place at the bottom of history.blade.php) -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Quotation Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-white">
                    @include('dashboard.preview')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="fa-solid fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Attachments -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/history.js') }}"></script>
</body>
</html>