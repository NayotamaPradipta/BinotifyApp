document.addEventListener('DOMContentLoaded', function (){ 
    const registrationForm = document.getElementById('registrationForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password'); 
    const message = document.getElementById('message-register'); 

    const userNameInput = document.getElementById('username');
    const emailInput = document.getElementById('email'); 
    const usernameMessage = document.getElementById('usernameMessage'); 
    const emailMessage = document.getElementById('emailMessage'); 
    const submitButton = registrationForm.querySelector('button[type="submit"]');

    let isUsernameAvailable = true; 
    let isEmailAvailable = true; 

    function debounce(func, delay){ 
        let debounceTimer; 
        return function(){ 
            const context = this; 
            const args = arguments; 
            clearTimeout(debounceTimer); 
            debounceTimer = setTimeout(() => func.apply(context, args), delay); 
        };
    }

    function checkAvailability(type, value, messageElement, inputElement){ 
        const params = new URLSearchParams(); 
        params.append('type', type); 
        params.append('value', value);
        fetch('/api/isUnique.php', {
            method: 'POST', 
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: params.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) { 
                messageElement.textContent = `${type.charAt(0).toUpperCase() + type.slice(1)} is already taken`;
                inputElement.classList.add('input-error');
                if (type === 'username'){ 
                    isUsernameAvailable = false;
                } else { 
                    isEmailAvailable = false;
                }
            } else { 
                messageElement.textContent = ''; 
                inputElement.classList.remove('input-error'); 
                if (type === 'username') { 
                    isUsernameAvailable = true; 
                } else { 
                    isEmailAvailable = true; 
                }
            }
            
            submitButton.disabled = !isUsernameAvailable || !isEmailAvailable; 
        })
        .catch(error => { 
            console.error('Error checking availability: ', error); 
        });
    }

    function validatePassword(){ 
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value; 
        const regex = /^(?=.*[0-9!@#\$%\^&\*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

        if (!regex.test(password)) { 
            message.textContent = 'Password must be at least 8 characters long, contain at least one number or symbol, one uppercase letter, and one lowercase letter.';
            return false; 
        } else if (password !== confirmPassword) {
            message.textContent = 'Passwords do not match.';
            return false;
        } else {
            message.textContent = '';
            return true;
        }
    }

    function registerUser(){ 
        const formData = {
            username: userNameInput.value,
            email: emailInput.value,
            password: passwordInput.value
        }

        fetch('/api/register.php', {
            method: 'POST', 
            headers: { 'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => { 
            if (data.success){ 
                window.location.href = '/login.html';
            } else {
                message.textContent = data.message || 'Registration failed.';
            }
        })
        .catch(error => {
            console.error('Error during registration: ', error); 
            message.textContent = 'An error occured during registration';
        });

    }

    userNameInput.addEventListener('blur', debounce(function() {
        checkAvailability('username', userNameInput.value, usernameMessage, userNameInput);
    }, 2000));

    emailInput.addEventListener('blur', debounce(function() {
        checkAvailability('email', emailInput.value, emailMessage, emailInput);
    }, 2000));

    registrationForm.addEventListener('submit', function(event){ 
        event.preventDefault(); 
        if (validatePassword()) { 
            registerUser();
        }
    });

});