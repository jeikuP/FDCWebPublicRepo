<!DOCTYPE html>
<html>
<head>
    <title>Thank You</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h2>Thank You for Registering!</h2>
            <p>Your registration was successful. Please login to complete your profile.</p>
            <?php echo $this->Html->link('Back to Homepage', ['controller' => 'users', 'action' => 'complete_profile'], ['class' => 'btn btn-primary']); ?>
        </div>
    </div>
</div>
</body>
</html>

