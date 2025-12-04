const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');
const forgotLink = document.getElementById('forgot');

if (registerBtn) {
    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });
}

if (loginBtn) {
    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });
}

if (forgotLink) {
    forgotLink.addEventListener('click', (e) => {
        e.preventDefault();
        container.classList.add("active");
    });
}

const flashMessageDiv = document.getElementById('flash-message');
if (flashMessageDiv) {
    const msg = flashMessageDiv.getAttribute('data-message');
    if (msg) {
        alert(msg);
    }
}


