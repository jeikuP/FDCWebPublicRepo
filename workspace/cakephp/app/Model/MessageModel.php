<?php

App::uses('AppModel','Model');

class MessageModel extends AppModel {

    public $name = 'Message';
    public $useTable = 'messages';

    public $validate = array(
        'conversation_id' => array(
            'rule' => 'notEmpty',
            'message'=> 'Conversation ID is required'
        ),
        'sender_id' => array(
            'rule' => 'notEmpty',
            'message'=> 'Sender ID is required'
        ),
        'message' => array(
            'rule' => 'notEmpty',
            'message' => 'Please enter a message'
        )
    );

    public $belongsTo = array(
        'Conversation' => array(
            'className' => 'Conversation',
            'foreignKey' => 'conversation_id',
        ),
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'sender_id'
        ),
        'Recipient' => array(
            'className' => 'User',
            'foreignKey' => 'recipient_id'
        )
    );

    public $autoTimeStamp = 'modified';
}