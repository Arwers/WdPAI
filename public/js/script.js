document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('.register');
    const emailInput = form.querySelector('input[name="email"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const repeatPasswordInput = form.querySelector('input[name="repeat_password"]');

    // Validate email on the fly
    emailInput.addEventListener('input', function() {
        const emailValue = emailInput.value.trim();
        if (validateEmail(emailValue)) {
            emailInput.style.border = "";
        } else {
            emailInput.style.border = "2px solid red";
        }
    });

    // Validate password matching on the fly
    function validatePasswords() {
        if (passwordInput.value === repeatPasswordInput.value) {
            passwordInput.style.border = "";
            repeatPasswordInput.style.border = "";
        } else {
            passwordInput.style.border = "2px solid red";
            repeatPasswordInput.style.border = "2px solid red";
        }
    }

    passwordInput.addEventListener('input', validatePasswords);
    repeatPasswordInput.addEventListener('input', validatePasswords);

    // Final check on form submission
    form.addEventListener('submit', function(event) {
        let valid = true;

        // Validate email format
        const emailValue = emailInput.value.trim();
        if (!validateEmail(emailValue)) {
            emailInput.style.border = "2px solid red";
            valid = false;
        }

        // Validate matching passwords
        if (passwordInput.value !== repeatPasswordInput.value) {
            passwordInput.style.border = "2px solid red";
            repeatPasswordInput.style.border = "2px solid red";
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });

    // Function to check if an email is valid
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
