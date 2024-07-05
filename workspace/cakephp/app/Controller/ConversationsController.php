<?php
App::uses('AppController', 'Controller');

// app/Controller/ConversationsController.php
class ConversationsController extends AppController
{
    public $uses = array('Message', 'Conversation', 'User');

    public $components = array(
        'DebugKit.Toolbar',
        'Session',
        'Auth' => array(
            'authError' => 'Please login to access this page',
        )
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->deny(); // Deny access to all actions unless authenticated
    }

    public function home()
    {
        $userId = $this->Auth->user('id');
        $user = $this->User->findById($userId);
        if (!$userId) {
            return $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        if (!$this->Session->check('RegistrationCompleted')) {
            return $this->redirect(array('controller' => 'users', 'action' => 'complete_profile'));
        }

        $sql = "
        SELECT
            `Conversation`.`id` AS id,
            `User1`.`name` AS sender,
            `User1`.`id` AS sender_id,
            `User1`.`profile_pic` AS sender_profile_pic,
            `User2`.`name` AS recipient,
            `User2`.`id` AS recipient_id,
            `User2`.`profile_pic` AS recipient_profile_pic,
            `LatestMessage`.`sent_at` AS latest_message_time,
            `LatestMessage`.`message` AS latest_message
        FROM
            (SELECT DISTINCT `conversation_id` FROM `messages`) AS `UniqueConversations`
        JOIN
            `conversations` AS `Conversation` ON `UniqueConversations`.`conversation_id` = `Conversation`.`id`
        JOIN
            `users` AS `User1` ON `User1`.`id` = `Conversation`.`user1_id`
        JOIN
            `users` AS `User2` ON `User2`.`id` = `Conversation`.`user2_id`
        LEFT JOIN
            `messages` AS `LatestMessage` ON (
                `LatestMessage`.`conversation_id` = `Conversation`.`id`
                AND `LatestMessage`.`sent_at` = (
                    SELECT MAX(`sent_at`)
                    FROM `messages`
                    WHERE `conversation_id` = `Conversation`.`id`
                )
            )
        WHERE
            `User1`.`id` = {$userId} OR `User2`.`id` = {$userId}
        ORDER BY
            `Conversation`.`modified` DESC;
        ";

        $conversations = $this->Conversation->query($sql);


        $this->set('conversations', $conversations);
        $this->set('userId', $userId);
        $this->set('user', $user);
        CakeLog::debug(json_encode($user, JSON_PRETTY_PRINT));
        CakeLog::debug(json_encode($conversations, JSON_PRETTY_PRINT));
        CakeLog::debug(json_encode($userId, JSON_PRETTY_PRINT));
    }

    public function search()
    {
        $this->layout = 'ajax'; // Use AJAX layout (no layout)
        $userId = $this->Auth->user('id');
        $term = $this->request->query('term');

        $sql = "
            SELECT
                `Conversation`.`id` AS id,
                `User1`.`name` AS sender,
                `User1`.`id` AS sender_id,
                `User1`.`profile_pic` AS sender_profile_pic,
                `User2`.`name` AS recipient,
                `User2`.`id` AS recipient_id,
                `User2`.`profile_pic` AS recipient_profile_pic,
                `LatestMessage`.`sent_at` AS latest_message_time,
                `LatestMessage`.`message` AS latest_message
            FROM
                (SELECT DISTINCT `conversation_id` FROM `messages`) AS `UniqueConversations`
            JOIN
                `conversations` AS `Conversation` ON `UniqueConversations`.`conversation_id` = `Conversation`.`id`
            JOIN
                `users` AS `User1` ON `User1`.`id` = `Conversation`.`user1_id`
            JOIN
                `users` AS `User2` ON `User2`.`id` = `Conversation`.`user2_id`
            LEFT JOIN
                `messages` AS `LatestMessage` ON (
                    `LatestMessage`.`conversation_id` = `Conversation`.`id`
                    AND `LatestMessage`.`sent_at` = (
                        SELECT MAX(`sent_at`)
                        FROM `messages`
                        WHERE `conversation_id` = `Conversation`.`id`
                    )
                )
            WHERE
                (`User1`.`id` = {$userId} OR `User2`.`id` = {$userId})
                AND (`User1`.`name` LIKE '%{$term}%' OR `User2`.`name` LIKE '%{$term}%')
            ORDER BY
                `Conversation`.`modified` DESC;
            ";

        $conversations = $this->Conversation->query($sql);

        $this->set('conversations', $conversations);
        $this->set('userId', $userId);

        // Render the partial view
        $this->render('/Elements/conversation_list');
    }

    public function delete($id = null)
    {
        if ($this->request->is('post')) {
            // Debug to ensure action is triggered and ID is correct
            debug("Deleting conversation with ID: $id");

            // Attempt to delete the conversation
            if ($this->Conversation->delete($id)) {
                // Successful deletion
                $this->set('success', true); // Optional: Return success response
            } else {
                // Failed to delete
                $this->set('success', false); // Optional: Return failure response
            }
        }

        // Redirect or return JSON response as appropriate
        if ($this->request->is('ajax')) {
            $this->autoRender = false; // Disable auto rendering for AJAX
            $this->response->type('json');
            echo json_encode(['success' => $this->viewVars['success']]);
            return;
        }

        return $this->redirect($this->referer());
    }

}
?>