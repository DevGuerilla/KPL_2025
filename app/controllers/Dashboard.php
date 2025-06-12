<?php

class Dashboard extends Controller
{
    private $userModel;
    private $postModel;
    private $tagModel;

    public function __construct()
    {
        if (!isset($_SESSION['isLoggedIn'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
        $this->userModel = $this->model('User_model');
        $this->postModel = $this->model('Post_model');
        $this->tagModel = $this->model('Tags_model');
    }

    private function validatePostField($data, $isEdit = false)
    {
        $errors = [];

        // diawali oleh isset lalu di check empty
        if (!isset($data['title']) || empty(trim($data['title']))) {
            $errors[] = 'Judul tidak boleh kosong.';
        }
        if (!isset($data['content']) || empty(trim($data['content']))) {
            $errors[] = 'Konten tidak boleh kosong.';
        }
        if (!isset($data['tags']) || empty(trim($data['tags']))) {
            $errors[] = 'Tag tidak boleh kosong.';
        }

        // user id
        if (!isset($data['id_user']) || empty(trim($data['id_user']))) {
            $errors[] = 'User ID tidak valid.';
        }

        // check apakah $error tidak kosong, jika tidak kosong, set flash message
        if (!empty($errors)) {
            Flasher::setFlash(false, ['message' => implode(' ', $errors)]);
            // reload halaman
            if ($isEdit) {
                header('Location: ' . BASEURL . '/dashboard/editpost/' . $data['id_post']);
            } else {
                header('Location: ' . BASEURL . '/dashboard/createpost');
            }
            exit;
        }
    }

    private function validateUpdateProfile($data)
    {
        $errors = [];

        if (!isset($data['name']) || empty(trim($data['name']))) {
            $errors[] = 'Nama tidak boleh kosong.';
        }
        if (!isset($data['email']) || empty(trim($data['email']))) {
            $errors[] = 'Email tidak boleh kosong.';
        }

        // jika password diisi , maka password lama dan konfirmasi password harus diisi
        if (!empty($data['password']) && (empty($data['old_password']) || empty($data['confirm_password']))) {
            $errors[] = 'Jika mengubah password, maka password lama dan konfirmasi password harus diisi.';
        }

        if (!empty($errors)) {
            Flasher::setFlash(false, ['message' => implode(' ', $errors)]);
            header('Location: ' . BASEURL . '/dashboard/profile');
            exit;
        }
    }

    public function index()
    {
        $data['posts'] = $this->postModel->getRecentPostByUserId($_SESSION['myProfile']['id_user']);
        $this->view('templates/header');
        $this->view('dashboard/index', $data);
        $this->view('templates/footer');
    }
    public function profile()
    {
        $data['posts'] = $this->postModel->getRecentPostByUserId($_SESSION['myProfile']['id_user']);
        $this->view('templates/header');
        $this->view('dashboard/editprofile', $data);
        $this->view('templates/footer');
    }


    // post profile update jika tidak ada image ataupun tidak ada password itu boleh di update, jjika ada update sesuai yang ada , jika image gaada pake yang dari datatbase, misal password gaakada, ambil yang dari database, image gaada ambiil yyang dari database usermodel
    public function doProfile()
    {
        if (!isset($_POST['csrf_token']) || !Helper::validateCSRFToken($_POST['csrf_token'])) {
            Flasher::setFlash(false, ['message' => 'Token keamanan tidak valid. Silakan muat ulang halaman.']);
            header('Location: ' . BASEURL . '/dashboard/profile');
            exit;
        }
        $data = $_POST;
        $user = $this->userModel->getUserById($_SESSION['myProfile']['id_user']);
        $data['id_user'] = $user['id_user'];

        // Jika dari popup password (change_password_only)
        if (isset($_POST['change_password_only'])) {
            if (empty($data['old_password']) || empty($data['password']) || empty($data['confirm_password'])) {
                Flasher::setFlash(false, ['message' => 'Semua field password harus diisi.']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }
            if (!password_verify($data['old_password'], $user['password'])) {
                Flasher::setFlash(false, ['message' => 'Password lama tidak sesuai.']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }
            if ($data['password'] !== $data['confirm_password']) {
                Flasher::setFlash(false, ['message' => 'Konfirmasi password tidak sesuai.']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }
            if (strlen($data['password']) < 6) {
                Flasher::setFlash(false, ['message' => 'Password baru minimal 6 karakter.']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // Update hanya password
            if ($this->userModel->updateProfile([
                'id_user' => $user['id_user'],
                'password' => $data['password'],
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'image' => $user['profile_picture_url']
            ]) > 0) {
                $user = $this->userModel->getUserById($user['id_user']);
                $_SESSION['myProfile'] = $user;
                Flasher::setFlash(true, ['message' => 'Password berhasil diubah!']);
            } else {
                Flasher::setFlash(false, ['message' => 'Password gagal diubah!']);
            }
            header('Location: ' . BASEURL . '/dashboard/profile');
            exit;
        }

        // jika tidak dari popup password, proses update profile biasa
        $this->validateUpdateProfile($data);

        if ($_FILES['image']['error'] === 4) {
            $data['image'] = $user['profile_picture_url'];
        } else {
            $data['image'] = UploadFile::upload($_FILES, 'image', 'users');
        }

        if (!empty($data['password'])) {
            if (!password_verify($data['old_password'], $user['password'])) {
                Flasher::setFlash(false, ['message' => 'Password lama tidak sesuai.']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }
            if ($data['password'] !== $data['confirm_password']) {
                Flasher::setFlash(false, ['message' => 'Konfirmasi password tidak sesuai.']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            $data['password'] = $user['password'];
        }

        if ($this->userModel->updateProfile($data) > 0) {
            $user = $this->userModel->getUserById($user['id_user']);
            $_SESSION['myProfile'] = $user;
            Flasher::setFlash(true, ['message' => 'Profile berhasil diubah!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Profile gagal diubah!']);
        }

        header('Location: ' . BASEURL . '/dashboard/profile');
    }

    public function posts()
    {
        $data = [
            'posts' => $this->postModel->getRecentPostByUserId($_SESSION['myProfile']['id_user']),
        ];
        $this->view('templates/header');
        $this->view('dashboard/posts', $data);
        $this->view('templates/footer');
    }

    public function createPost()
    {
        $this->view('templates/header');
        $this->view('dashboard/formpost');
        $this->view('templates/footer');
    }

    public function doCreatePost()
    {
        if (!isset($_POST['submit'])) {
            header('Location: ' . BASEURL . '/dashboard/createpost');
            exit;
        }
        if (!isset($_POST['csrf_token']) || !Helper::validateCSRFToken($_POST['csrf_token'])) {
            Flasher::setFlash(false, ['message' => 'Token keamanan tidak valid. Silakan muat ulang halaman.']);
            header('Location: ' . BASEURL . '/dashboard/createpost');
            exit;
        }

        $_POST['id_user'] = $_SESSION['myProfile']['id_user'];

        $this->validatePostField($_POST);

        if ($_FILES['image']['error'] === 4) {
            $_POST['image'] = 'default.jpg';
        } else {
            $_POST['image'] = UploadFile::upload($_FILES, 'image', 'posts');
        }

        if ($this->postModel->createPost($_POST) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil dibuat!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal dibuat!']);
        }

        header('Location: ' . BASEURL . '/dashboard/posts');
    }

    public function editpost(Int $id)
    {
        $data = $this->postModel->getPostTagsById($id);
        $this->view('templates/header');
        $this->view('dashboard/formpost', $data);
        $this->view('templates/footer');
    }

    public function doEditPost()
    {
        if (!isset($_POST['submit'])) {
            header('Location: ' . BASEURL . '/dashboard/posts');
            exit;
        }
        if (!isset($_POST['csrf_token']) || !Helper::validateCSRFToken($_POST['csrf_token'])) {
            Flasher::setFlash(false, ['message' => 'Token keamanan tidak valid. Silakan muat ulang halaman.']);
            header('Location: ' . BASEURL . '/dashboard/posts');
            exit;
        }

        $_POST['id_user'] = $_SESSION['myProfile']['id_user'];

        $this->validatePostField($_POST, true);

        if ($_FILES['image']['error'] === 4) {
            $_POST['image'] = $_POST['old_image'];
        } else {
            $_POST['image'] = UploadFile::upload($_FILES, 'image', 'posts');
        }

        if ($this->postModel->updatePost($_POST) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil diubah!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal diubah!']);
        }

        header('Location: ' . BASEURL . '/dashboard/posts');
    }

    public function deletepost()
    {
        if (!isset($_POST['csrf_token']) || !Helper::validateCSRFToken($_POST['csrf_token'])) {
            Flasher::setFlash(false, ['message' => 'Token keamanan tidak valid. Silakan muat ulang halaman.']);
            header('Location: ' . BASEURL . '/dashboard/posts');
            exit;
        }
        if ($this->postModel->deletePost($_POST['id']) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil dihapus!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal dihapus!']);
        }

        header('Location: ' . BASEURL . '/dashboard/posts');
    }
    public function recoverpost()
    {
        if (!isset($_POST['csrf_token']) || !Helper::validateCSRFToken($_POST['csrf_token'])) {
            Flasher::setFlash(false, ['message' => 'Token keamanan tidak valid. Silakan muat ulang halaman.']);
            header('Location: ' . BASEURL . '/dashboard/posts');
            exit;
        }
        if ($this->postModel->recoverPost($_POST['id']) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil direcover!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal direcover!']);
        }

        header('Location: ' . BASEURL . '/dashboard/posts');
    }
}
