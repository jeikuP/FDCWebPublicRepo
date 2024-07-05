<?php
App::uses('AppController', 'Controller');
App::uses('AuthComponent', 'Controller/Component');

class UsersController extends AppController
{

    public $components = array(
        'Session'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('
            register',
            'logout',
            'thank_you',
            'check_email'
        ); // Allow unauthenticated access to register action
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                if ($this->Auth->login()) {
                    $user = $this->Auth->user();
                    $this->User->id = $user['id'];
                    $this->User->saveField('last_login', date('Y-m-d H:i:s'));

                    return $this->redirect($this->Auth->redirectUrl());
                } else {
                    $this->Session->setFlash(__('Invalid username or password'), 'default', array(), 'auth');
                }
            } else {
                $this->Session->setFlash(__('Please correct the errors below.'), 'default', array(), 'auth');
            }
        }
    }

    public function logout()
    {
        $this->Session->destroy();
        return $this->redirect($this->Auth->logout());
    }

    public function register()
    {
        if ($this->request->is('post')) {
            $this->User->create();

            // Encrypt the password before saving
            $this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);

            // Extract email from the request data
            $email = $this->request->data['User']['email'];

            // Check if the email already exists in the database
            $existingUser = $this->User->find(
                'first',
                array(
                    'conditions' => array('User.email' => $email)
                )
            );

            if ($existingUser) {
                $this->Session->setFlash(__('Email already exists. Please use a different email.'), 'default', array('class' => 'alert alert-danger'));
                return $this->redirect(array('action' => 'register')); // Redirect back to the registration page
            }

            // Handle optional fields
            $optionalFields = ['birthdate', 'gender', 'hobby', 'profile_pic'];
            foreach ($optionalFields as $field) {
                if (empty($this->request->data['User'][$field])) {
                    $this->request->data['User'][$field] = null;
                }
            }

            if ($this->User->save($this->request->data)) {
                $this->Session->write('RegistrationCompleted', false);
                return $this->redirect(array('action' => 'thank_you'));
            } else {
                debug($this->User->validationErrors);
                $validationErrors = array();
                foreach ($this->User->validationErrors as $errors) {
                    foreach ($errors as $error) {
                        $validationErrors[] = $error;
                    }
                }
                $this->Session->write('ValidationErrors', $validationErrors);
                $this->Session->setFlash(__('Unable to register. Please, correct the errors and try again.'), 'default', array('class' => 'alert alert-danger'));
            }
        }
    }

    public function thank_you()
    {
        if (!$this->Session->check('RegistrationCompleted')) {
            return $this->redirect(array('controller' => 'users', 'action' => 'complete_profile'));
        }
    }

    public function check_email()
    {
        $this->autoRender = false;
        $this->response->type('json');

        $email = $this->request->query('email');
        $existingUser = $this->User->find(
            'first',
            array(
                'conditions' => array('User.email' => $email)
            )
        );

        $response = array('exists' => !empty($existingUser));
        echo json_encode($response);
    }

    public function complete_profile()
    {
        // Fetch current user details
        $userId = $this->Auth->user('id');
        $user = $this->User->findById($userId);
        // if the user fields are already filled and has no null values
        $requiredFields = ['name', 'birthdate', 'gender', 'hobby'];
        $isFilled = true;

        foreach ($requiredFields as $requiredField) {
            if (empty($user['User'][$requiredField])) {
                $isFilled = false;
                break;
            }
        }
        $this->set('user', $user);

        if ($isFilled) {
            $this->Session->write('RegistrationCompleted', true);
            return $this->redirect(array('controller' => 'conversations', 'action' => 'home'));
        }  
    }

    public function updateProfile()
    {
        if ($this->request->is('post')) {
            $userId = $this->Auth->user('id');
            $data = $this->request->data;

            // Handle profile picture upload
            if (!empty($data['profile_pic']['name'])) {
                $file = $data['profile_pic'];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $validImageTypes = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array(strtolower($ext), $validImageTypes)) {
                    $this->Flash->error('Please upload a valid image file (jpg, png, gif).');
                    $this->redirect(['action' => 'complete_profile']);
                }

                // Move uploaded file to a specific directory
                $uploadPath = WWW_ROOT . 'img' . DS . 'profile_pics' . DS;
                $filename = 'profile_' . $userId . '_' . time() . '.' . $ext;

                if (move_uploaded_file($file['tmp_name'], $uploadPath . $filename)) {
                    // Set the profile picture path in the data array
                    $data['profile_pic'] = 'img/profile_pics/' . $filename;
                } else {
                    $this->Flash->error('Failed to upload profile picture. Please try again.');
                    $this->redirect(['action' => 'complete_profile']);
                }
            } else {
                unset($data['profile_pic']); // Remove profile_pic from data if not provided
            }

            // Update user data
            $this->User->id = $userId;

            if ($this->User->save($data)) {
                $this->redirect(['action' => 'complete_profile']);
            } else {
                $this->Flash->error('Unable to update your profile. Please try again.');
                $this->redirect(['action' => 'complete_profile']);
            }
        } else {
            $this->Flash->error('Invalid request.');
            $this->redirect(['action' => 'complete_profile']);
        }
    }

    public function updateProfileAjax()
    {
        $this->autoRender = false; // We don't render a view here

        if ($this->request->is('ajax')) {
            $userId = $this->Auth->user('id');
            $data = $this->request->data;
            $errors = [];

            // Validate name length
            if (strlen($data['name']) < 5 || strlen($data['name']) > 20) {
                $errors['name'] = 'Name must be between 5 and 20 characters long.';
            }

            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email address.';
            }

            // Validate passwords
            if (!empty($data['password']) && !empty($data['confirm_password'])) {
                if ($data['password'] !== $data['confirm_password']) {
                    $errors['password'] = 'Passwords do not match.';
                } else {
                    $data['password'] = Security::hash($data['password'], 'sha256', true);
                }
            }

            if (empty($errors)) {
                // Update user data
                $user = $this->User->findById($userId);
                if ($user) {
                    // Process profile picture upload
                    if (!empty($this->request->data['profile_pic']['name'])) {
                        $file = $this->request->data['profile_pic'];
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $filename = 'profile_' . $userId . '_' . time() . '.' . $ext;
                        $uploadPath = WWW_ROOT . 'img/profile_pics/';
                        if (move_uploaded_file($file['tmp_name'], $uploadPath . $filename)) {
                            $data['profile_pic'] = 'img/profile_pics/' . $filename;
                        } else {
                            $errors['profile_pic'] = 'Failed to upload profile picture.';
                        }
                    }

                    // Update user data
                    $this->User->id = $userId;
                    if ($this->User->save($data, true, array('name', 'birthdate', 'email', 'password', 'hobby', 'profile_pic'))) {
                        echo json_encode(array('success' => true));
                    } else {
                        $errors['general'] = 'Failed to update profile. Please try again.';
                        echo json_encode(array('success' => false, 'errors' => $errors));
                    }
                } else {
                    $errors['general'] = 'User not found.';
                    echo json_encode(array('success' => false, 'errors' => $errors));
                }
            } else {
                echo json_encode(array('success' => false, 'errors' => $errors));
            }
        } else {
            throw new MethodNotAllowedException();
        }
    }

    public function my_profile()
    {
        $userId = $this->Auth->user('id');
        $this->User->id = $userId;
        $user = $this->User->read();


        $requiredFields = ['name', 'birthdate', 'gender', 'hobby'];
        $isFilled = true;

        foreach ($requiredFields as $requiredField) {
            if (empty($user['User'][$requiredField])) {
                $isFilled = false;
                break;
            }
        }

        if (!$isFilled) {
            return $this->redirect(array('controller' => 'users', 'action' => 'complete_profile'));
        }
        $this->set(compact('user'));
    }

    public function search()
    {
        $this->autoRender = false;

        $term = $this->request->query('term');
        $users = $this->User->find(
            'all',
            array(
                'conditions' => array(
                    'User.name LIKE' => '%' . $term . '%'
                ),
                'fields' => array('id', 'name')
            )
        );

        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'id' => $user['User']['id'],
                'text' => $user['User']['name']
            ];
        }

        $response = json_encode($results);
        $this->response->type('json');
        $this->response->body($response);
    }

    public function view_user($userId) {
        // Assuming you have a User model and you want to fetch user details
        $user = $this->User->findById($userId);

        if (!$user) {
            // Handle case where user is not found
            throw new NotFoundException('User not found');
        }

        // Pass user data to the view
        $this->set(compact('user'));
    }

}
