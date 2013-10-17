<?php
$form_rules = array(
    'authorization' => array(
        'user_id' => array(
            'type' => 'numeric',
            'required' => TRUE,
            'min' => 1,
            'max' => 10,
        ),
        'session' => array(
            'type' => 'alphanumeric',
            'required' => TRUE,
            'min' => 0,
            'max' => 40,
        ),
    ),
    'standard' => array(
        'limit' => array(
            'type' => 'numeric',
            'required' => FALSE,
            'min' => 1,
            'max' => 10,
        ),
        'start' => array(
            'type' => 'numeric',
            'required' => FALSE,
            'min' => 0,
            'max' => 10,
        ),
    ),
    
    'get_users' => array(
        'id' => array(
            'type' => 'numeric',
            'required' => FALSE,
            'min' => 1,
            'max' => 30,
        ),
        'password' => array(
            'type' => 'alphanumeric',
            'required' => FALSE,
            'min' => 1,
            'max' => 50,
        ),
    ),
);