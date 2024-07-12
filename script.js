document.getElementById('registrationForm').addEventListener('submit', function(event) {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm-password').value;
    var message = document.getElementById('message');

    if (password !== confirmPassword) {
        message.textContent = 'Passwords do not match!';
        event.preventDefault(); // Prevent form submission
    } else {
        message.textContent = '';
    }
});
