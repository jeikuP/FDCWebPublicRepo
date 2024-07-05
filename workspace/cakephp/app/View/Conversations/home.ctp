<!-- app/View/Conversations/index.ctp -->
<!DOCTYPE html>
<html>

<head>
    <title>Your Conversations</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/open-iconic@1.1.1/font/css/open-iconic-bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Page Title and Compose Message Button -->
            <div class="d-flex">
                <h2 class="mb-0">Message List</h2>
                <?php echo $this->Html->link('New Message', array('controller' => 'messages', 'action' => 'compose'), array('class' => 'btn btn-primary ml-3')); ?>
            </div>
            <!-- Main Navigation -->
            <div class="d-flex">
                <?php echo $this->element('user_info'); ?>
                <?php echo $this->element('dropdown_menu'); ?>
            </div>
        </div>

        <!-- Search Conversations/Messages -->
        <div class="input-group mt-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Search Conversations...">
        </div>

        <!-- List of Conversations -->
        <?php if (count($conversations) > 0): ?>
            <?php echo $this->element('conversation_list'); ?>
        <?php else: ?>
            <div class="alert alert-info mt-3" role="alert">
                No conversations found.
            </div>
        <?php endif; ?>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#searchInput').on('keyup', function () {
                var searchTerm = $(this).val();
                $.ajax({
                    url: '<?php echo $this->Html->url(array('controller' => 'conversations', 'action' => 'search')); ?>',
                    method: 'GET',
                    data: { term: searchTerm },
                    success: function (response) {
                        $('#conversationList').html(response);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });

        // JavaScript in your view file
        $(document).on('click', '.delete-conversation', function (e) {
            e.preventDefault();
            var conversationId = $(this).data('conversation-id');
            var deleteUrl = "<?php echo $this->Html->url(array('controller' => 'conversations', 'action' => 'delete')); ?>/" + conversationId;

            // Confirm deletion with a modal or other confirmation method if necessary
            if (confirm("Are you sure you want to delete this conversation?")) {
                $.ajax({
                    url: deleteUrl,
                    method: 'POST',
                    success: function (response) {
                        // Handle success response
                        console.log(response);

                        // Optionally remove the conversation from the UI
                        $(`.delete-conversation[data-conversation-id="${conversationId}"]`).closest('li').fadeOut('normal', function () {
                            $(this).remove(); // Ensure complete removal after fadeOut
                        });

                        // Update total conversations count
                        var totalConversations = $('#conversationList > li').length;
                        $('.total-conversations').text(totalConversations);

                    },
                    error: function (xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });

    </script>
</body>

</html>