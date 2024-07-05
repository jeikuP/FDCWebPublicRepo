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
    <style>
        .message-text {
            display: inline;
        }

        .ellipsis {
            display: none;
        }

        .see-more {
            color: blue;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex">
                <a href="<?php echo $this->Html->url(array('controller' => 'conversations', 'action' => 'home')); ?>"
                    class="btn btn-secondary mr-3"><i class="bi bi-arrow-left"></i></a>
                <h2 class="mb-0">Message Details</h2>
            </div>
            <div class="d-flex">
                <?php echo $this->element('user_info'); ?>
                <?php echo $this->element('dropdown_menu'); ?>
            </div>
        </div>
        <div class="mb-3 mt-3 d-flex align-items-center justify-content-between">
            <h4>Your Conversation with
                <?php
                $recipientId = $conversation['User2']['recipient_id'];
                $senderId = $conversation['User1']['sender_id'];

                if ($recipientId == $userId) {
                    $recipientName = isset($conversation['User1']['sender_name']) ? h($conversation['User1']['sender_name']) : 'Sender Name';
                    $recipientProfileLink = $this->Html->url(array('controller' => 'users', 'action' => 'view_user', $senderId));
                    echo '<a href="' . $recipientProfileLink . '">' . $recipientName . '</a>';
                } else {
                    $recipientName = isset($conversation['User2']['recipient_name']) ? h($conversation['User2']['recipient_name']) : 'Recipient Name';
                    $recipientProfileLink = $this->Html->url(array('controller' => 'users', 'action' => 'view_user', $recipientId));
                    echo '<a href="' . $recipientProfileLink . '">' . $recipientName . '</a>';
                }
                ?>
            </h4>
        </div>

        <!-- Reply Form -->
        <div id="replyForm">
            <?php echo $this->Form->create('Message', array('url' => array('controller' => 'messages', 'action' => 'reply', $conversation['Conversation']['id']), 'id' => 'replyForm')); ?>
            <div class="form-group">
                <?php echo $this->Form->textarea('message', array('class' => 'form-control', 'id' => 'replyMessage', 'rows' => '3', 'required' => true)); ?>
                <div class="invalid-feedback">
                    Please enter a reply message.
                </div>
            </div>
            <button id="sendReply" class="btn btn-primary mb-3"><i class="bi bi-reply"></i> Send Reply</button>
            <?php echo $this->Form->end(); ?>
        </div>

        <!-- Display Messages in Chronological Order -->
        <ul class="list-group" id="messageList">
            <?php foreach ($messages as $message): ?>
                <?php
                // Determine alignment based on sender
                $isSender = ($message['Message']['sender_id'] == $userId);
                $alignClass = $isSender ? 'justify-content-end' : 'justify-content-start';
                // Profile pictures
                $senderProfilePic;
                $recipientProfilePic;

                // if logged in user is recipient
                if ($conversation['User2']['recipient_id'] == $userId) {
                    $senderProfilePic = $conversation['User2']['recipient_profile_pic'];
                    $recipientProfilePic = $conversation['User1']['sender_profile_pic'];
                }
                // if logged in user is sender
                else if ($conversation['User1']['sender_id'] == $userId) {
                    $senderProfilePic = $conversation['User1']['sender_profile_pic'];
                    $recipientProfilePic = $conversation['User2']['recipient_profile_pic'];
                } else {
                    $senderProfilePic = null;
                    $recipientProfilePic = null;
                }
                ?>
                <li class="list-group-item d-flex align-items-center <?php echo $alignClass; ?> <?php echo $isSender ? 'bg-light' : ''; ?>"
                    style="margin-bottom: 10px;">
                    <?php if (!$isSender): ?>
                        <!-- Profile Picture Placeholder for Recipient -->
                        <div class="ml-1 mt-1 mr-2">
                            <?php if ($recipientProfilePic): ?>
                                <img src="<?= $this->Html->url('/' . $recipientProfilePic) ?>" class="rounded-circle bg-secondary"
                                    width="40" height="40" id="profilePicture">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">
                                    <span class="oi oi-person" style="font-size: 20px;"></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="text-justify mr-2" style="max-width: 100%;">
                        <div class="message-container">
                            <?php
                            $maxLength = 120;
                            $messageText = h($message['Message']['message']);
                            if (strlen($messageText) > $maxLength) {
                                $shortMessage = substr($messageText, 0, $maxLength) . '...';
                                echo "<span class='message-text short-message'>{$shortMessage}</span>";
                                echo "<span class='message-text full-message' style='display:none;'>{$messageText}</span>";
                                echo "<button class='see-more' style='border:none; background:none; color:blue; cursor:pointer;'>See more</button>";
                                echo "<button class='see-less' style='display:none; border:none; background:none; color:blue; cursor:pointer;'>See less</button>";
                            } else {
                                echo "<span class='message-text'>{$messageText}</span>";
                            }
                            ?>
                        </div>
                        <small class="text-muted <?php echo $isSender ? 'float-right' : ''; ?>">
                            <?php echo date('M j, Y, H:i', strtotime($message['Message']['sent_at'])); ?>
                        </small>
                    </div>
                    <?php if ($isSender): ?>
                        <!-- Profile Picture Placeholder for Sender (Logged-in User) -->
                        <div class="ml-1 mt-1 mr-2">
                            <?php if ($senderProfilePic): ?>
                                <img src="<?= $this->Html->url('/' . $senderProfilePic) ?>" class="rounded-circle bg-secondary"
                                    width="40" height="40" id="profilePicture">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">
                                    <span class="oi oi-person" style="font-size: 20px;"></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <!-- Delete Button -->
                    <?php if ($isSender): ?>
                        <button class="btn btn-danger btn-sm ml-2 delete-message"
                            data-message-id="<?php echo $message['Message']['id']; ?>"
                            data-url="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'delete', $message['Message']['id'])); ?>">
                            <i class="bi bi-trash" style="vertical-align: middle;"></i>
                        </button>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Pagination controls -->
        <div class="mt-3 d-flex justify-content-center">
            <ul class="pagination">
                <?php if ($this->Paginator->hasPrev()): ?>
                    <li class="page-item">
                        <?php echo $this->Paginator->prev('<span aria-hidden="true">&laquo;</span> Previous', ['escape' => false, 'class' => 'page-link']); ?>
                    </li>
                <?php endif; ?>
                <?php if ($this->Paginator->hasNext()): ?>
                    <li class="page-item">
                        <?php echo $this->Paginator->next('Show more <span aria-hidden="true">&raquo;</span>', ['escape' => false, 'class' => 'page-link']); ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        // Send Reply via Ajax
        $('#sendReply').click(function (e) {
            e.preventDefault(); // Prevent default form submission

            var message = $('#replyMessage').val();

            // Check if message is empty
            if (!message.trim()) {
                $('#replyMessage').addClass('is-invalid');
                return;
            }

            // AJAX request
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'reply', $conversation['Conversation']['id'])); ?>',
                method: 'POST',
                data: { message: message },
                success: function (response) {
                    // Handle success response
                    console.log(response);

                    // Prepare the new message HTML
                    var newMessageHtml = `
                        <li class="list-group-item d-flex justify-content-end mb-2 bg-light" style="display: none;">
                            <div class="d-flex align-items-center">
                                <div class="text-justify mr-2" style="max-width: 100%;">
                                    <div>${message}</div>
                                    <small class="text-muted float-right"><?php echo date('M j, Y, H:i', strtotime($message['Message']['sent_at'])); ?></small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <?php if ($user['User']['profile_pic']): ?>
                                                <img src="<?= $this->Html->url('/' . h($user['User']['profile_pic'])) ?>" class="rounded-circle bg-secondary mr-2" width="40" height="40" alt="<?= h($user['User']['name']) ?>'s Profile Picture">
                                    <?php else: ?>
                                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center mr-2" style="width: 40px; height: 40px;">
                                                    <span class="oi oi-person" style="font-size: 20px;"></span>
                                                </div>
                                    <?php endif; ?>
                                    <button class="btn btn-danger btn-sm ml-2 delete-message" data-message-id="<?= $message['Message']['id']; ?>" data-url="<?= $this->Html->url(array('controller' => 'messages', 'action' => 'delete', $message['Message']['id'])); ?>">
                                        <i class="bi bi-trash" style="vertical-align: middle;"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                    `;

                    // Prepend the new message with animation
                    var $newMessage = $(newMessageHtml).prependTo('#messageList').slideToggle();
                    // Clear textarea
                    $('#replyMessage').val('');
                },
                error: function (xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });

        // Delete Message via Ajax
        $(document).on('click', '.delete-message', function (event) {
            event.preventDefault(); // Prevent default action of the button click

            var messageId = $(this).data('message-id');
            var deleteUrl = $(this).data('url'); // Assuming data-url is set correctly in the HTML

            // Reference to the message list item to be deleted
            var $messageListItem = $(this).closest('li');

            // AJAX request to delete message
            $.ajax({
                url: deleteUrl,
                method: 'POST',
                success: function (response) {
                    // Handle success response
                    console.log(response);

                    // Fade out the message list item
                    $messageListItem.fadeOut(function () {
                        $(this).remove();
                    });
                },
                error: function (xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });


        // Handle "See more" click
        $(document).on('click', '.see-more', function () {
            var $messageContainer = $(this).closest('.message-container');
            $messageContainer.find('.short-message').hide();
            $messageContainer.find('.full-message').show(); // Assuming you have a .full-message class for the full message
            $messageContainer.find('.see-less').show(); // Show "See less" button
            $(this).hide(); // Optionally hide "See more" button
        });

        // Handle "See less" click
        $(document).on('click', '.see-less', function () {
            var $messageContainer = $(this).closest('.message-container');
            $messageContainer.find('.full-message').hide(); // Hide the full message
            $messageContainer.find('.short-message').show(); // Show the shortened message
            $messageContainer.find('.see-more').show(); // Show "See more" button again
            $(this).hide(); // Hide "See less" button
        });

        // Client-side form validation
        $('#replyMessage').on('input', function () {
            var message = $(this).val().trim();
            if (message.length > 0) {
                $(this).removeClass('is-invalid');
            } else {
                $(this).addClass('is-invalid');
            }
        });

        $('#replyForm').on('submit', function (event) {
            var message = $('#replyMessage').val().trim();
            if (message.length === 0) {
                $('#replyMessage').addClass('is-invalid');
                event.preventDefault();
            }
        });
    </script>
</body>

</html>