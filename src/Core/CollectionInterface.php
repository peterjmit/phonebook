<?php

namespace Core;

interface CollectionInterface
{
    public function add($id, $item);

    public function set($items);

    public function get($id);

    public function toArray();
}