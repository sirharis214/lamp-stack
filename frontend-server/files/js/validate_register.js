$(document).ready(function() {
	
	function checkPasswords() {
    	var password = $('#password').val();
        var confirmPassword = $('#confirm-password').val();
		var passwordCheck = $('#password-check');
		var passwordX = $('#password-x');
				
        if (!password && !confirmPassword) {
        	passwordCheck.css('display', 'none');
        	passwordX.css('display', 'none');	
        } else if (password === confirmPassword) {
        	passwordCheck.css('display', 'flex');
            passwordX.css('display', 'none');
            return true;
        } else {
        	passwordCheck.css('display', 'none');
            passwordX.css('display', 'flex');
            return false;
        }
	}
	
	function checkEmail() {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		var email = $('#email').val();
		var emailCheck = $('#email-check');
		var emailX = $('#email-x');
        
        if (!email){
        	emailCheck.css('display', 'none');
        	emailX.css('display', 'none');	
        } else if (emailRegex.test(email) ) {
            emailCheck.css('display', 'flex');
        	emailX.css('display', 'none');	
        	return true;
        } else if (!emailRegex.test(email) ){
            emailCheck.css('display', 'none');
        	emailX.css('display', 'flex');
        	return false;
        }
	}
	
	// Form submit event handler
    $('form').on('submit', function(e) {
        // Check passwords and email before submitting the form
		errorMessages = [];
	    
	    if (!checkPasswords() ) {
        	errorMessages.push('Passwords do not match.');
        }
        
        if (!checkEmail() ) {
        	errorMessages.push('Email was not a valid format.');
        }
            
        if (errorMessages.length > 0) {
            e.preventDefault(); // Prevent form submission

            // Store error messages in session as JSON
            sessionStorage.setItem('registrationErrorMessages', JSON.stringify(errorMessages) );
        }
        
        // Redirect to the registration page
        window.location.href = 'register.php';
    });
    
    // Check for stored error messages on page load
    var storedErrorMessagesJSON = sessionStorage.getItem('registrationErrorMessages');
    if (storedErrorMessagesJSON) {
        // Parse the stored error messages as an array
        var storedErrorMessages = JSON.parse(storedErrorMessagesJSON);

        // Display each stored error message
        storedErrorMessages.forEach(function(errorMessage) {
            $('#error-message').append('<p>' + errorMessage + '</p>').css('display', 'block');
        });

        // Clear stored error messages from session
        sessionStorage.removeItem('registrationErrorMessages');
    }

    // Keyup event handlers for password and confirm password
    $('#password, #confirm-password').on('keyup', function() {
        checkPasswords();
    });

    // Keyup event handler for email
    $('#email').on('keyup', function() {
        checkEmail();
    });
});

