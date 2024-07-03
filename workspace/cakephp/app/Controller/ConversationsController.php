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
        // if (!$this->Session->check('RegistrationCompleted')) {
        //     return $this->redirect(array('controller' => 'users', 'action' => 'complete_profile'));
        // }
        $userId = $this->Auth->user('id');
        if (!$userId) {
            return $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        

        // Define your raw SQL query
        $sql = "
        SELECT
            `Conversation`.`id` AS id,
            `User1`.`name` AS sender,
            `User1`.`id` AS sender_id,
            `User2`.`name` AS recipient,
            `User2`.`id` AS recipient_id,
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

        
        
  
        CakeLog::debug(json_encode($conversations, JSON_PRETTY_PRINT));
        $this->set('conversations', $conversations);
        $this->set('userId', $userId);
        CakeLog::debug(json_encode($userId, JSON_PRETTY_PRINT));
    }

    


}

?>