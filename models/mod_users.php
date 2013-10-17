<?php
// sample construct
class Mod_users extends \RESTAPI\Model {
    public function __construct() {
        //the default fields to get if client has not provided specific fields
        $this->fields = 'id, username';
        //don't show this fields
        $this->blacklist_fields = array('password', 'last_update');
        //for limiting and pagination of results
        $this->limit = DATABASE_DEFAULT_LIMIT;
        $this->max = DATABASE_MAX;
        $this->start = 0;
    }
    
    public function get_users($id = '') {
        //override client-defined limit if it reached max
        if ($this->limit > $this->max)
        $this->limit = $this->max;
        
        //execute mysql query, sorry no abstraction here :(
        if ($id == '')
        return $this->query("select ".$this->fields." from users ORDER BY `username` LIMIT ".$this->start.", ".$this->limit);
        else
        return $this->query("select ".$this->fields." from users where id = '$id' and active=1 and deleted=0");
    }
}
