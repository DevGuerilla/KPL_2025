<?php

class Flasher
{
    public static function setFlash($success, $data)
    {
        $_SESSION['flash'] = [
            'success' => $success,
            'data' => $data
        ];
    }

    public static function flash()
    {
        if (isset($_SESSION['flash'])) {

            unset($_SESSION['flash']);
        }
    }
}
