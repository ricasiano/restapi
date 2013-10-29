<?php
/******************************************************
  *
  * REST SERVER v0.1
  *
*******************************************************/

//check first PHP version for compatibility
if (version_compare(phpversion(), '5.3.0', '<')) {
   echo 'PHP Version required is 5.3.0 or greater';
   exit;
}
$core = array();
require_once('configs/default.php');
require_once(BASEPATH.'configs/database.php');
//recurse through and call all core files
$core_files = glob(BASEPATH.'core/*.php');
$library_files = glob(BASEPATH.'core/libraries/*.php');
$files = array_merge($core_files, $library_files);
foreach ($files as $file)
require_once($file);

$errorhandling = new \RESTAPI\libraries\Errorhandling();
$restapi = new \RESTAPI\Resource($errorhandling);

//clean first the input strings, this will disallow use of global vars $_POST & $_GET
$formdata = $restapi->sanitize();

//get the default response formats requested by client
$restapi->assign_response_format($formdata);

$restapi->check_core_functions('function_exists', $core['functions']);
$restapi->check_core_functions('class_exists', $core['classes']);

//route what action to take based on HTTP Method and action
if (isset($formdata->action))
$restapi->action = $formdata->action;
$path = $restapi->route_path();
$restapi->invoke_resource($path, $formdata);
