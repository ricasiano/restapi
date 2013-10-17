<?php
//user account management
class Authorization extends \RESTAPI\Loader {
    
    public function __construct() {
        $this->library(array('authentication'));
        $this->model('mod_users');
    }
    
    public function signup() {
        echo 'signed up';
    }
    
    public function login() {
        $this->authentication->digest();
    }
    
    public function logout() {
        echo 'logged out';
    }
    
    public function deactivate() {
        echo 'deactivated';
    }
}