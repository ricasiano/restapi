<?php
/**loading of class for core use and user-defined libraries/functions
 * 
 * @return object
 */
function invoker() {
    $main = new \RESTAPI\Loader();
    $main->errorhandling = new \RESTAPI\libraries\Errorhandling();
    $main->response_generator = new \RESTAPI\libraries\Response_generator();
    return $main;
}