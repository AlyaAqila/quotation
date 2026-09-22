<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign up</title>

        <link rel="stylesheet" href="{{ asset('css/signup.css') }}">

        <!--google font-->
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

        <!--font-->
        <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="background">
        <div class="signup-card">

        <!--header-->
        <div class="card-header">
            <div class="header-title">
            <i class= "fa-solid fa-user"></i>
            <span>Create Account</span>
        </div>

            <p class="subtitle">
            Enter your information to create a new account.
        </p>
    </div>

    <!--body-->
    <div class="card-body">

    <form method="POST" action="{{ route('signup') }}">
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

            <!--name-->
            <label>Name</label>
            <div class="input-box">
                <i class="fa-regular fa-user"></i>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter your name"
                    required>
                </div>


            <!--email-->
            <label>Email</label>
            
            <div class="input-box">
                <i class="fa-regular fa-envelope"></i>

                <input
                    type="email"
                    name="email"
                    placeholder="example@gmail.com"
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
                    minlength="8"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[-_@$!%*?&#]).{8,}$"
                    title="Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character."
                    required>

                    <i class="fa-regular fa-eye" id="togglePassword"></i>

                </div>

                <p class="password-hint">
                    Minimum 8 characters with uppercase, lowercase, number and special character (-_@$!%*?&).
                </p>

                <div class="strength-container">

                    <div class="strength-bar">
                        <div id="strengthFill"></div>
                    </div>

                    <span id="strengthText">
                        Weak
                    </span>

                </div>
                <p id="passwordMatch" class="password-match"></p>

                <!--confirm password-->
                <label>Confirm Password</label>

                <div class="input-box">
                    
                    <input
                    type="password"
                    id="confirmPassword"
                    name="password_confirmation"
                    placeholder="Confirm your password"
                    required>

                    <i class="fa-regular fa-eye" id="toggleConfirmPassword"></i>

                </div>

                <p id="passwordMatch" class="password-match"></p>     

                <button type="submit" class="signup-btn">
                    SIGN UP
                </button>

                <hr>

                <div class="new-user">
                    Already have an account?
                </div>

                <a href="/signin" class="signin-btn">
                    SIGN IN
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </form>
        </div>
    </div>
</div>

 <script src="{{ asset('js/signup.js') }}"></script>

</body>
</html>


