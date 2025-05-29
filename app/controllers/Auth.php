<?php

class Auth extends Controller
{
    private $userModel;
    private $authService;

    public function __construct()
    {
        $this->userModel = $this->model('User_model');
        $this->authService = new AuthService($this->userModel);
    }

    public function login()
    {
        if (SessionManager::isLoggedIn()) {
            Helper::redirect(BASEURL . '/');
        }

        $this->view('templates/header', ['judul' => 'Login']);
        $this->view('auth/login');
        $this->view('templates/footer');
    }

    public function doLogin()
    {
        if (SessionManager::isLoggedIn()) {
            Helper::redirect(BASEURL . '/');
        }

        try {
            $result = $this->authService->processLogin($_POST);
            $this->handleAuthResult($result);
        } catch (Exception $e) {
            Logger::error('Login controller exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Flasher::setFlash(false, ['message' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
            Helper::redirect(BASEURL . '/auth/login');
        }
    }

    public function register()
    {
        if (SessionManager::isLoggedIn()) {
            Helper::redirect(BASEURL . '/');
        }

        $this->view('templates/header', ['judul' => 'Register']);
        $this->view('auth/register');
        $this->view('templates/footer');
    }

    public function doRegister()
    {
        if (SessionManager::isLoggedIn()) {
            Helper::redirect(BASEURL . '/');
        }

        try {
            $result = $this->authService->processRegistration($_POST);
            $this->handleAuthResult($result);
        } catch (Exception $e) {
            Logger::error('Registration controller exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Flasher::setFlash(false, ['message' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
            Helper::redirect(BASEURL . '/auth/register');
        }
    }

    public function logout()
    {
        SessionManager::logout();
        Helper::redirect(BASEURL . '/');
    }

    /**
     * Handle authentication result from AuthService
     */
    private function handleAuthResult(array $result): void
    {
        Flasher::setFlash($result['success'], ['message' => $result['message']]);

        // If successful login, establish session
        if ($result['success'] && isset($result['user'])) {
            SessionManager::login($result['user']);
        }

        Helper::redirect($result['redirect']);
    }
}