<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;

interface RouterInterface extends CollectionInterface
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
    public function match(Request $request);
}