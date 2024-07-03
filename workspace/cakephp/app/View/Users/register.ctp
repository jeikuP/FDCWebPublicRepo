<!DOCTYPE html>
<html>

<head>
    <title>User Registration</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JS for Validation and AJAX -->
    <script>
        $(document).ready(function () {
            // Function to check if email already exists
            function checkEmailExists(email, successCallback) {
                $.ajax({
                    type: 'GET',
                    url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'check_email')); ?>',
                    data: { email: email },
                    dataType: 'json',
                    success: successCallback,
                    error: function (xhr, status, error) {
                        console.error('AJAX Error: ' + status + ' - ' + error);
                    }
                });
            }

            // Handle form submission
            $('#registerForm').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission

                var name = $('#UserName').val();
                var email = $('#UserEmail').val();
                var password = $('#UserPassword').val();
                var confirmPassword = $('#UserPasswordConfirm').val();
                var valid = true;
                var errorMessage = '';

                // Validate name
                if (name.length < 5 || name.length > 20) {
                    valid = false;
                    errorMessage += '<li>Name must be between 5 and 20 characters.</li>';
                }

                // Validate email format
                var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                if (!emailRegex.test(email)) {
                    valid = false;
                    errorMessage += '<li>Please enter a valid email address.</li>';
                }

                // Validate password length
                if (password.length < 5 || password.length > 20) {
                    valid = false;
                    errorMessage += '<li>Password is too short.</li>';
                }

                // Validate password match
                if (password !== confirmPassword) {
                    valid = false;
                    errorMessage += '<li>Passwords do not match.</li>';
                }

                // Check if email already exists
                checkEmailExists(email, function (response) {
                    if (response.exists) {
                        valid = false;
                        errorMessage += '<li>This email is already registered.</li>';
                    }

                    // Display error messages if any
                    if (!valid) {
                        $('#errorMessages').html(errorMessage).show();
                    } else {
                        // If all validations pass, submit the form
                        $('#registerForm')[0].submit();
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <h2>Register</h2>
                <div id="errorMessages" class="alert alert-danger" style="display: none;"></div>
                <?php if (!empty($this->Session->read('ValidationErrors'))): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($this->Session->read('ValidationErrors') as $error): ?>
                            <?php echo h($error); ?>
                        <?php endforeach; ?>
                    </div>
                    <?php $this->Session->delete('ValidationErrors'); endif; ?>

                <?php
                echo $this->Form->create('User', array('id' => 'registerForm'));
                echo $this->Form->input('name', array('label' => 'Name', 'class' => 'form-control', 'required' => true));
                echo $this->Form->input('email', array('label' => 'Email', 'class' => 'form-control', 'required' => true));
                echo $this->Form->input('password', array('label' => 'Password', 'class' => 'form-control', 'type' => 'password', 'required' => true));
                echo $this->Form->input('password_confirm', array('label' => 'Confirm Password', 'class' => 'form-control', 'type' => 'password', 'required' => true)) . '<br>';
                echo $this->Form->button('Register', array('type' => 'submit', 'class' => 'btn btn-primary'));
                echo $this->Html->link('Login', '/login', array('class' => 'btn btn-secondary ml-3'));
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</body>

</html>