<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modelling</title>

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
    
</head>

<body>

    <!-- Navigation Bar -->
    @include('dashboard.navbar')

    <!-- Home Page Content Container -->
    <div class="home-container">

        <h2 class="page-title">
            <i class="bi bi-file-earmark-ruled-fill"></i>
            MODELLING
        </h2>
    </div>

</body>
</html>