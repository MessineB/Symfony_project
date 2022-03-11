<?php

namespace App\Controller;

interface InterfaceController
{
    public function render($view_path);

    public function sendJson($data);
}