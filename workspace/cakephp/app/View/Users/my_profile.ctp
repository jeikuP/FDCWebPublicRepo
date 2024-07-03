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
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <!-- Back Button -->
                        <a href="javascript:history.back()" class="btn btn-secondary mr-3"><i
                                class="bi bi-arrow-left"></i></a>
                                
                        <h2 class="mb-0">My Profile</h2>
                    </div>
                    <?php echo $this->element('dropdown_menu'); ?>
                </div>

                <!-- Display user details -->
                <div class="row mt-3">
                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <?php if (!empty($user['User']['profile_pic'])): ?>
                            <img src="<?php echo $this->Html->url('/app/webroot/' . $user['User']['profile_pic']); ?>"
                                class="rounded-circle bg-secondary" width="250" height="250" />
                        <?php else: ?>
                            <img src="<?= $this->Html->url('/img/default.jpg') ?>" class="rounded-circle bg-secondary"
                                width="200" height="200">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tr>
                                <th>Name:</th>
                                <td><?= h($user['User']['name']) ?></td>
                            </tr>
                            <tr>
                                <th>Birthdate:</th>
                                <td><?= h(date('F j, Y', strtotime($user['User']['birthdate']))) ?></td>
                            </tr>
                            <tr>
                                <th>Gender:</th>
                                <td><?= h($user['User']['gender']) ?></td>
                            </tr>
                            <tr>
                                <th>Hobbies:</th>
                                <td><?= h($user['User']['hobby']) ?></td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>

</html>