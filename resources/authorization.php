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
        switch(AUTH_TYPE) {
            case 'BASIC':
                $this->authentication->basic();
                break;
            case 'DIGEST':
                $this->authentication->digest();
                break;
            case 'OAUTH':
                break;
            case 'SIGNED_REQUEST':
                break;
            default:
                echo 'Invalid AUTH_TYPE. See defaults.php config file.';
                exit;
                break;
        }
    }
    
    
    public function logout() {
        echo 'logged out';
    }
    
    public function deactivate() {
        echo 'deactivated';
    }
}