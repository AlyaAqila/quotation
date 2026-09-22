/* ============================================================
   SHOW / HIDE PASSWORD
   ============================================================ */

const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

togglePassword.addEventListener("click", function() {
    /*
        Check whether the password is currently hidden.

        password.type returns:
        - "password" → Hidden (••••••)
        - "text"     → Visible (Password123)
    */
    if (password.type === "password") {
        /*
            Change the input type to "text"
            so the password becomes visible.
        */   
        password.type = "text";

        this.classList.remove("fa-eye");
        this.classList.add("fa-eye-slash");

    } else {
        /*
            If the password is already visible,
            change it back to hidden.
        */
        password.type = "password";

        this.classList.remove("fa-eye-slash");
        this.classList.add("fa-eye");
    }
});