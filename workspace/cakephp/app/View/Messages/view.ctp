<!-- app/View/Messages/view.ctp -->

<!DOCTYPE html>
<html>

<head>
    <title>Conversation View</title>
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
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex">
                <a href="javascript:history.back()" class="btn btn-secondary mr-3"><i class="bi bi-arrow-left"></i></a>
                <h2 class="mb-0">Message Details</h2>
            </div>
            <div class="d-flex">
                <?php echo $this->element('dropdown_menu'); ?>
            </div>
        </div>
        <div class="mb-3 mt-3 d-flex align-items-center justify-content-between">
            <h4>Your Conversation with <?php echo h($conversation['User2']['recipient_name']) ?></h4>
        </div>

        <!-- Reply Form -->
        <div id="replyForm">
            <?php echo $this->Form->create('Message', array('url' => array('controller' => 'messages', 'action' => 'reply', $conversation['Conversation']['id']), 'id' => 'replyForm')); ?>
            <div class="form-group">
                <?php echo $this->Form->textarea('message', array('class' => 'form-control', 'id' => 'replyMessage', 'rows' => '3')); ?>
            </div>
            <button id="sendReply" class="btn btn-primary mb-3"><i class="bi bi-reply"></i> Send Reply</button>
            <?php echo $this->Form->end(); ?>
        </div>


        <!-- Display Messages in Chronological Order -->
        <ul class="list-group" id="messageList">
            <?php $messages = array_reverse($messages); // Reverse array to display latest message first ?>
            <?php foreach ($messages as $message): ?>
                <?php
                // Determine alignment based on sender
                $isSender = ($message['Message']['sender_id'] == $userId);
                $alignClass = $isSender ? 'justify-content-end' : 'justify-content-start';
                ?>
                <li
                    class="list-group-item d-flex align-items-start <?php echo $alignClass; ?> <?php echo $isSender ? 'bg-light' : ''; ?>">
                    <?php if (!$isSender): ?>
                        <!-- Profile Picture Placeholder for Recipient -->
                        <div class="mr-3 mt-1">
                            <?php if (empty($conversation['User2']['profile_picture'])): ?>
                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">
                                    <span class="oi oi-person" style="font-size: 16px;"></span>
                                </div>
                            <?php else: ?>
                                <img src="<?php echo h($conversation['User2']['profile_picture']); ?>" class="rounded-circle"
                                    width="40" height="40"
                                    alt="<?php echo h($conversation['User2']['recipient_name']); ?>'s Profile Picture">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="text-justify" style="max-width: 100%;">
                        <div><?php echo nl2br(h($message['Message']['message'])); ?></div>
                        <small
                            class="text-muted <?php echo $isSender ? 'float-right' : ''; ?>"><?php echo date('M j, Y, H:i', strtotime($message['Message']['sent_at'])); ?></small>
                    </div>
                    <?php if ($isSender): ?>
                        <!-- Profile Picture Placeholder for Sender (Logged-in User) -->
                        <div class="ml-3 mt-1">
                            <?php if (empty($conversation['User1']['profile_picture'])): ?>
                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">
                                    <span class="oi oi-person" style="font-size: 16px;"></span>
                                </div>
                            <?php else: ?>
                                <img src="<?php echo h($conversation['User1']['profile_picture']); ?>" class="rounded-circle"
                                    width="40" height="40"
                                    alt="<?php echo h($conversation['User1']['sender_name']); ?>'s Profile Picture">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <!-- Delete Button -->
                    <?php if ($isSender): ?>
                        <button class="btn btn-danger btn-sm ml-2 delete-message"
                            data-message-id="<?php echo $message['Message']['id']; ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    <?php endif; ?>
                </li>
                <div style="margin-bottom: 10px;"></div>
            <?php endforeach; ?>
        </ul>
        <button id="loadMore" class="btn btn-link">Show more</button>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        // AJAX function to send a reply
        // Send Reply via Ajax
        $('#sendReply').click(function (e) {
            e.preventDefault(); // Prevent default form submission

            var message = $('#replyMessage').val();

            // AJAX request
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'reply', $conversation['Conversation']['id'])); ?>',
                method: 'POST',
                data: { message: message },
                success: function (response) {
                    // Handle success response
                    console.log(response);

                    // Assuming you want to prepend the new message to the list
                    $('#messageList').prepend(`
                        <li class="list-group-item d-flex align-items-start <?php echo $alignClass; ?> <?php echo $isSender ? 'bg-light' : ''; ?> mb-2">
                            <div class="<?php echo !$isSender ? 'mr-3' : 'ml-3'; ?> mt-1">
                                <img src="<?php echo !$isSender ? h($conversation['User2']['profile_pic']) : h($conversation['User1']['profile_pic']); ?>" class="rounded-circle"
                                    width="40" height="40"
                                    alt="<?php echo !$isSender ? h($conversation['User2']['recipient_name']) : h($conversation['User1']['sender_name']); ?>'s Profile Picture">
                            </div>
                            <div class="text-justify" style="max-width: 100%;">
                                <div>${message}</div>
                                <small class="text-muted <?php echo $isSender ? 'float-right' : ''; ?>">Just now</small>
                            </div>
                            <?php if ($isSender): ?>
                                    <button class="btn btn-danger btn-sm ml-2 delete-message"
                                        data-message-id="<?php echo $message['Message']['id']; ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                            <?php endif; ?>
                        </li>
                    `);

                    // Clear textarea
                    $('#replyMessage').val('');
                },
                error: function (xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });

    </script>
</body>

</html>