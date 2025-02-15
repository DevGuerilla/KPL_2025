<?php

class Dashboard extends Controller
{
    private $userModel;
    private $postModel;

    public function __construct()
    {
        $this->userModel = $this->model('User_model');
        $this->postModel = $this->model('Post_model');
    }

    public function index()
    {
        $this->view('templates/header');
        $this->view('dashboard/index');
        $this->view('templates/footer');
        
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

        if($this->postModel->createPost($_POST) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil dibuat!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal dibuat!']);
        }
        
        header('Location: ' . BASEURL . '/dashboard/posts');
    }

    public function editpost($id)
    {
        $data = $this->postModel->getPostById($id);

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

        if($this->postModel->updatePost($_POST) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil diubah!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal diubah!']);
        }
        
        header('Location: ' . BASEURL . '/dashboard/posts');
    }

    public function deletepost()
    {
        if($this->postModel->deletePost($_POST['id']) > 0) {
            Flasher::setFlash(true, ['message' => 'Post berhasil dihapus!']);
        } else {
            Flasher::setFlash(false, ['message' => 'Post gagal dihapus!']);
        }
        
        header('Location: ' . BASEURL . '/dashboard/posts');
    }
}
