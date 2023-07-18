$(document).ready(function() {
	$('#password, #confirm-password').on('keyup', function() {
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
        } else {
        	passwordCheck.css('display', 'none');
            passwordX.css('display', 'flex');
        }
	});
	
	$('#email').on('keyup', function() {
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
        } else if (!emailRegex.test(email) ){
            emailCheck.css('display', 'none');
        	emailX.css('display', 'flex');
        }
	});
});

