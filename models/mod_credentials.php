<?php

class Mod_credentials extends \RESTAPI\Model {
    public function authorize($user_id, $session) {
        return $this->query("select date_updated from sessions WHERE `user_id` = '$user_id' AND `session` like UNHEX('$session')");
    }
}
