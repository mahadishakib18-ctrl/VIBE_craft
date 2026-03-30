function validateLogin() {
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();
  const emailError = document.getElementById('email-error');
  const passwordError = document.getElementById('password-error');
  const submitError = document.getElementById('submit-error');

  let valid = true;

  if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
    emailError.textContent = "Enter a valid email address.";
    valid = false;
  } else {
    emailError.textContent = "";
  }

  if (password.length < 6) {
    passwordError.textContent = "Password must be at least 6 characters.";
    valid = false;
  } else {
    passwordError.textContent = "";
  }

  if (!valid) {
    submitError.textContent = "❌ Fix the errors above before submitting.";
    submitError.style.color = "red";
  } else {
    submitError.textContent = "";
  }

  return valid;
}
