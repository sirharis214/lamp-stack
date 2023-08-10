$(document).ready(function() {
  // Function to validate the username format
  function isValidUsername(username) {
    // Add your username validation logic here
    // For example, you can use a regular expression to check the format
    // Replace the 'regexPattern' with your desired pattern
    const regexPattern = /^[a-zA-Z]+\.[a-zA-Z]+$/;
    return regexPattern.test(username);
  }

  // Function to validate the username format and show feedback
  function validateUsernameFormat(usernameInput, usernameFeedback) {
    const username = usernameInput.value;
    if (!isValidUsername(username)) {
      usernameFeedback.textContent = "Username format is invalid. It should be in the format 'string.string'.";
      usernameInput.classList.add('is-invalid');
      usernameInput.classList.remove('is-valid');
    } else {
      usernameFeedback.textContent = ""; // Clear the error message
      usernameInput.classList.add('is-valid');
      usernameInput.classList.remove('is-invalid');
    }
  }

  // Event listener for keyup on dynamic modals
  $(document).on('keyup', '.modal input[name="username"]', function() {
    const usernameInput = this;
    const usernameFeedback = this.nextElementSibling;
    validateUsernameFormat(usernameInput, usernameFeedback);
  });

  // Function to handle the form submission
  function handleSubmit(action, modalId) {
    const modal = $("#" + modalId);

    // Update the value of the hidden input named "action"
    modal.find("#action").val(action);

    // Submit the form
    modal.find("form").submit();
  }

  // Click event for "Update User" button
  $(document).on("click", "#update-user", function(event) {
    const modalId = $(this).closest(".modal").attr("id");
    handleSubmit("update-user", modalId);

    // Prevent the default form submission behavior
    event.preventDefault();
  });

  // Click event for "Confirm Delete" button
  $(document).on("click", "#confirm-delete", function() {
    const modalId = $(this).closest(".modal").attr("id");
    handleSubmit("delete-user", modalId);
  });
});

