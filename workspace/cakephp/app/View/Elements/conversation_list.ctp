<!-- app/View/Elements/conversation_list.ctp -->

<ul class="list-group mt-3" id="conversationList">
    <?php foreach ($conversations as $conversation): ?>
        <li class="list-group-item d-flex align-items-center"
            data-conversation-id="<?= $conversation['Conversation']['id']; ?>">
            <!-- Profile Picture Placeholder or Icon -->
            <div class="mr-3">
                <?php
                if ($conversation['User2']['recipient_id'] == $userId) {
                    $profilePic = $conversation['User1']['sender_profile_pic'];
                } elseif ($conversation['User1']['sender_id'] == $userId) {
                    $profilePic = $conversation['User2']['recipient_profile_pic'];
                } else {
                    $profilePic = null; // No profile picture available
                }
                ?>

                <?php if ($profilePic): ?>
                    <img src="<?= $this->Html->url('/' . $profilePic) ?>" class="rounded-circle bg-secondary" width="50"
                        height="50" id="profilePicture">
                <?php else: ?>
                    <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                        style="width: 50px; height: 50px;">
                        <span class="oi oi-person" style="font-size: 20px;"></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Conversation Details -->
            <div class="flex-grow-1">
                <a
                    href="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'view', $conversation['Conversation']['id'])); ?>">
                    <strong>
                        <?php
                        if ($conversation['User2']['recipient_id'] == $userId) {
                            echo isset($conversation['User1']['sender']) ? h($conversation['User1']['sender']) : 'Sender Name';
                        } else {
                            echo isset($conversation['User2']['recipient']) ? h($conversation['User2']['recipient']) : 'Recipient Name';
                        }
                        ?>
                    </strong>
                    <div class="text-muted">
                        <?php
                        $maxLen = 135; // Maximum number of characters to display
                        $trimmedText = mb_strimwidth($conversation['LatestMessage']['latest_message'], 0, $maxLen, "...");
                        echo htmlspecialchars($trimmedText);
                        ?>
                    </div>
                    <small class="text-muted">
                        <?php echo date('M j, Y, H:i', strtotime($conversation['LatestMessage']['latest_message_time'])); ?>
                    </small>
                </a>
            </div>

            <!-- Trash Icon for Delete -->
            <div class="ml-auto">
                <button class="btn btn-link delete-conversation"
                    data-conversation-id="<?= $conversation['Conversation']['id']; ?>">
                    <i class="oi oi-trash text-danger"></i>
                </button>
            </div>
        </li>
    <?php endforeach; ?>
</ul>