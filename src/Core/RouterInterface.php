<?php

namespace Core;

interface RouterInterface
{
    /**
     * Match a request object to an action
     *
     * @return array Information configured for the route
     *         e.g. array(
     *                  'controller' => 'test_controller',
     *                  'action' => 'index'
     *              )
     */
    public function match();
}