<!-- app/View/Users/view_user.ctp -->

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <!-- Include Open Iconic Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/open-iconic@1.1.1/font/css/open-iconic-bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="d-flex">
                            <a href="javascript:history.back()" class="btn btn-secondary mr-3"><i class="bi bi-arrow-left"></i></a>
                            <h2 class="mb-0">User Profile</h2>
                        </div>
                        <div class="d-flex">
                            <?php echo $this->element('user_info'); ?>
                            <?php echo $this->element('dropdown_menu'); ?>
                        </div>
                    </div>    
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <!-- Profile Picture -->
                            <?php if ($user['User']['profile_pic']): ?>
                                <img src="<?= $this->Html->url('/' . h($user['User']['profile_pic'])) ?>" class="rounded-circle bg-secondary mb-3" width="120" height="120" alt="Profile Picture">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center mb-3" style="width: 120px; height: 120px;">
                                    <span class="oi oi-person" style="font-size: 60px;"></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="<?php echo h($user['User']['name']); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="<?php echo h($user['User']['email']); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="birthdate" class="col-md-4 col-form-label text-md-right">Birthdate</label>
                            <div class="col-md-6">
                                <input id="birthdate" type="text" class="form-control" name="birthdate" value="<?php echo h($user['User']['birthdate']); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">Gender</label>
                            <div class="col-md-6">
                                <input id="gender" type="text" class="form-control" name="gender" value="<?php echo h($user['User']['gender']); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="hobbies" class="col-md-4 col-form-label text-md-right">Hobbies</label>
                            <div class="col-md-6">
                                <input id="hobbies" type="text" class="form-control" name="hobbies" value="<?php echo h($user['User']['hobby']); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
</body>
</html>
