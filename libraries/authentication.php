<?php

/**
 * Authentication class
 * 
 * checks whether the user is allowed to access using current authorization parameters
 * 
 * 
 */
class Authentication {

    //sentry cannon authorization :3 based on headers sent
    public function sentry() {
        $this->API = invoker();
        //get access parameters from authorization headers
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $client = explode('-', $headers['Authorization']);
            if (!isset($client[0]) || !isset($client[1]))
            $this->API->errorhandling->generate_error(403);
            else
            $this->check_session($client);
        }
        else
        $this->API->errorhandling->generate_error(403);
    }

    //validate if session exists or not yet expired for the client
    private function check_session($client) {
        $this->API->library('form');
        $this->API->model('mod_credentials');
        $validate = array('user_id' => $client[0], 'session' => $client[1]);
        $validate_obj = (object) $validate;
        $this->API->form->validate('authorization', $validate_obj);
        //check in db if session exists
        $result = $this->API->mod_credentials->authorize($client[0], $client[1]);
        if (!isset($result[0]['date_updated']))
            $this->API->errorhandling->generate_error(403);
        else {
            //expiration check
            $expiry_date = date('Y-m-d H:i:s', strtotime($result[0]['date_updated'] . ' + ' . EXPIRE_ACCESS_HOURS . ' hours'));
            if ($expiry_date < date('Y-m-d H:i:s'))
                $this->API->errorhandling->generate_error(9006);
        }
    }

    public function digest() {
        $API = invoker();
        $realm = AUTH_REALM;

        //user => password
        $users = array('admin' => 'mypass', 'guest' => 'guest');

        if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
            header('WWW-Authenticate: Digest realm="' . $realm .
                    '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
            $API->errorhandling->generate_error(401);
        }
        // analyze the PHP_AUTH_DIGEST variable
        if (!($data = $this->http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
            !isset($users[$data['username']])) {
                header('WWW-Authenticate: Digest realm="' . $realm .
                    '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
                    $API->errorhandling->generate_error(401);
            }


        // generate the valid response
        $A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
        $A2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
        $valid_response = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);

        if ($data['response'] != $valid_response) {
            header('WWW-Authenticate: Digest realm="' . $realm .
                    '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
            $API->errorhandling->generate_error(401);
            
        }

        // ok, valid username & password
        echo 'You are logged in as: ' . $data['username'];
    }

    private function http_digest_parse($txt) {
        // function to parse the http auth header 
        // protect against missing data
        $needed_parts = array('nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1);
        $data = array();
        $keys = implode('|', array_keys($needed_parts));
        $matches = array();
        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }
        return $needed_parts ? false : $data;
    }
}