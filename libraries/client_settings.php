<?php
//fetches parameter settings based on client's preference
class Client_settings {
    public function __construct() {
        //used on pagination
        $this->start = 0;
        $this->limit = DATABASE_DEFAULT_LIMIT;
    }
    
    public function get_param_settings($formdata) {
        //start data for selecting to DB
        if (isset($formdata->start) && is_int($formdata->start))
        $this->start = $formdata->start;
        //recalibrate limit based on settings
        if (isset($formdata->limit) && is_int($formdata->limit)) {
            if ($formdata->limit > DATABASE_MAX)
            $this->limit = DATABASE_MAX;
            else
            $this->limit = $formdata->limit;
        }
    }
}