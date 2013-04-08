<?php

$routes = array(
    'contacts' => array(
        'path' => '/contacts',
        'defaults' => array(
            'service' => 'contact_controller',
        ),
        'methods' => array('GET'),
    ),
    'contact' => array(
        'path' => '/contacts/{id}',
        'defaults' => array(
            'service' => 'contact_controller',
        ),
        'methods' => array('GET', 'POST', 'PUT', 'DELETE')
    )
);