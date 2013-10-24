<?php
//fetches parameter settings based on client's preference
class Client_settings {
    public function __construct() {
        //used on pagination
        $this->offset = 0;
        $this->limit = DATABASE_DEFAULT_LIMIT;
    }
    
    public function get_param_settings($formdata) {
        //offset data for selecting to DB
        if (isset($formdata->offset) && is_int($formdata->offset))
        $this->offset = $formdata->offset;
        //recalibrate limit based on settings
        if (isset($formdata->limit) && is_int($formdata->limit)) {
            if ($formdata->limit > DATABASE_MAX)
            $this->limit = DATABASE_MAX;
            else
            $this->limit = $formdata->limit;
        }
    }
}