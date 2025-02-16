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

        if (empty($data['password'])) {
            $data['password'] = $user['password'];
        } else {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
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
            'posts' => $this->postModel->getAllPost()
        ];
        $this->view('templates/header');
        $this->view('dashboard/posts', $data);
        $this->view('templates/footer');
    }

    public function createPost()
    {
        $this->view('templates/header');
        $this->view('dashboard/createpost');
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
        $this->view('dashboard/createpost', $data);
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

        if ($this->postModel->updatePost($_POST) > 0) {
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
}
