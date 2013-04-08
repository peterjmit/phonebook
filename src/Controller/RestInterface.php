<?php

namespace Controller;

interface RestInterface
{
    public function get($id = null);

    public function update($id);

    public function delete($id);
}