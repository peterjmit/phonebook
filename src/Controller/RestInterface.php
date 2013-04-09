<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Request;

interface RestInterface
{
    const GET = 'get';
    const POST = 'create';
    const PUT = 'update';
    const DELETE = 'delete';

    public function get(Request $request, $id = null);

    public function create(Request $request);

    public function update(Request $request, $id);

    public function delete(Request $request, $id);
}