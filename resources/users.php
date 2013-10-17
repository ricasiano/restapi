<?php
class Users extends \RESTAPI\Loader implements iRestserver {
    public function __construct() {
        $this->library(array('form', 'client_settings', 'authentication'));
        $this->model(array('mod_users', 'mod_groups'));
        $this->authentication->sentry();
    }
    /**
     * 
     * @param string $id
     */
    public function get($id = null) {        

        $this->form->validate('get_users', $this->formdata);
        $this->client_settings->get_param_settings($this->formdata);
        if(isset($this->formdata->fields))
        $this->mod_users->fields = $this->formdata->fields;
        
        if(isset($this->formdata->limit))
        $this->mod_users->limit = $this->formdata->limit;
        
        if(isset($this->formdata->start))
        $this->mod_users->start = $this->formdata->start;
        
        $data = $this->mod_users->get_users($id);
        if ($data != '')
        $this->response_generator($data, strtolower(__CLASS__));
        else
        $this->errorhandling(404);
    }

    public function post() {
        echo 'Invoked POST';
    }

    public function put($id = '') {
        echo 'Invoked PUT';
    }

    public function delete($id = '') {
        echo 'Invoked DELETE';
    }
}