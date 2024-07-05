<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController
{

    public $uses = array('Message', 'Conversation', 'User');

    public $components = array(
        'DebugKit.Toolbar',
        'Paginator',
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

    public function index()
    {
        $userId = $this->Auth->user('id');
        if (!$userId) {
            return $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
    }

    public function compose()
    {
        // if (!$this->Session->check('RegistrationCompleted')) {
        //     return $this->redirect(array('controller' => 'users', 'action' => 'complete_profile'));
        // }
        if ($this->request->is('post')) {
            $userId = $this->Auth->user('id');
            $recipientId = $this->request->data['Message']['recipient_id'];

            // Check if a conversation already exists
            $conversation = $this->Conversation->find(
                'first',
                array(
                    'conditions' => array(
                        'OR' => array(
                            array(
                                'Conversation.user1_id' => $userId,
                                'Conversation.user2_id' => $recipientId
                            ),
                            array(
                                'Conversation.user1_id' => $recipientId,
                                'Conversation.user2_id' => $userId
                            )
                        )
                    )
                )
            );

            // If no conversation exists, create a new one
            if (empty($conversation)) {
                $this->Conversation->create();
                $this->Conversation->save(
                    array(
                        'user1_id' => $userId,
                        'user2_id' => $recipientId
                    )
                );
                $conversationId = $this->Conversation->id;
            } else {
                $conversationId = $conversation['Conversation']['id'];
            }

            // Save the message
            $this->Message->create();
            if (
                $this->Message->save(
                    array(
                        'conversation_id' => $conversationId,
                        'sender_id' => $userId,
                        'recipient_id' => $recipientId,
                        'message' => $this->request->data['Message']['message']
                    )
                )
            ) {
                $this->Conversation->id = $conversationId;
                $this->Conversation->saveField('modified', date('Y-m-d H:i:s'));

                $this->set('message', 'Message sent successfully.');
                return $this->redirect(array('controller' => 'conversations', 'action' => 'index'));
            } else {
                $this->Flash->error('Failed to send the message. Please try again.');
            }
        }
    }

    public function view($conversationId = null)
    {
        $userId = $this->Auth->user('id');
        $user = $this->User->findById($userId);

        // Check if $conversationId is provided
        if (!$conversationId) {
            throw new NotFoundException(__('Invalid Conversation'));
        }

        // Fetch conversation details, sender, recipient, and latest message
        $conversation = $this->Conversation->find(
            'first',
            array(
                'conditions' => array(
                    'Conversation.id' => $conversationId
                ),
                'fields' => array(
                    'Conversation.id',
                    'User1.name AS sender_name',
                    'User1.id AS sender_id',
                    'User1.profile_pic AS sender_profile_pic', // Include sender's profile pic
                    'User2.name AS recipient_name',
                    'User2.id AS recipient_id',
                    'User2.profile_pic AS recipient_profile_pic', // Include recipient's profile pic
                    'MAX(Message.sent_at) AS latest_message_time',
                    'SUBSTRING(MAX(Message.message), 1, 50) AS latest_message' // Adjust length as needed
                ),
                'joins' => array(
                    array(
                        'table' => 'messages',
                        'alias' => 'Message',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'Message.conversation_id = Conversation.id'
                        )
                    ),
                    array(
                        'table' => 'users',
                        'alias' => 'User1',
                        'type' => 'INNER',
                        'conditions' => array(
                            'User1.id = Conversation.user1_id'
                        )
                    ),
                    array(
                        'table' => 'users',
                        'alias' => 'User2',
                        'type' => 'INNER',
                        'conditions' => array(
                            'User2.id = Conversation.user2_id'
                        )
                    )
                ),
                'group' => 'Conversation.id'
            )
        );

        // Check if conversation exists
        if (!$conversation) {
            throw new NotFoundException(__('Conversation not found'));
        }

        // Fetch messages with pagination
        $this->Paginator->settings = array(
            'conditions' => array(
                'Message.conversation_id' => $conversationId
            ),
            'order' => 'Message.sent_at DESC',
            'limit' => 10, // Adjust the limit as per your requirement
            'page' => 1, // Default to first page
        );
        $messages = $this->Paginator->paginate('Message');

        // Pass data to the view
        $this->set('conversation', $conversation);
        $this->set('messages', $messages);
        $this->set('userId', $userId);
        $this->set('user', $user);
    }

    public function reply($conversationId = null)
    {
        // disable rendering of view
        $this->autoRender = false;

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException('Invalid request method');
        }

        // Assuming you have a Message model
        $this->loadModel('Message');

        // Assuming you have a Conversation model to retrieve the conversation details
        $this->loadModel('Conversation');

        // Retrieve logged-in user's ID
        $userId = $this->Auth->user('id');

        // Retrieve the conversation based on conversationId and userId
        $conversation = $this->Conversation->find(
            'first',
            array(
                'conditions' => array(
                    'Conversation.id' => $conversationId,
                    'OR' => array(
                        'Conversation.user1_id' => $userId,
                        'Conversation.user2_id' => $userId
                    )
                )
            )
        );

        if (!$conversation) {
            throw new NotFoundException('Conversation not found');
        }

        // Retrieve message content from POST data
        $messageContent = $this->request->data['message'];
        $recipientId = ($conversation['Conversation']['user2_id']);

        // Prepare the message data to be saved
        $messageData = array(
            'Message' => array(
                'conversation_id' => $conversationId,
                'sender_id' => $userId,
                'recipient_id' => $recipientId,
                'message' => $messageContent,
                'sent_at' => date('Y-m-d H:i:s')
            )
        );

        // Save the message
        if ($this->Message->save($messageData)) {
            // Optionally, you can handle success response here
            $this->set('success', true);
            $this->set('_serialize', array('success'));
            $this->Conversation->id = $conversationId;
            $this->Conversation->saveField('modified', date('Y-m-d H:i:s'));
        } else {
            // Handle error if saving fails
            $this->set('success', false);
            $this->set('_serialize', array('success'));
        }
    }

    public function delete($messageId)
    {
        if ($this->request->is('ajax')) {
            // Assume Message model is loaded
            $this->Message->id = $messageId;
            if ($this->Message->exists()) {
                if ($this->Message->delete()) {
                    $this->autoRender = false;
                    $this->response->statusCode(200);
                    echo json_encode(['success' => true]);
                } else {
                    $this->response->statusCode(500);
                    echo json_encode(['error' => 'Failed to delete message.']);
                }
            } else {
                $this->response->statusCode(404);
                echo json_encode(['error' => 'Message not found.']);
            }
        } else {
            $this->response->statusCode(405);
            echo json_encode(['error' => 'Method Not Allowed']);
        }
        return $this->response;
    }

}
?>