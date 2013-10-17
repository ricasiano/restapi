<?php

namespace RESTAPI;

/**
 * Checking and invocation of the resource requested by user
 * 
 * @param object $errorhandling
 */
class Resource {

    public $errorhandling;

    public function __construct($errorhandling) {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->action = '';
        $this->errorhandling = $errorhandling;
    }

    /**
     * calls the resource triggers by the client
     * 
     * @param array $resource
     * @param string $path
     * @param array $formdata
     */
    public function invoke_resource($path, $formdata) {
        if (!isset($_SERVER['PATH_INFO']))
            $this->errorhandling->generate_error(9001);
        $resource = explode('/', substr($_SERVER['PATH_INFO'], 1));
        $this->check_resource($resource[0]);
        $this->clean_resource($resource);
        $uc_resource = ucfirst($resource[0]);
        $class_resource = new $uc_resource();

        if (method_exists($class_resource, $path) === TRUE) {
            $class_resource->formdata = $formdata;
            //pass all segments as parameters
            array_shift($resource);
            if (is_array($resource))
                call_user_func_array(array($class_resource, $path), $resource);
            else
                $class_resource->$path();
        }
        else
            $this->errorhandling->generate_error(9003);
    }

    /**
     * Safecheck for required PHP core libraries and functions
     * 
     * @param string $type
     * @param array $functions
     */
    public function check_core_functions($type, $functions = array()) {
        foreach ($functions as $core_function) {
            if ($type($core_function) === FALSE)
                $this->errorhandling->generate_error(9002);
        }
    }

    /**
     * check whether resource requested is valid and existing
     * 
     * @param string $resource
     */
    private function check_resource($resource) {
        if (is_file(BASEPATH . 'resources/' . $resource . '.php'))
            require(BASEPATH . 'resources/' . $resource . '.php');
        else
            $this->errorhandling->generate_error(9001);
    }

    /**
     * Safecheck for resource string validation, disallow access for non-alphanumeric string
     * 
     * @param array $resource
     */
    private function clean_resource($resource) {
        foreach ($resource as $cleanresource) {
            if (preg_match('/[^a-z_\-0-9]/i', $cleanresource))
                $this->errorhandling->generate_error(9005);
        }
    }

    /**
     * run through POST and GET to clean the string and re-assign to a new property
     * 
     * @return array
     */
    public function sanitize() {
        $request_data = array();
        if (is_array($_POST) && count($_POST) > 0)
            $request_data = $this->convert_data($_POST);
        else if (is_array($_GET) && count($_GET) > 0)
            $request_data = $this->convert_data($_GET);
        unset($_POST);
        unset($_GET);
        return $request_data;
    }

    /**
     * converts all post/get data to object
     * 
     * @param array $raw_data
     * @return object
     */
    private function convert_data($raw_data = array()) {
        $request_data = (object) '';
        foreach ($raw_data as $var => $val) {
            $val = $this->clean_input($val);
            $request_data->$var = $val;
        }
        return $request_data;
    }

    /**
     * actual cleaning of data
     * 
     * @param string $input
     * @return string
     */
    private function clean_input($input) {
        $search = array(
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );
        $output = mysql_real_escape_string(preg_replace($search, '', $input));
        return $output;
    }

    /**
     * assigns the format to be used as well as the response code if it will be included in the headers
     * 
     * @param object $data
     */
    public function assign_response_format($data) {
        if (isset($data->response_format))
            define('RESPONSE_FORMAT', $data->response_format);
        else
            define('RESPONSE_FORMAT', 'json');
        if (isset($data->suppress_response_codes) == 'true')
            define('SUPPRESS_RESPONSE_CODES', true);
        else
            define('SUPPRESS_RESPONSE_CODES', false);
    }

    /**
     * rerouting to what resource/class to call, based on method and action
     * 
     * @return string
     */
    function route_path() {
        $path = '';
        if ($this->method == 'GET')
            $path = 'get';
        //post, put, delete
        else {
            $resource = explode('/', substr($_SERVER['PATH_INFO'], 1));
            if ($resource[0] == 'authorization') {
                $path = strtolower($resource[1]);
            }
            else {
                //get action via request method
                if ($this->action == '')
                    $path = strtolower($this->method);
                //get action via post parameter
                else
                    $path = $this->action;
            }
        }
        return $path;
    }

}