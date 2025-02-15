<?php

class Auth extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User_model');
    }

    public function login()
    {
        $this->view('templates/header');
        $this->view('auth/login');
        $this->view('templates/footer');
    }

    public function doLogin()
    {
        if (isset($_POST)) {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                if ($data = $this->userModel->getUserByUsername($_POST['username'])) {
                    if (password_verify($_POST['password'], $data['password'])) {
                        $_SESSION['isLogin'] = true;
                        $_SESSION['idUser'] = $data['id_user'];
                        header('Location: ' . BASEURL . '/');
                        exit;
                    }
                }
                $data = [
                    'message' => 'Username atau password salah!'
                ];
                Flasher::setFlash(false, $data);
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }
        }
        $data = [
            'message' => 'Username dan password wajib diisi!'
        ];
        Flasher::setFlash(false, $data);
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }


    public function register()
    {
        $this->view('auth/register');
        $this->view('templates/header');
        $this->view('templates/footer');
    }

    public function doRegister()
    {
        if (isset($_POST)) {
            if (isset($_POST['username']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                if ($this->userModel->createUser($_POST) > 0) {
                    $data = [
                        'message' => 'Akun berhasil teregistrasi, silahkan login!'
                    ];
                    Flasher::setFlash(true, $data);
                    header('Location: ' . BASEURL . '/auth/login');
                    exit;
                }
            }
        }
        $data = [
            'message' => 'Semua field wajib diisi!'
        ];
        Flasher::setFlash(false, $data);
        header('Location: ' . BASEURL . '/auth/register');
        exit;
    }
}
