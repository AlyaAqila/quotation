<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign in</title>

        <link rel="stylesheet" href="{{ asset('css/signin.css') }}">

        <!--google font-->
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

        <!--font-->
        <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="background">
        <div class="signin-card">

        <!--header-->
        <div class="card-header">
            <div class="header-title">
            <i class= "fa-solid fa-user"></i>
            <span>SIGN IN</span>
        </div>

            <p class="subtitle">
            Enter your credentials to access the dashboard.
        </p>
    </div>

    <!--body-->
    <div class="card-body">

    <form method="POST" action="{{ route('signin') }}">
        @csrf

        @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
            @endif

            <!--email-->
            <label>Email</label>
            
            <div class="input-box">
                <i class="fa-regular fa-envelope"></i>

                <input
                    type="email"
                    name="email"
                    placeholder="admin@gmail.com"
                    required>
                </div>

                <!--password-->
                <label>Password</label>

                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>

                    <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required>

                    <i class="fa-regular fa-eye" id="togglePassword"></i>

                </div>

                <div class="forgot-password">
                    <a href="#">Forgot password?</a>
                </div>

                <button type="submit" class="signin-btn">
                    SIGN IN
                </button>

                <hr>

                <div class="new-user">
                    Don't have an account?
                </div>

                <a href="/signup" class="signup-btn">
                    SIGN UP
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/signin.js') }}"></script>

</body>
</html>


