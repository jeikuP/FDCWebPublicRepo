<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component');

class User extends AppModel {

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A name is required'
            ),
            'length' => array(
                'rule' => array('between', 5, 20),
                'message' => 'Name must be between 5 and 20 characters'
            )
        ),
        'email' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'An email is required'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This email is already registered'
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Please provide a valid email address'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A password is required'
            ),
            'length' => array(
                'rule' => array('minLength', 6),
                'message' => 'Password must be at least 6 characters long'
            )
        ),
        'password_confirm' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Please confirm your password'
            ),
            'compare' => array(
                'rule' => array('compareFields', 'password'),
                'message' => 'Passwords do not match'
            )
        )
    );

    public $hasMany = array(
        'Conversation1' => array(
            'className' => 'Conversation',
            'foreignKey' => 'user1_id'
        ),
        'Conversation2' => array(
            'className' => 'Conversation',
            'foreignKey' => 'user2_id',
        )
    );

    public function compareFields($check, $field) {
        return $this->data[$this->alias][$field] === array_values($check)[0];
    }

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }
}
?>