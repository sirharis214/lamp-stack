$(document).ready(function() {
	// Function to validate the username format
	function validateUsernameFormat(usernameInput, usernameFeedback) {
	  const username = usernameInput.value.trim();
      const usernameFormat = /^[a-zA-Z]+\.[a-zA-Z]+$/; // Format: string.string

	  if (!usernameFormat.test(username)) {
		usernameFeedback.textContent = 'Invalid username format (e.g., string.string)';
		usernameInput.classList.add('is-invalid');
		usernameInput.classList.remove('is-valid');
		return false;
	  } else {
		usernameFeedback.textContent = '';
		usernameInput.classList.remove('is-invalid');
		usernameInput.classList.add('is-valid');
		return true;
	  }
	}
	
	// Event listener for keyup on dynamic modals
    $(document).on('keyup', '.modal input[name="username"]', function () {
    	const usernameInput = this;
    	const usernameFeedback = this.nextElementSibling;
    	validateUsernameFormat(usernameInput, usernameFeedback);
  	});
  	
  	// Form submit event handler for dynamic modals
    $(document).on('submit', 'form[name="update-user-form"]', function (e) {
    	const usernameInput = this.elements['username'];
    	const usernameFeedback = usernameInput.nextElementSibling;

    	if (!validateUsernameFormat(usernameInput, usernameFeedback)) {
      		e.preventDefault(); // Prevent form submission
      		// Store error messages in session as JSON
      		//sessionStorage.setItem('updateUserErrorMessages', JSON.stringify(['Invalid username format (e.g., string.string)']));
    	}
    });
    
    // Check for stored error messages on page load
    /*var storedErrorMessagesJSON = sessionStorage.getItem('updateUserErrorMessages');
    if (storedErrorMessagesJSON) {
    	// Parse the stored error messages as an array
    	var storedErrorMessages = JSON.parse(storedErrorMessagesJSON);

    	// Display each stored error message
    	storedErrorMessages.forEach(function (errorMessage) {
      	$('#error-message').append('<p>' + errorMessage + '</p>').css('display', 'block');
    	});

    	// Clear stored error messages from session
    	sessionStorage.removeItem('updateUserErrorMessages');
  	}*/
});
