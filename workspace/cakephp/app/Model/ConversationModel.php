<?php

App::uses('AppModel', 'Model');

class ConversationModel extends AppModel {
    
    public $name = 'Conversation';
    public $useTable = 'conversations';

    public $validate = array(
        'user1_id' => array(
            'rule' => 'notEmpty',
            'message'=> 'User 1 is required'
        ),
        'user2_id' => array(
            'rule' => 'notEmpty',
            'message'=> 'User 2 is required'
        ),
    );

    public $hasMany = array(
        'Message' => array(
            'className' => 'Message',
            'foreignKey' => 'conversation_id',
            'dependent' => true,
        )
    );

    public $belongsTo = array(
        'User1' => array(
            'className' => 'User',
            'foreignKey' => 'user1_id'
        ),
        'User2' => array(
            'className' => 'User',
            'foreignKey' => 'user2_id'
        ),
    );

    public $autoTimeStamp = 'modified';
}