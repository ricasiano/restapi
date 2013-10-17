<?php

class Mod_authentication extends \RESTAPI\Model {
    public function login($username) {
        return $this->query("SELECT `id`, `username`, `password` 
            FROM users 
            WHERE `username`
            LIKE '$username'");
    }
    
    public function store_token($username, $token) {
        $this->query("UPDATE `users` SET `token` = '$token' WHERE `username` LIKE '$username'");
    }
    
    public function authorize($user_id, $token) {
        return $this->query("select `last_update` from `users` WHERE `id` = '$user_id' AND `token` like '$token'");
    }
    
    
}
