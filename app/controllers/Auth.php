<?php

class Auth extends Controller
{
    public function register()
    {
        $data['judul'] = 'Register';
        // head
        $this->view('templates/header', $data);

        $this->view('auth/register', $data);
        // footer
        $this->view('templates/footer');
    }

    public function doRegister()
    {
        return var_dump($_POST);
    }

    public function login()
    {
        $data['judul'] = 'Login';
        // head
        $this->view('templates/header', $data);

        $this->view('auth/login', $data);
        // footer
        $this->view('templates/footer');
    }

    public function doLogin()
    {
        return var_dump($_POST);
    }
}
