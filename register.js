const nameError = document.getElementById('name-error');
const emailError = document.getElementById('email-error');
const phoneError = document.getElementById('phone-error');
const submitError = document.getElementById('submit-error');

function nameValidate() {
  const first = document.getElementById('first_name').value.trim();
  const last = document.getElementById('last_name').value.trim();
  if (first.length === 0 || last.length === 0) {
    nameError.innerHTML = "First and Last name are required";
    return false;
  }
  if (!first.match(/^[a-zA-Z]+$/) || !last.match(/^[a-zA-Z]+$/)) {
    nameError.innerHTML = "Use alphabetic characters only";
    return false;
  }
  nameError.innerHTML = "";
  return true;
}

function emailValidate() {
  const email = document.getElementById('email').value.trim();
  if (email.length === 0) {
    emailError.innerHTML = "Email is required";
    return false;
  }
  if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,4}$/)) {
    emailError.innerHTML = "Enter a valid email";
    return false;
  }
  emailError.innerHTML = "";
  return true;
}

function phoneValidate() {
  const phone = document.getElementById('phone').value.trim();
  if (phone.length === 0) {
    phoneError.innerHTML = "Phone is required";
    return false;
  }
  if (!phone.match(/^01[0-9]{9}$/)) {
    phoneError.innerHTML = "Enter valid 11-digit Bangladeshi number";
    return false;
  }
  phoneError.innerHTML = "";
  return true;
}

function validateForm() {
  const validName = nameValidate();
  const validEmail = emailValidate();
  const validPhone = phoneValidate();

  if (validName && validEmail && validPhone) {
    submitError.innerHTML = "✅ Form is valid and ready to submit!";
    submitError.style.color = 'green';
    return true;
  } else {
    submitError.innerHTML = "❌ Please fix the errors above.";
    submitError.style.color = 'red';
    return false;
  }
}
