<?php

/**
 * Authentication class
 * 
 * checks whether the user is allowed to access using current authorization parameters
 * 
 * 
 */
class Authentication {

    
    public function __construct() {
        $this->API = invoker();
    }
    //sentry cannon authorization :3 based on headers sent
    public function sentry() {
        //get access parameters from authorization headers
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $client = explode(' ', $headers['Authorization']);
            //check if authorization headers are malformed/incomplete
            if (!isset($client[0]) || !isset($client[1]) || !isset($client[2])) {
                $this->digest_header();
                $this->API->errorhandling->generate_error(403);
            }
            else if ($client[0] != AUTH_HEADER_LABEL) {
                $this->digest_header();
                $this->API->errorhandling->generate_error(403);
            }
            else
            $this->check_session($client);
        }
        else {
            $this->digest_header();
            $this->API->errorhandling->generate_error(403);
        }
    }

    //validate if session exists or not yet expired for the client
    private function check_session($client) {
        $this->API->library('form');
        $this->API->model('mod_authentication');
        $validate = array('user_id' => $client[1], 'token' => $client[2]);
        $validate_obj = (object) $validate;
        $this->API->form->validate('authorization', $validate_obj);
        //check in db if session exists
        $result = $this->API->mod_authentication->authorize($client[1], $client[2]);
        if (!isset($result[0]['last_access'])) {
            $this->digest_header();
            $this->API->errorhandling->generate_error(403);
        }
        else {
            //expiration check
            $expiry_date = date('Y-m-d H:i:s', strtotime($result[0]['last_access'] . ' + ' . EXPIRE_ACCESS_HOURS . ' hours'));
            if ($expiry_date < date('Y-m-d H:i:s'))
                $this->API->errorhandling->generate_error(9006);
            else {
                define ('CLIENT_ID', $client[1]);
                $this->API->mod_authentication->update_access($client[1], $client[2]);
            }
        }
    }

    public function basic() {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            $this->basic_header();
            $this->API->errorhandling->generate_error(401);
        } else {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            $auth_password = md5($username.':'.AUTH_REALM.':'.$password);
            $this->API->model('mod_authentication');
            $account = $this->API->mod_authentication->basic_authentication($username, $auth_password);
            if (!isset($account[0]['id'])) {
                $this->basic_header();
                $this->API->errorhandling->generate_error(401);
            }
            else {
                $token = sha1(date('U').$auth_password.rand(0,99)).sha1(date('U').rand(0,50));
                // ok, valid username & password
                $data = array(0 => array(
                    'userId' => $account[0]['id'],
                    'token' => $token));
                $this->API->mod_authentication->store_token($username, $token);
                $this->API->response_generator($data, '', TRUE);
            }
            
        }
    }
    
    private function basic_header() {
        header('WWW-Authenticate: Basic realm="'.AUTH_REALM.'"');
        header('HTTP/1.0 401 Unauthorized');
    }
    
    public function digest() {
        
        if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
            $this->digest_header();
            $this->API->errorhandling->generate_error(401);
        }
        $this->API->model('mod_authentication');
        $digest_data = $this->http_digest_parse($_SERVER['PHP_AUTH_DIGEST']);
        $account = $this->API->mod_authentication->login($digest_data['username']);
        // analyze the PHP_AUTH_DIGEST variable
        if (!($digest_data) || !isset($account[0]['username'])) {
            $this->digest_header();
            $this->API->errorhandling->generate_error(401);
        }
        // generate the valid response
        $A1 = $account[0]['password'];
        $A2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $digest_data['uri']);
        $valid_response = md5($A1 . ':' . $digest_data['nonce'] . ':' . $digest_data['nc'] . ':' . $digest_data['cnonce'] . ':' . $digest_data['qop'] . ':' . $A2);
        if ($digest_data['response'] != $valid_response) {
            $this->digest_header();
            $this->API->errorhandling->generate_error(401);
            
        }
        $token = sha1(date('U').$account[0]['password'].rand(0,99)).sha1(date('U').rand(0,50));
        // ok, valid username & password
        $data = array(0 => array(
            'userId' => $account[0]['id'],
            'token' => $token));
        $this->API->mod_authentication->store_token($account[0]['username'], $token);
        $this->API->response_generator($data, '', TRUE);
    }

    private function digest_header() {
        header('WWW-Authenticate: Digest realm="' . AUTH_REALM .
            '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5(AUTH_REALM) . '"');
    }
    
    private function http_digest_parse($txt) {
        // function to parse the http auth header 
        // protect against missing digest_data
        $needed_parts = array('nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1);
        $digest_data = array();
        $keys = implode('|', array_keys($needed_parts));
        $matches = array();
        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $digest_data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }
        return $needed_parts ? false : $digest_data;
    }
}