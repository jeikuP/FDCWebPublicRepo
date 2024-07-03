<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body,html {
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center">Login</h2>

                <?php if ($this->Session->check('Message.auth')): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->Session->flash('auth'); ?>
                    </div>
                <?php endif; ?>

                <?php
                echo $this->Form->create('User', array('id' => 'loginForm', 'url' => array('action' => 'login')));
                echo $this->Form->input('email', array('label' => 'Email', 'class' => 'form-control', 'required' => true));
                echo $this->Form->input('password', array('label' => 'Password', 'class' => 'form-control', 'type' => 'password', 'required' => true)) . '<br>';
                echo $this->Form->button('Login', array('type' => 'submit', 'class' => 'btn btn-primary btn-block'));
                echo $this->Form->end();
                ?>

                <div class="mt-3 text-center">
                    <p>Don't have an account? <a
                            href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'register')); ?>">Register
                            here</a>.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>