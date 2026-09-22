<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Data Management</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="https://laravel.com/img/logomark.min.svg">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/module-item.css') }}">
</head>

<body>

    <!-- Navigation Bar -->
    @include('dashboard.navbar')

    <!-- MAIN WRAPPER TO CENTER CONTENT -->
    <div class="admin-container">

        <!-- PAGE TITLE & BUTTON -->
        <div class="page-header-wrapper">
            <h2 class="page-header">
                <i class="bi bi-clipboard-data"></i>
                DATA MANAGEMENT
            </h2>

            <!-- CREATE NEW ITEM BUTTON -->
            <a href="{{ route('newItem') }}">
                <button type="button" class="create-button" id="createButton">
                    <i class="fa-solid fa-plus"></i> CREATE NEW
                </button>
            </a>
        </div>
        
        <!-- SEARCH & FILTER -->
        <div class="filter-card">
            <div class="search-box">
                <label>
                    SEARCH
                    <i class="bi bi-search"></i>
                </label>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search item...">
            </div>
        </div>

        <!-- MODULE ITEMS TABLE GROUPS -->
        @forelse ($dataGroups as $groupName => $group)
            @include('dashboard.module-item', [
                'title' => $groupName,
                'items' => $group['items'] ?? $group,
                'categoryId' => $group['id'] ?? null
            ])
        @empty
            <section class="data-group">
                <div class="table-card" style="padding: 20px; text-align: center;">
                    <p>No data groups found.</p>
                </div>
            </section>
        @endforelse          

    </div> <!-- END admin-container -->
    
    <script>
        window.adminModulesTree = @json($adminModulesTree);
    </script>
    <!-- Bootstrap JS & Admin JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>