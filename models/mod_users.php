<?php

class Mod_users extends \RESTAPI\Model {
    public function __construct() {
        $this->fields = 'id, username, first_name, last_name, nickname, photo, facebook, twitter, instagram';
        $this->blacklist_fields = array('password', 'date_created', 'date_updated', 'active', 'deleted');
        $this->limit = DATABASE_DEFAULT_LIMIT;
        $this->max = DATABASE_MAX;
        $this->start = 0;
    }
    public function get_users($id = '') {
        if ($this->limit > $this->max)
        $this->limit = $this->max;
        if ($id == '')
        return $this->query("select ".$this->fields." from users WHERE active=1 AND deleted=0 ORDER BY `username` LIMIT ".$this->start.", ".$this->limit);
        else
        return $this->query("select ".$this->fields." from users where id = '$id' and active=1 and deleted=0");
    }
}
