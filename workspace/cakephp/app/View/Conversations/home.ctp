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
            <h2>Message List</h2>
            <div class="d-flex">
                <!-- New Message Button -->
                <?php echo $this->Html->link('New Message', array('controller' => 'messages', 'action' => 'compose'), array('class' => 'btn btn-primary ml-3')); ?>
                <?php echo $this->element('dropdown_menu'); ?>
            </div>
        </div>
        <!-- Display Count of Conversations -->
        <div class="mt-3 ml-1">
            <strong>Total Conversations:</strong> <?php echo count($conversations); ?>
        </div>

        <!-- Search Conversations/Messages -->
        <div class="input-group mt-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Search Conversations...">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
            </div>
        </div>

        <!-- List of Conversations -->
        <ul class="list-group mt-3" id="conversationList">
            <?php foreach ($conversations as $conversation): ?>
                <li class="list-group-item d-flex align-items-center">
                    <!-- Profile Picture Placeholder or Icon -->
                    <div class="mr-3">
                        <?php if (empty($conversation['User2']['profile_picture'])): ?>
                            <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px;">
                                <span class="oi oi-person" style="font-size: 20px;"></span>
                            </div>
                        <?php else: ?>
                            <img src="<?php echo h($conversation['User2']['profile_picture']); ?>" class="rounded-circle"
                                width="50" height="50"
                                alt="<?php echo h($conversation['User2']['recipient_name']); ?>'s Profile Picture">
                        <?php endif; ?>
                    </div>

                    <!-- Conversation Details -->
                    <div>
                        <a
                            href="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'view', $conversation['Conversation']['id'])); ?>">
                            <strong>
                                <?php
                                if ($conversation['User2']['recipient'] == $userId) {
                                    echo 'You';
                                } else {
                                    echo isset($conversation['User2']['recipient']) ? h($conversation['User2']['recipient']) : 'User2';
                                }
                                ?>
                            </strong>
                            <div class="text-muted">
                                <?php
                                $maxLen = 135; // Maximum number of characters to display
                                $trimmedText = mb_strimwidth($conversation['LatestMessage']['latest_message'], 0, $maxLen, "...");
                                echo $trimmedText;
                                ?>
                            </div>
                            <small class="text-muted">
                                <?php echo date('M j, Y, H:i', strtotime($conversation['LatestMessage']['latest_message_time'])); ?>
                            </small>
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Handle search button click
            $('#searchButton').click(function () {
                var searchTerm = $('#searchInput').val();
                // Implement your search logic here (AJAX or form submission)
                // Example: Redirect to search results page with the search term
                window.location.href = '<?php echo $this->Html->url(array('controller' => 'conversations', 'action' => 'search')); ?>?term=' + searchTerm;
            });
        });

    </script>
</body>

</html>