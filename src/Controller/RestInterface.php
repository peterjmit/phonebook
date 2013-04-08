<?php

namespace Controller;

interface RestInterface
{
    const GET = 'get';
    const POST = 'create';
    const PUT = 'update';
    const DELETE = 'delete';

    public function get();

    public function create();

    public function update();

    public function delete();
}