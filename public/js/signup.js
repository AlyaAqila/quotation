/* ============================================================
   GET HTML ELEMENTS
   ============================================================ */

/*
    Get the Password input field.
*/
const password = document.getElementById("password");

/*
    Get the Confirm Password input field.
*/
const confirmPassword = document.getElementById("confirmPassword");

/*
    Get the eye icon for Password.
*/
const togglePassword = document.getElementById("togglePassword");

/*
    Get the eye icon for Confirm Password.
*/
const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

/*
    Get the Sign Up form.
*/
const form = document.querySelector("form");


/* ============================================================
   SHOW / HIDE PASSWORD FUNCTION
   ============================================================ */

/*
    Instead of writing the same code twice,
    create one reusable function.

    Parameters:
    input  = Password textbox
    toggle = Eye icon
*/
function setupPasswordToggle(input, toggle) {

    toggle.addEventListener("click", function () {

        /*
            If the password is hidden,
            show it.
        */
        if (input.type === "password") {

            input.type = "text";

            /*
                Change icon:
                Eye  -> Eye Slash
            */
            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");

        } else {

            /*
                Hide password again.
            */
            input.type = "password";

            /*
                Change icon:
                Eye Slash -> Eye
            */
            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");

        }

    });

}

/*
    Activate password toggle.
*/
setupPasswordToggle(password, togglePassword);

/*
    Activate confirm password toggle.
*/
setupPasswordToggle(confirmPassword, toggleConfirmPassword);


/* ============================================================
   PASSWORD STRENGTH VALIDATION
   ============================================================ */

/*
    Every time the user types,
    check all password requirements.
*/
password.addEventListener("input", function () {

    updateStrength();

    validatePasswordMatch();

});

/* ============================================================
   PASSWORD STRENGTH BAR
   ============================================================ */

function updateStrength() {

    const value = password.value;

    let score = 0;

    if (value.length >= 8) score++;

    if (/[A-Z]/.test(value)) score++;

    if (/[a-z]/.test(value)) score++;

    if (/\d/.test(value)) score++;

    if (/[-_@$!%*?&]/.test(value)) score++;

    const fill = document.getElementById("strengthFill");

    const text = document.getElementById("strengthText");

    switch (score) {

        case 0:

            fill.style.width = "0%";
            fill.style.background = "#e74c3c";
            text.textContent = "Very Weak";

            break;

        case 1:

            fill.style.width = "20%";
            fill.style.background = "#e74c3c";
            text.textContent = "Very Weak";

            break;

        case 2:

            fill.style.width = "40%";
            fill.style.background = "#ff7f50";
            text.textContent = "Weak";

            break;

        case 3:

            fill.style.width = "60%";
            fill.style.background = "#f39c12";
            text.textContent = "Fair";

            break;

        case 4:

            fill.style.width = "80%";
            fill.style.background = "#9acd32";
            text.textContent = "Good";

            break;

        case 5:

            fill.style.width = "100%";
            fill.style.background = "#2ecc71";
            text.textContent = "Strong";

            break;

    }

}

/* ============================================================
   CONFIRM PASSWORD VALIDATION
   ============================================================ */

/*
    Check whether Password
    and Confirm Password match.
*/
function validatePasswordMatch() {

    const message = document.getElementById("passwordMatch");

    /*
        Hide the message
        if Confirm Password is empty.
    */
    if (confirmPassword.value === "") {

        message.style.display = "none";

        return;

    }

    /*
        Show the message.
    */
    message.style.display = "block";

    /*
        Compare both passwords.
    */
    if (password.value === confirmPassword.value) {

        message.classList.add("valid");
        message.classList.remove("invalid");

        message.innerHTML = "Passwords match.";

    } else {

        message.classList.add("invalid");
        message.classList.remove("valid");

        message.innerHTML = "Passwords do not match.";

    }

}

/*
    Check every time
    the user types Confirm Password.
*/
confirmPassword.addEventListener("input", validatePasswordMatch);


/* ============================================================
   FORM SUBMISSION VALIDATION
   ============================================================ */

/*
    Before the form is submitted,
    perform one final check.
*/
form.addEventListener("submit", function (event) {

    /*
        Passwords must match.
    */
    if (password.value !== confirmPassword.value) {

        event.preventDefault();

        alert("Passwords do not match.");

        return;

    }

    /*
        Password must satisfy
        all security requirements.
    */
    if (
        password.value.length < 8 ||
        !/[A-Z]/.test(password.value) ||
        !/[a-z]/.test(password.value) ||
        !/\d/.test(password.value) ||
        !/[-_@$!%*?&]/.test(password.value)
    ) {

        event.preventDefault();

        alert("Please create a stronger password.");

    }

});