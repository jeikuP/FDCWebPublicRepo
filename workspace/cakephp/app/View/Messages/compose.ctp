<!-- app/View/Conversations/compose.ctp -->

<!DOCTYPE html>
<html>

<head>
    <title>Compose New Message</title>
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
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex">
                <!-- Back Button -->
                <a href="javascript:history.back()" class="btn btn-secondary mr-3"><i class="bi bi-arrow-left"></i></a>
                <h2 class="mb-0">Compose New Message</h2>
            </div>
            <div class="d-flex">
                <?php echo $this->element('user_info'); ?>
                <?php echo $this->element('dropdown_menu'); ?>
            </div>
        </div>

        <!-- Form to compose a new message -->
        <?php echo $this->Form->create('Message', array('url' => array('controller' => 'messages', 'action' => 'compose'))); ?>
        <div class="form-group mt-3">
            <label for="recipient">To:</label>
            <?php echo $this->Form->input('recipient_id', array('type' => 'select', 'label' => false, 'class' => 'form-control', 'id' => 'recipient', 'empty' => 'Select a recipient', 'required' => true)); ?>
        </div>

        <div class="form-group">
            <label for="message">Message:</label>
            <?php echo $this->Form->textarea('message', array('class' => 'form-control', 'rows' => '5', 'id' => 'message')); ?>
            <div class="invalid-feedback">
                Please enter a message.
            </div>
        </div>

        <div class="form-group">
            <?php echo $this->Form->button('Send', array('class' => 'btn btn-primary')); ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 on the recipient input
            $('#recipient').select2({
                placeholder: 'Search for a recipient',
                ajax: {
                    url: '<?php echo htmlspecialchars($this->Html->url(array('controller' => 'users', 'action' => 'search')), ENT_QUOTES); ?>',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        console.log(data);
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Client-side form validation
            $('#message').on('input', function () {
                var message = $(this).val().trim();
                if (message.length > 0) {
                    $(this).removeClass('is-invalid');
                } else {
                    $(this).addClass('is-invalid');
                }
            });

            $('form').on('submit', function (event) {
                var message = $('#message').val().trim();
                if (message.length === 0) {
                    $('#message').addClass('is-invalid');
                    event.preventDefault();
                }
            });
        });
    </script>
</body>

</html>
