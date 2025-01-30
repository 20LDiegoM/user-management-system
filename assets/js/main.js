document.addEventListener("DOMContentLoaded", function () {
    const signupForm = document.getElementById("signup-form");
    const loginForm = document.getElementById("login-form");
    const showSignup = document.getElementById("show-signup");
    const showLogin = document.getElementById("show-login");
    const alertBox = document.querySelector(".alert");

    /**
     * Handles toggling between the "Sign Up" and "Login" forms.
     * Only executes if all the necessary elements are present.
     */
    if (showSignup && showLogin && signupForm && loginForm) {
        // Show the "Sign Up" form and hide the "Login" form
        showSignup.addEventListener("click", function () {
            signupForm.classList.add("active");
            loginForm.classList.remove("active");
        });

        // Show the "Login" form and hide the "Sign Up" form
        showLogin.addEventListener("click", function () {
            loginForm.classList.add("active");
            signupForm.classList.remove("active");
        });
    }

    /**
     * Handles dismissing the alert message when the user clicks on it.
     * The alert message is hidden by adding the "hidden" class.
     */
    if (alertBox) {
        alertBox.addEventListener('click', function (e) {
            const target = e.currentTarget; // The element that triggered the event

            // Add the "hidden" class to hide the alert
            target.classList.add('hidden');
        });
    }
});
