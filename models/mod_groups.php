<?php

class Mod_groups extends \RESTAPI\Model {
    public function __construct() {

    }
    public function get_groups($id) {
        if ($id == 1)
        $result = 'datagroups';
        else
        $result = 'errorgroups';
        return $result;
    }
}
