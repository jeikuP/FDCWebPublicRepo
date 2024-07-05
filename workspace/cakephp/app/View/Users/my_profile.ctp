<!-- app/View/Users/my_profile.ctp -->

<!DOCTYPE html>
<html>

<head>
    <title>My Profile</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Include Open Iconic Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/open-iconic@1.1.1/font/css/open-iconic-bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <!-- Back Button -->
                        <a href="<?php echo $this->Html->url(array('controller' => 'conversations', 'action' => 'home')); ?>"
                            class="btn btn-secondary mr-3"><i class="bi bi-arrow-left"></i></a>
                        <h2 class="mb-0">My Profile</h2>
                    </div>
                    <div class="d-flex align-items-center">
                        <button id="editProfileButton" class="btn btn-primary mr-2"><i class="bi bi-pencil-square"></i>
                            Edit Profile</button>
                        <?php echo $this->element('dropdown_menu'); ?>
                    </div>
                </div>

                <!-- Display user details -->
                <div class="row mt-3">
                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <label for="profilePicInput" style="cursor: pointer;">
                            <?php if (!empty($user['User']['profile_pic'])): ?>
                                <img src="<?= $this->Html->url('/' . $user['User']['profile_pic']) ?>"
                                    class="rounded-circle bg-secondary" width="250" height="250" id="profilePicture">
                            <?php else: ?>
                                <img src="<?= $this->Html->url('/img/default.jpg') ?>" class="rounded-circle bg-secondary"
                                    width="200" height="200" id="profilePicture">
                            <?php endif; ?>
                        </label>
                        <input type="file" name="profile_pic" id="profilePicInput" class="form-control d-none">
                    </div>
                    <div class="col-md-8">
                        <form id="profileForm">
                            <!-- Error Messages -->
                            <div class="alert alert-danger d-none" id="errorMessages"></div>

                            <table class="table">
                                <tr>
                                    <th>Name:</th>
                                    <td>
                                        <span class="profile-value"><?= h($user['User']['name']) ?></span>
                                        <input type="text" name="name" class="form-control profile-input d-none"
                                            value="<?= h($user['User']['name']) ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter your name.
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Birthdate:</th>
                                    <td>
                                        <span
                                            class="profile-value"><?= h(date('F j, Y', strtotime($user['User']['birthdate']))) ?></span>
                                        <input type="date" name="birthdate" class="form-control profile-input d-none"
                                            value="<?= h($user['User']['birthdate']) ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter your birthdate.
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td><?= h($user['User']['gender']) ?></td>
                                </tr>
                                <tr>
                                    <th>Hobbies:</th>
                                    <td>
                                        <span class="profile-value"><?= h($user['User']['hobby']) ?></span>
                                        <input type="text" name="hobby" class="form-control profile-input d-none"
                                            value="<?= h($user['User']['hobby']) ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter your hobbies.
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>
                                        <span class="profile-value"><?= h($user['User']['email']) ?></span>
                                        <input type="email" name="email" class="form-control profile-input d-none"
                                            value="<?= h($user['User']['email']) ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid email address.
                                        </div>
                                    </td>
                                </tr>
                                <tr class="profile-input d-none">
                                    <th>New Password:</th>
                                    <td><input type="password" name="password" class="form-control" required></td>
                                </tr>
                                <tr class="profile-input d-none">
                                    <th>Confirm New Password:</th>
                                    <td><input type="password" name="confirm_password" class="form-control" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Login:</th>
                                    <td><?= h(date('F j, Y g:i A', strtotime($user['User']['last_login']))) ?></td>
                                </tr>
                                <tr>
                                    <th>Joined:</th>
                                    <td><?= h(date('F j, Y g:i A', strtotime($user['User']['created']))) ?></td>
                                </tr>
                            </table>
                            <button type="submit" class="btn btn-success profile-input d-none"
                                id="saveProfileButton">Save</button>
                            <button type="button" class="btn btn-secondary profile-input d-none"
                                id="cancelEditButton">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#editProfileButton').click(function () {
                $('.profile-value').addClass('d-none');
                $('.profile-input').removeClass('d-none');
            });

            $('#cancelEditButton').click(function () {
                $('.profile-value').removeClass('d-none');
                $('.profile-input').addClass('d-none');
                $('#errorMessages').addClass('d-none');
            });

            $('#profilePicture').click(function () {
                $('#profilePicInput').click();
            });

            $('#profilePicInput').change(function () {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#profilePicture').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            });

            $('#profileForm').submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);

                // Client-side form validation
                var valid = true;
                $('.profile-input').each(function () {
                    if ($(this).prop('required') && !$(this).val().trim()) {
                        $(this).addClass('is-invalid');
                        valid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!valid) {
                    $('#errorMessages').html('Please fill in all required fields.').removeClass('d-none');
                    return;
                }

                $.ajax({
                    url: '<?= $this->Html->url(['controller' => 'users', 'action' => 'updateProfileAjax']) ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Update the displayed profile values
                            $('span.profile-value').each(function () {
                                var inputField = $(this).siblings('input');
                                if (inputField.length) {
                                    $(this).text(inputField.val());
                                }
                            });
                            // Hide input fields and show profile values
                            $('.profile-value').removeClass('d-none');
                            $('.profile-input').addClass('d-none');
                            $('#errorMessages').addClass('d-none');
                        } else {
                            var errorMessage = '';
                            if (response.errors) {
                                $.each(response.errors, function (key, value) {
                                    errorMessage += value + '<br>';
                                });
                                $('#errorMessages').html(errorMessage).removeClass('d-none');
                            } else {
                                $('#errorMessages').html('Error: ' + response.message).removeClass('d-none');
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#errorMessages').html('Error: ' + error).removeClass('d-none');
                    }
                });
            });
        });
    </script>
</body>

</html>