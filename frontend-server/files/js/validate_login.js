$(document).ready(function() {
	function checkEmail() {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		var email = $('#email').val();
		var emailCheck = $('#email-check');
		var emailX = $('#email-x');
        
        if (!email) {
        	emailCheck.css('display', 'none');
        	emailX.css('display', 'none');	
        } else if (emailRegex.test(email) ) {
            emailCheck.css('display', 'flex');
        	emailX.css('display', 'none');	
        	return true;
        } else if (!emailRegex.test(email) ) {
            emailCheck.css('display', 'none');
        	emailX.css('display', 'flex');
        	return false;
        }
	}
	
	function checkPassword() {
    	var password = $('#password').val();
		var passwordCheck = $('#password-check');
		var passwordX = $('#password-x');
				
        if (!password) {
        	passwordCheck.css('display', 'none');
        	passwordX.css('display', 'none');	
        } else if (password == null || password == "") {
        	passwordCheck.css('display', 'none');
            passwordX.css('display', 'flex');
            return false;
        } else {
        	passwordCheck.css('display', 'flex');
            passwordX.css('display', 'none');
            return true;
        }
	}
	
	// Form submit event handler
    $('form').on('submit', function(e) {
        // Check passwords and email before submitting the form
		errorMessages = [];
	    
	    if (!checkPassword() ) {
        	errorMessages.push('Password field can not be empty.');
        }
        
        if (!checkEmail() ) {
        	errorMessages.push('Email is not a valid format.');
        }
            
        if (errorMessages.length > 0) {
            e.preventDefault(); // Prevent form submission

            // Store error messages in session as JSON
            sessionStorage.setItem('loginErrorMessages', JSON.stringify(errorMessages) );
        }
        
        // Redirect to the registration page
        window.location.href = 'index.php';
    });
    
    // Check for stored error messages on page load
    var storedErrorMessagesJSON = sessionStorage.getItem('loginErrorMessages');
    if (storedErrorMessagesJSON) {
        // Parse the stored error messages as an array
        var storedErrorMessages = JSON.parse(storedErrorMessagesJSON);

        // Display each stored error message
        storedErrorMessages.forEach(function(errorMessage) {
            $('#error-message').append('<p>' + errorMessage + '</p>').css('display', 'block');
        });

        // Clear stored error messages from session
        sessionStorage.removeItem('loginErrorMessages');
    }
	
	// Keyup event handler for email
    $('#email').on('keyup', function() {
        checkEmail();
    });	
	
});
