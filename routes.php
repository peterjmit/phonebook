<?php

$routes = array(
    'home' => array(
        'path' => '/',
        'defaults' => array(
            'redirect' => '/index.html'
        )
    ),
    'contacts' => array(
        'path' => '/contacts',
        'defaults' => array(
            'service' => 'contact_controller',
        ),
        'methods' => array('GET', 'POST'),
    ),
    'contact' => array(
        'path' => '/contacts/{id}',
        'defaults' => array(
            'service' => 'contact_controller',
        ),
        'methods' => array('GET', 'PUT', 'DELETE')
    ),
    'catch_all' => array(
        'path' => '/{all}',
        'defaults' => array(
            'redirect' => '/index.html'
        )
    ),
);