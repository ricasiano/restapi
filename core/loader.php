<?php
namespace RESTAPI;
/**
 * Loader Class
 * 
 * invokes/loads the appropriate libraries, models and core functions
 */
class Loader {

    /**
     * calls the libraries triggered on the controller
     * 
     * @param mixed $libraries
     */
    public function library($libraries) {
        $this->check_resource_type($libraries, 'libraries');
    }
    
    /**
     * calls the core libraries
     * 
     * @param mixed $libraries
     */
    public function core($core) {
        $this->check_resource_type($core, 'core', TRUE);
    }

    /**
     * this invokes data models aka queries. Sorry no abstraction in here :(
     * 
     * @param mixed $models
     */
    public function model($models) {
        $this->check_resource_type($models, 'models');
    }

    /**
     * check datatype first then invoke the resource library
     * 
     * @param mixed $resources
     * @param string $directory
     */
    private function check_resource_type($resources, $directory, $is_core = FALSE) {
         if (is_array($resources)) {
            foreach ($resources as $resource) {
                $loaded = $this->load_resource($resource, $directory, $is_core);
                $this->$resource = $loaded;
            }
        }
        else {
            $loaded = $this->load_resource($resources, $directory, $is_core);
            $this->$resources = $loaded;
        }
    }
    
    /**
     * loads the resource and assign it to a new object that can be accessed on the controller
     * 
     * @param type $library
     * @param type $directory
     * @return \classname
     */
    private function load_resource($library, $directory, $is_core = FALSE) {
        $loaded = (object) '';
        if(is_file(BASEPATH.$directory.'/'.$library.'.php')) {
            require_once(BASEPATH.$directory.'/'.$library.'.php');
            if ($is_core === TRUE) {
                $libname = ucfirst($library);
                $classname = "\\RESTAPI\\libraries\\$libname";
            }
            else
            $classname = ucfirst($library);
            $loaded = new $classname();
        }
        else {
            $this->errorhandling(9004);
        }
        return $loaded;
    }
    
    public function errorhandling($code) {
        $errorhandling = new \RESTAPI\libraries\Errorhandling();
        $errorhandling->generate_error($code);
    }
    
    public function response_generator($data, $resource = '', $id = null) {
        $response_generator = new \RESTAPI\libraries\Response_generator();
        $response_generator->generate($data, $resource, $id);
    }
}