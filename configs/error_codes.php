<?php
$http_error_codes = array(
    400 => array('message' => 'Bad Request'),
    401 => array('message' => 'Unauthorized'),
    403 => array('message' => 'Forbidden'),
    404 => array('message' => 'Not Found'),
);

$codes = array(
    401 => array(
        'message' => 'Authentication failed. Check your credentials.',
        'documentation' => '',
        'http' => 401
    ),
    403 => array(
        'message' => 'You are forbidden to access this resource. Check your authorization headers.',
        'documentation' => '',
        'http' => 403
    ),
    404 => array(
        'message' => 'Resource not found.',
        'documentation' => '',
        'http' => 404
    ),
    405 => array(
        'message' => 'Method not allowed.', 
        'documentation' => '', 
        'http' => 405),
    9001 => array(
        'message' => 'Endpoint not found.', 
        'documentation' => '', 
        'http' => 400),
    9002 => array(
        'message' => 'Core Function/Library trying to initialize is not installed.', 
        'documentation' => '', 
        'http' => 400),
    9003 => array(
        'message' => 'Invalid action triggered.', 
        'documentation' =>'', 
        'http' => 400),
    9004 => array(
        'message' => 'Error loading library.', 
        'documentation' =>'', 
        'http' => 400),
    9005 => array('message' => 
        'Alphanumeric characters and underscore(_) are only accepted in the URL endpoint.', 
        'documentation' =>'', 
        'http' => 400),
    9006 => array('message' => 
        'Your access to this resource has expired since no request was done within '.EXPIRE_ACCESS_HOURS.' hours.', 
        'documentation' =>'', 
        'http' => 403),

    //database-related
    8001 => array('message' =>
        'Cannot connect to database.', 
        'documentation' =>'', 
        'http' => 400),
    8002 => array('message' =>
        'The fields you have provided is inexisting.', 
        'documentation' =>'', 
        'http' => 400),
    8003 => array('message' =>
        'The fields you are requesting are not allowed.', 
        'documentation' =>'', 
        'http' => 400),
    8004 => array('message' =>
        'The record you are trying to access does not exist.', 
        'documentation' =>'', 
        'http' => 404),
);