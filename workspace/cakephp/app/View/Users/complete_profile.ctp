<!-- app/View/Users/complete_profile.ctp -->

<!DOCTYPE html>
<html>

<head>
    <title>Complete Your Profile</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-center">Complete Your Profile</h2>
                    <?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?>
                </div>

                <!-- Display existing user data if available -->
                <form method="post"
                    action="<?= $this->Html->url(['controller' => 'users', 'action' => 'updateProfile']) ?>"
                    enctype="multipart/form-data" id="profileForm">

                    <!-- Profile Picture Holder -->
                    <div class="text-center mb-4">
                        <?php if (isset($user['User']['profile_pic']) && !empty($user['User']['profile_pic'])): ?>
                            <img src="<?= $user['User']['profile_pic'] ?>" class="rounded-circle bg-secondary"
                                id="profilePicture" width="150" height="150">
                        <?php else: ?>
                            <img src="<?= $this->Html->url('/img/default.jpg') ?>" class="rounded-circle bg-secondary"
                                id="profilePicture" width="150" height="150">
                        <?php endif; ?>
                    </div>

                    <!-- Upload Profile Picture Input -->
                    <?= $this->Form->input('profile_pic', [
                        'label' => 'Upload Profile Picture:',
                        'type' => 'file',
                        'class' => 'form-control-file',
                        'required' => true,
                        'accept' => '.jpg,.jpeg,.gif,.png',
                        'onchange' => 'previewFile(event)'
                    ]); ?>

                    <!-- Name Field -->
                    <?= $this->Form->input('name', [
                        'label' => 'Name:',
                        'class' => 'form-control',
                        'value' => isset($user['User']['name']) ? h($user['User']['name']) : '',
                        'required' => true
                    ]); ?>

                    <!-- Birthdate Field -->
                    <?= $this->Form->input('birthdate', [
                        'label' => 'Birthdate:',
                        'type' => 'text',
                        'class' => 'form-control',
                        'value' => isset($user['User']['birthdate']) ? h($user['User']['birthdate']) : '',
                        'required' => true
                    ]); ?>

                    <!-- Gender Field -->
                    <div class="form-group">
                        <label>Gender:</label><br>
                        <?= $this->Form->radio(
                            'gender',
                            ['male' => 'Male', 'female' => 'Female'],
                            ['legend' => false, 'value' => isset($user['User']['gender']) ? $user['User']['gender'] : null]
                        ) ?>
                    </div>

                    <!-- Hobbies Field -->
                    <?= $this->Form->input('hobby', [
                        'label' => 'Hobbies:',
                        'type' => 'text',
                        'class' => 'form-control',
                        'required' => true,
                        'value' => isset($user['User']['hobbies']) ? h($user['User']['hobbies']) : ''
                    ]); ?>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <?= $this->Form->button('Update', ['type' => 'submit', 'class' => 'btn btn-primary btn-block']) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // jQuery date picker for birthdate
            $('#birthdate').datepicker({
                dateFormat: 'yy-mm-dd'
            });

            // Function to preview selected file
            function previewFile(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = function () {
                    $('#profilePicture').attr('src', reader.result);
                };
                reader.readAsDataURL(file);
            }

            // Trigger file input change event
            $('#profile_pic').change(function (event) {
                previewFile(event);
            });

            // Form submission validation (optional)
            $('#profileForm').submit(function (e) {
                if ($('#profile_pic').val() && $.inArray($('#profile_pic')[0].files[0].type, ["image/jpeg", "image/png", "image/gif"]) < 0) {
                    e.preventDefault();
                    $('#error-messages').text('Please upload a valid image file (jpg, png, gif).').show();
                }
            });
        });
    </script>
</body>

</html>