// Fungsi untuk menampilkan pesan sukses
function showSuccess(message) {
  const successMessage = document.createElement('div');
  successMessage.classList.add('success-message');
  successMessage.innerHTML = message;
  document.body.appendChild(successMessage);
  setTimeout(() => {
    successMessage.remove();
  }, 3000);
}

// Fungsi untuk menampilkan pesan error
function showError(message) {
  const errorMessage = document.createElement('div');
  errorMessage.classList.add('error-message');
  errorMessage.innerHTML = message;
  document.body.appendChild(errorMessage);
  setTimeout(() => {
    errorMessage.remove();
  }, 3000);
}

// Fungsi untuk mengirimkan form
function submitForm(form) {
  const formData = new FormData(form);
  fetch(form.action, {
    method: form.method,
    body: formData,
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showSuccess(data.message);
    } else {
      showError(data.message);
    }
  })
  .catch(error => {
    console.error(error);
  });
}

// Tambahkan event listener ke form
document.addEventListener('submit', (event) => {
  event.preventDefault();
  const form = event.target;
  submitForm(form);
});
