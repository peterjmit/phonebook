<?php

$services = array(
    // controllers
    'contact_controller' => function ($c) {
        return new Controller\ContactController();
    },

    // database
    'db_connection' => function ($c) {
        return new Database\Connection($c->getParam('database'));
    },
    'contact_manager' => function ($c) {
        return new Model\ContactManager($c->get('db_connection'));
    },
);