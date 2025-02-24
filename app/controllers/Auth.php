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
        if (isset($_SESSION['isLoggedIn'])) {
            header('Location: ' . BASEURL . '/');
            exit;
        }
        $this->view('templates/header');
        $this->view('auth/login');
        $this->view('templates/footer');
    }

    public function doLogin()
    {
        if (isset($_SESSION['isLoggedIn'])) {
            header('Location: ' . BASEURL . '/');
            exit;
        }

        if (isset($_POST)) {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // sanitasi
                $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
                $password = trim($_POST['password']);

                if ($data = $this->userModel->getUserByUsername($username)) {
                    if (password_verify($password, $data['password'])) {
                        $_SESSION['isLoggedIn'] = true;
                        $_SESSION['myProfile'] = $data;
                        $_SESSION['csrf_token'] = Helper::generateCSRFToken();
                        Flasher::setFlash(true, ['message' => 'Selamat datang, ' . $data['username'] . '']);
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
        if (isset($_SESSION['isLoggedIn'])) {
            header('Location: ' . BASEURL . '/');
            exit;
        }

        $this->view('auth/register');
        $this->view('templates/header');
        $this->view('templates/footer');
    }

    public function doRegister()
    {
        if (isset($_SESSION['isLoggedIn'])) {
            header('Location: ' . BASEURL . '/');
            exit;
        }

        if (isset($_POST)) {
            if (isset($_POST['username']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
                // sanitasi
                $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
                $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
                $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
                $password = trim($_POST['password']);
                $confirm_password = trim($_POST['confirm_password']);

                // Validasi email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Flasher::setFlash(false, ['message' => 'Format email tidak valid!']);
                    header('Location: ' . BASEURL . '/auth/register');
                    exit;
                }

                if (!$this->userModel->getUserByUsername($username)) {
                    if ($password === $confirm_password) {
                        $data = [
                            'username' => $username,
                            'name' => $name,
                            'password' => $password,
                            'email' => $email,
                            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                            'profile_picture_url' => 'https://ui-avatars.com/api/?name=' . $username . '&background=random',
                        ];

                        if ($this->userModel->createUser($data) > 0) {
                            $data = [
                                'message' => 'Akun berhasil teregistrasi, silahkan login!'
                            ];
                            Flasher::setFlash(true, $data);
                            header('Location: ' . BASEURL . '/auth/login');
                            exit;
                        }
                    }
                    Flasher::setFlash(false, ['message' => 'Password tidak sama!']);
                    header('Location: ' . BASEURL . '/auth/register');
                    exit;
                }
                Flasher::setFlash(false, ['message' => 'Username sudah diambil!']);
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }
            $data = [
                'message' => 'Semua field wajib diisi!'
            ];
            Flasher::setFlash(false, $data);
            header('Location: ' . BASEURL . '/auth/register');
            exit;
        }
    }


    public function logout()
    {
        if (isset($_SESSION['isLoggedIn'])) {
            if (isset($_POST)) {
                session_destroy();
                session_reset();
            }
        }
        header('Location: ' . BASEURL . '/');
        exit;
    }
}
