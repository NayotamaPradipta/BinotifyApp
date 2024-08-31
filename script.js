document.addEventListener('DOMContentLoaded', function(){ 
    const registrationForm = document.getElementById('registrationForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const message = document.getElementById('message'); 

    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const usernameMessage = document.getElementById('usernameMessage'); 
    const emailMessage = document.getElementById('emailMessage');
    const submitButton = registrationForm.querySelector('button[type="submit"]');

    let isUsernameAvailable =  true; 
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

    usernameInput.addEventListener('blur', debounce(function() {
        checkAvailability('username', usernameInput.value, usernameMessage, usernameInput);
    }, 2000));

    emailInput.addEventListener('blur', debounce(function(){ 
        checkAvailability('email', emailInput.value, emailMessage, emailInput);
    }, 2000));

    registrationForm.addEventListener('submit', function(event) { 
        var password = passwordInput.value; 
        var confirmPassword = confirmPasswordInput.value;

        if (password !== confirmPassword){ 
            message.textContent = 'Passwords do not match!'; 
            event.preventDefault();
        } else { 
            message.textContent = '';
        }
    });

    function checkAvailability(type, value, messageElement, inputElement){ 
        const xhr = new XMLHttpRequest(); 
        xhr.open('POST', 'isUnique.php', true); 
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function(){ 
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status == 200){ 
                const response = JSON.parse(xhr.responseText);
                if (response.exists){ 
                    messageElement.textContent = type.charAt(0).toUpperCase() + type.slice(1) + ' is already taken';
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
            }
        };

        xhr.send(`${type}=${encodeURIComponent(value)}`);
    }
});