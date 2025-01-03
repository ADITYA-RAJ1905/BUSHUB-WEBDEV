document.addEventListener('DOMContentLoaded', function () {
    const loginButton = document.querySelector('button[type="button"]');
    loginButton.addEventListener('click', login);
});

function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('errorMessage');

    if (username === 'user' && password === 'password') {
        alert('Login successful!');
        window.location.href = 'bushub.html'; // Redirect to the next page
    } else {
        errorMessage.style.display = 'block';
    }
}
