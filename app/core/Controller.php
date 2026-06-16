<?php

class Controller
{
    protected function view($view, array $data = [])
    {
        extract($data, EXTR_SKIP);

        require APP_ROOT . '/app/views/' . ltrim($view, '/') . '.php';
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
}
