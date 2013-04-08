<?php

namespace Controller;

interface RestInterface
{
    const GET = 'get';
    const POST = 'create';
    const PUT = 'update';
    const DELETE = 'delete';

    public function get($id = null);

    public function create();

    public function update($id);

    public function delete($id);
}