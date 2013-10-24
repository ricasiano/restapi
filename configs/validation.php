<?php
/**
 * Form Validation config
 * 
 * format:
 *     'module_1' =>
 *         'field_1' =>
 *             'type' => 'numeric / alphanumeric',
 *             'required' => TRUE / FALSE,
 *             'min' => 'minimum string length',
 *             'max' => 'maximum string length',
 *         'field_2' =>
 *             'type' => 'numeric / alphanumeric',
 *             'required' => TRUE / FALSE,
 *             'min' => 'minimum string length',
 *             'max' => 'maximum string length',
 *     'module_2' =>
 *         'field_1' =>
 *             'type' => 'numeric / alphanumeric',
 *             'required' => TRUE / FALSE,
 *             'min' => 'minimum string length',
 *             'max' => 'maximum string length',
 */
$form_rules = array(
    'authorization' => array(
        'user_id' => array(
            'type' => 'numeric',
            'required' => TRUE,
            'min' => 1,
            'max' => 10,
        ),
        'token' => array(
            'type' => 'alphanumeric',
            'required' => TRUE,
            'min' => 80,
            'max' => 80,
        ),
    ),
    'standard' => array(
        'limit' => array(
            'type' => 'numeric',
            'required' => FALSE,
            'min' => 1,
            'max' => 10,
        ),
        'offset' => array(
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