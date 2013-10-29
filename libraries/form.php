<?php

//Validates the passed fields via $_POST or $_GET
class Form {
    public function __construct() {
        $this->errorhandling = new \RESTAPI\libraries\Errorhandling();
        $form_rules = array();
        require(BASEPATH.'configs/validation.php');
        $this->form_rules = $form_rules;
    }
    
    //run through the form validation config against form post/get data
    public function validate($form_label, $formdata) {
        if(isset($this->form_rules[$form_label])) {
            $rules = $this->form_rules[$form_label];
            foreach ($rules as $field_key => $field_value) 
                @$this->apply_rules($field_value, $formdata->$field_key, $field_key);
        }
    }
    
    //check the config setup, apply rule per field
    private function apply_rules($rules, $data, $field_label) {
        if ($rules['required'] === TRUE)
        $this->var_required($field_label, $data);
        if ($data != '') {
            if (isset($rules['type']))
            $this->var_type($field_label, $data, $rules['type']);
            if (isset($rules['max']))
            $this->var_max($field_label, $data, $rules['max']);
            if (isset($rules['min']))
            $this->var_min($field_label, $data, $rules['min']);
        }
    }
    
    //field is required and not blank
    private function var_required($field_label, $data) {
        if ($data == '')
        $this->errorhandling->custom_error('Field '.$field_label.' is required.', '');
    }
    
    //invokes field type checking
    private function var_type($field_label, $data, $type) {
        if (method_exists(__CLASS__, $type))
        $this->$type($field_label, $data);
        else
        $this->errorhandling->custom_error('Invalid type '.$type.' in config file.', '');
    }
    
    //checks if field is numeric
    private function numeric($label, $data) {
        if(!is_numeric($data))
        $this->errorhandling->custom_error('Field '.$label.' is not of numeric type.', '');
    }
    
    //checks if field is alphanumeric
    private function alphanumeric($label, $data) {
        if(ctype_alnum($data) === FALSE)
        $this->custom_error('Field '.$label.' is not of alphanumeric type.', '');
    }
    
    //checks if field is alphabetic
    private function alphabetic($label, $data) {
        if(ctype_alpha($data) === FALSE)
        $this->errorhandling->custom_error('Field '.$label.' is not of alphabetic type.', '');
    }
    
    //check if string passed exceeds max length
    private function var_max($field_label, $data, $max) {
        if(strlen($data) > $max)
        $this->errorhandling->custom_error('Field '.$field_label.' exceeds maximum string length.', '');
    }
    
    //check if string passed exceeds min length
    private function var_min($field_label, $data, $min) {
        if(strlen($data) < $min)
        $this->errorhandling->custom_error('Field '.$field_label.' exceeds minimum string length.', '');
    }
}