<?php

$routes = array(
    'contacts' => array(
        'path' => '/contacts$',
        'controller' => 'contact_controller',
        'methods' => array('GET'),
    ),
    'contact' => array(
        'path' => '/contacts/(\d+)$',
        'controller' => 'contact_controller',
        'methods' => array('GET', 'POST', 'PUT', 'DELETE')
    )
);