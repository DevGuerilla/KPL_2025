<?php
class Auth extends Controller
{
    private $authModel;
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 300; // 5 minutes

    public function __construct()
    {
        $this->authModel = $this->model('Auth_Model');
        $this->initCSRF();
    }
    private function initCSRF()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

//    public function register()
//    {
//        $data['judul'] = 'Register';
//        $data['csrf_token'] = $_SESSION['csrf_token'];
//        $this->view('templates/header', $data);
//        $this->view('auth/register', $data);
//        $this->view('templates/footer');
//    }

    public function register()
    {
        $attemptKey = "attempt_register_" . $_SERVER['REMOTE_ADDR'];

        // Default values
        $showDelayWarning = false;

        if (isset($_SESSION[$attemptKey])) {
            $attemptCount = $_SESSION[$attemptKey]['count'];

            // Jika gagal kelipatan 3, beri delay 3 detik
            if ($attemptCount % 3 == 0 && $attemptCount > 0) {
                $showDelayWarning = true;
                sleep(3); // Tambahkan delay 3 detik
            }
        }

        $data['judul'] = 'Register';
        $data['csrf_token'] = $_SESSION['csrf_token'];
        $data['show_delay_warning'] = $showDelayWarning;

        $this->view('templates/header', $data);
        $this->view('auth/register', $data);
        $this->view('templates/footer');
    }




//    public function doRegister()
//    {
//        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//            http_response_code(405);
//            exit(json_encode(['status' => 'error', 'message' => 'Method not allowed']));
//        }
//
//        if (!$this->validateCSRF()) {
//            http_response_code(403);
//            exit(json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']));
//        }
//
//        try {
//            $data = $this->sanitizeInput([
//                'username' => $_POST['username'] ?? '',
//                'name' => $_POST['name'] ?? '',
//                'email' => $_POST['email'] ?? '',
//                'password' => $_POST['password'] ?? '',
//                'confirm_password' => $_POST['confirm_password'] ?? ''
//            ]);
//
//            $result = $this->authModel->register($data);
//
//            if (isset($result['error'])) {
//                http_response_code(400);
//                echo json_encode(['status' => 'error', 'message' => $result['error']]);
//            } else {
//                http_response_code(201);
//                echo json_encode(['status' => 'success', 'message' => 'Registration successful']);
//
//                echo "<script>setTimeout(function(){ window.location.href = '" . BASEURL . "/auth/login'; }, 2000);</script>";
//            }
//        } catch (Exception $e) {
//            error_log("Registration error: " . $e->getMessage());
//            http_response_code(500);
//            echo json_encode(['status' => 'error', 'message' => 'An error occurred during registration']);
//        }
//    }

    public function doRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Flasher::setFlash('Method not allowed!', 'Registration', 'danger');
            header("Location: " . BASEURL . "/auth/register");
            exit();
        }

        if (!$this->validateCSRF()) {
            Flasher::setFlash('Invalid CSRF token!', 'Registration', 'danger');
            header("Location: " . BASEURL . "/auth/register");
            exit();
        }

        // Simpan input sebelumnya ke session (kecuali password untuk keamanan)
        $_SESSION['old'] = [
            'username' => $_POST['username'] ?? '',
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
        ];

        $data = $this->sanitizeInput([
            'username' => $_POST['username'] ?? '',
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? ''
        ]);

        $result = $this->authModel->register($data);

        // Key untuk tracking percobaan register
        $attemptKey = "attempt_register_" . $_SERVER['REMOTE_ADDR'];

        // Jika gagal
        if (isset($result['error'])) {
            if (!isset($_SESSION[$attemptKey])) {
                $_SESSION[$attemptKey] = [
                    'count' => 0,
                    'first_attempt' => time()
                ];
            }

            $_SESSION[$attemptKey]['count']++;

            // **Tambahkan delay setelah setiap 3x gagal**
            if ($_SESSION[$attemptKey]['count'] % 3 == 0) {
                sleep(3); // Delay 3 detik
            }

            Flasher::setFlash($result['error'], 'Registration', 'danger');
            header("Location: " . BASEURL . "/auth/register");
            exit();
        }

        // Reset percobaan jika berhasil daftar
        unset($_SESSION[$attemptKey]);
        unset($_SESSION['old']); // Hapus old input

        Flasher::setFlash('Registration successful! Please login.', 'Registration', 'success');
        header("Location: " . BASEURL . "/auth/login");
        exit();
    }




//    public function login()
//    {
//        $data['judul'] = 'Login';
//        $data['csrf_token'] = $_SESSION['csrf_token'];
//        $data['is_locked'] = $this->isRateLimited('login');
//
//        $this->view('templates/header', $data);
//        $this->view('auth/login', $data);
//        $this->view('templates/footer');
//    }

    public function login()
    {
        $lockoutKey = "attempt_login_" . $_SERVER['REMOTE_ADDR'];
        $remainingTime = 0;
        $isLocked = false;

        if (isset($_SESSION[$lockoutKey]) && $_SESSION[$lockoutKey]['count'] >= self::MAX_LOGIN_ATTEMPTS) {
            $elapsedTime = time() - $_SESSION[$lockoutKey]['first_attempt'];
            if ($elapsedTime < self::LOCKOUT_TIME) {
                $remainingTime = self::LOCKOUT_TIME - $elapsedTime;
                $isLocked = true;
            } else {
                // Reset session jika lockout sudah selesai
                unset($_SESSION[$lockoutKey]);
            }
        }

        $data['judul'] = 'Login';
        $data['csrf_token'] = $_SESSION['csrf_token'];
        $data['is_locked'] = $isLocked;
        $data['remaining_time'] = $remainingTime; // Waktu tersisa dalam detik

        $this->view('templates/header', $data);
        $this->view('auth/login', $data);
        $this->view('templates/footer');
    }


//    public function doLogin()
//    {
//        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//            http_response_code(405);
//            exit(json_encode(['status' => 'error', 'message' => 'Method not allowed']));
//        }
//
//        if (!$this->validateCSRF()) {
//            http_response_code(403);
//            exit(json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']));
//        }
//
//        try {
//            $data = $this->sanitizeInput([
//                'email' => $_POST['email'] ?? '',
//                'password' => $_POST['password'] ?? ''
//            ]);
//
//            $result = $this->authModel->login($data);
//
//            if (isset($result['error'])) {
//                $this->incrementLoginAttempts();
//                http_response_code(401);
//                echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
//            } else {
//                $this->resetLoginAttempts();
//                $_SESSION['last_activity'] = time();
//                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
//                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
//
//                http_response_code(200);
//                echo json_encode(['status' => 'success', 'message' => 'Login successful']);
//
//
//                echo "<script>setTimeout(function(){ window.location.href = '" . BASEURL . "/home'; }, 2000);</script>";
//            }
//        } catch (Exception $e) {
//            error_log("Login error: " . $e->getMessage());
//            http_response_code(500);
//            echo json_encode(['status' => 'error', 'message' => 'An error occurred during login']);
//        }
//    }

    public function doLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Flasher::setFlash('Method not allowed!', 'Login', 'danger');
            header("Location: " . BASEURL . "/auth/login");
            exit();
        }

        if (!$this->validateCSRF()) {
            Flasher::setFlash('Invalid CSRF token!', 'Login', 'danger');
            header("Location: " . BASEURL . "/auth/login");
            exit();
        }

        try {
            $data = $this->sanitizeInput([
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? ''
            ]);

            $result = $this->authModel->login($data);

            if (isset($result['error'])) {
                $this->incrementLoginAttempts();
                Flasher::setFlash('Invalid email or password.', 'Login', 'danger');
                header("Location: " . BASEURL . "/auth/login");
            } else {
                $this->resetLoginAttempts();

                // Bersihkan data user sebelum disimpan ke session
                $cleanUser = $this->sanitizeUserData($result['user']);

                $_SESSION['user_id'] = $cleanUser['id_user'];
                $_SESSION['username'] = $cleanUser['username'];
                $_SESSION['email'] = $cleanUser['email'];

                Flasher::setFlash('Login successful! Welcome back', 'Login', 'success', $cleanUser['username']);
                header("Location: " . BASEURL . "/home");
            }
            exit();
        } catch (Exception $e) {
            Flasher::setFlash('An error occurred during login.', 'Login', 'danger');
            header("Location: " . BASEURL . "/auth/login");
            exit();
        }
    }





    public function logout()
    {
        try {
            $this->authModel->logout();
            Flasher::setFlash('Logged out successfully.', 'Logout', 'success');
            header("Location: " . BASEURL . "/auth/login");
            exit();
        } catch (Exception $e) {
//            error_log("Logout error: " . $e->getMessage());
            Flasher::setFlash('An error occurred during logout.', 'Logout', 'danger');
            header("Location: " . BASEURL . "/home");
            exit();
        }
    }


    private function validateCSRF(): bool
    {
        return isset($_POST['csrf_token']) &&
            hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }

    private function sanitizeInput(array $data): array
    {
        return array_map(function($item) {
            return htmlspecialchars(trim($item), ENT_QUOTES, 'UTF-8');
        }, $data);
    }

    private function sanitizeUserData(array $userData): array
    {
        unset($userData['password']); // Jangan pernah mengirim password ke client

        return array_map(function($item) {
            return is_null($item) ? '' : htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
        }, $userData);
    }

    private function isRateLimited(string $action): bool
    {
        $key = "attempt_{$action}_" . $_SERVER['REMOTE_ADDR'];

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'count' => 0,
                'first_attempt' => time()
            ];
        }

        // Jika sudah lewat dari lockout time, reset percobaan login
        if (time() - $_SESSION[$key]['first_attempt'] > self::LOCKOUT_TIME) {
            $_SESSION[$key]['count'] = 0;
            $_SESSION[$key]['first_attempt'] = time();
            return false;
        }

        return $_SESSION[$key]['count'] >= self::MAX_LOGIN_ATTEMPTS;
    }


    private function incrementLoginAttempts(): void
    {
        $key = "attempt_login_" . $_SERVER['REMOTE_ADDR'];
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'count' => 0,
                'first_attempt' => time()
            ];
        }
        $_SESSION[$key]['count']++;
    }

    private function resetLoginAttempts(): void
    {
        $key = "attempt_login_" . $_SERVER['REMOTE_ADDR'];
        unset($_SESSION[$key]);
    }
}