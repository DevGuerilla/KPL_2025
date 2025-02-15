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

    public function editpost($id)
    {
        $data = $this->postModel->getPostById($id);

        $this->view('templates/header');
        $this->view('dashboard/createpost', $data);
        $this->view('templates/footer');
    }
}
