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

    public function index()
    {
        $data['posts'] = $this->postModel->getRecentPostByUserId($_SESSION['myProfile']['id_user']);
        $this->view('templates/header');
        $this->view('dashboard/index', $data);
        $this->view('templates/footer');
    }


    private function validasiPost($data)
    {
        // pastikan semua field terisi
        if (empty($data['tags']) || empty($data['title']) || empty(trim($data['content']))) {
            Flasher::setFlash(false, ['message' => 'Pastikan semua field terisi!']);
            header('Location: ' . BASEURL . '/dashboard/editpost/' . $data['id_post']);
            exit;
        }
    }

    // validasi untuk user
    private function validasiUser($data)
    {
        // pastikan semua field terisi
        if (empty($data['username']) || empty($data['email']) || empty($data['name'])) {
            Flasher::setFlash(false, ['message' => 'Pastikan semua field terisi!']);
            header('Location: ' . BASEURL . '/dashboard/profile');
            exit;
        }

        // jika new password diisi maka pastikan old password dan confirm password juga diisi
        if (!empty($data['password']) || !empty($data['confirm_password'])) {
            if (empty($data['old_password']) || empty($data['confirm_password'])) {
                Flasher::setFlash(false, ['message' => 'Pastikan semua field password terisi!']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }
        }
    }

    private function xssSanitize($data)
    {
        // title
        if (isset($data['title'])) {
            $data['title'] = htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8'); // Allow some HTML tags
        }

        // tags
        if (isset($data['tags'])) {
            // If tags is already an array, sanitize it directly
            if (is_array($data['tags'])) {
                $data['tags'] = array_map(function ($tag) {
                    return is_string($tag) ? htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') : $tag;
                }, $data['tags']);
                // Convert to JSON string for storage
                $data['tags'] = json_encode($data['tags']);
            } else if (is_string($data['tags'])) {
                // Decode JSON string to array, sanitize, then encode back
                $tagsArray = json_decode($data['tags'], true);
                if (is_array($tagsArray)) {
                    $tagsArray = array_map(function ($tag) {
                        return is_string($tag) ? htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') : $tag;
                    }, $tagsArray);
                    $data['tags'] = json_encode($tagsArray);
                }
            }
        }
        // id user
        if (isset($data['id_user'])) {
            $data['id_user'] = htmlspecialchars($data['id_user'], ENT_QUOTES, 'UTF-8');
        }

        // sanitasi untuk user
        if (isset($data['username'])) {
            $data['username'] = htmlspecialchars($data['username'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($data['email'])) {
            $data['email'] = htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8');
        }

        if (isset($data['name'])) {
            $data['name'] = htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8');
        }

        return $data;
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
        $data = $_POST;
        $user = $this->userModel->getUserById($_SESSION['myProfile']['id_user']);
        $data['id_user'] = $user['id_user'];


        if ($_FILES['image']['error'] === 4) {
            $data['image'] = $user['profile_picture_url'];
        } else {
            $data['image'] = UploadFile::upload($_FILES, 'image', 'users');
        }

        $this->validasiUser($data);
        $data = $this->xssSanitize($data);

        if (empty($data['password'])) {
            $data['password'] = $user['password'];
        } else {
            //   cek apakah password sesuai dengan yang ada di database
            if (!password_verify($data['old_password'], $user['password'])) {
                Flasher::setFlash(false, ['message' => 'Password lama tidak sesuai!']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }

            // cceck apakah password baru sesuai dengan konfirmasi password
            if ($data['password'] !== $data['confirm_password']) {
                Flasher::setFlash(false, ['message' => 'Konfirmasi password tidak sesuai!']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }

            // jika password sesuai, hash password baru
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // cek apakah user mengubah username kalo iya, cek apakah username sudah terdaftar
        if ($data['username'] !== $user['username']) {
            if ($this->userModel->getUserByUsername($data['username'])) {
                Flasher::setFlash(false, ['message' => 'Username sudah terdaftar!']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }
        }

        // ccek apakah user mengubah email, kalo iya, cek apakah email sudah terdaftar
        if ($data['email'] !== $user['email']) {
            if ($this->userModel->isEmailExists($data['email'], $data['id_user'])) {
                Flasher::setFlash(false, ['message' => 'Email sudah terdaftar!']);
                header('Location: ' . BASEURL . '/dashboard/profile');
                exit;
            }
        }

        if ($this->userModel->updateProfile($data) > 0) {
            $user = $this->userModel->getUserById($user['id_user']);
            // Helper::dd($user);
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

        $_POST['id_user'] = $_SESSION['myProfile']['id_user'];

        if ($_FILES['image']['error'] === 4) {
            $_POST['image'] = 'default.png';
        } else {
            $_POST['image'] = UploadFile::upload($_FILES, 'image', 'posts');
        }

        // pastikan semua field terisi
        $this->validasiPost($_POST);
        $data = $this->xssSanitize($_POST);

        if ($this->postModel->createPost($data) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil dibuat!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal dibuat!']);
        }

        header('Location: ' . BASEURL . '/dashboard/posts');
    }

    public function editpost($id = null)
    {
        // Validasi apakah ID ada dan valid
        if ($id === null || !is_numeric($id)) {
            Flasher::setFlash(false, ['message' => 'ID post tidak valid!']);
            header('Location: ' . BASEURL . '/dashboard/posts');
            exit;
        }

        $data = $this->postModel->getPostTagsById($id);

        // Validasi apakah post ditemukan
        if (!$data || empty($data['post'])) {
            Flasher::setFlash(false, ['message' => 'Post tidak ditemukan!']);
            header('Location: ' . BASEURL . '/dashboard/posts');
            exit;
        }

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

        $_POST['id_user'] = $_SESSION['myProfile']['id_user'];

        if ($_FILES['image']['error'] === 4) {
            $_POST['image'] = $_POST['old_image'];
        } else {
            $_POST['image'] = UploadFile::upload($_FILES, 'image', 'posts');
        }


        $this->validasiPost($_POST);
        $data = $this->xssSanitize($_POST);

        if ($this->postModel->updatePost($data) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil diubah!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal diubah!']);
        }

        header('Location: ' . BASEURL . '/dashboard/posts');
    }

    public function deletepost()
    {
        if ($this->postModel->deletePost($_POST['id']) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil dihapus!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal dihapus!']);
        }

        header('Location: ' . BASEURL . '/dashboard/posts');
    }
    public function recoverpost()
    {
        if ($this->postModel->recoverPost($_POST['id']) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil direcover!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal direcover!']);
        }

        header('Location: ' . BASEURL . '/dashboard/posts');
    }
}
