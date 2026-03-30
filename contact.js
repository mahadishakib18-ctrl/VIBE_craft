document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();

    if (!name || !email || !message) {
      alert("⚠️ Please fill in all fields.");
      return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "contact.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
      if (this.status === 200) {
        if (this.responseText.includes("Message sent successfully")) {
          alert("✅ Message sent successfully!");
          form.reset();
        } else {
          alert("❌ " + this.responseText);
        }
      } else {
        alert("❌ Server error. Please try again later.");
      }
    };

    const data = `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&message=${encodeURIComponent(message)}`;
    xhr.send(data);
  });
});
