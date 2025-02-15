<?php

class Posts extends Controller
{

    private $postModel;
    private $commentModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post_model');
        $this->commentModel = $this->model('Comment_model');
    }

    public function index()
    {
        $data['judul'] = 'Posts';
        $data['posts'] = $this->postModel->getAllPost();
        // head
        $this->view('templates/header', $data);

        $this->view('home/posts', $data);
        // footer
        $this->view('templates/footer');
    }
    public function detail(Int $id)
    {
        $data['post'] = $this->postModel->getPostTagsCommentById($id);
        $data['judul'] = 'Posts: ' . $data['post']['post']['title'];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['comment']) && isset($_POST['username'])) {
                $comment = htmlspecialchars(trim($_POST['comment']), ENT_QUOTES, 'UTF-8');
                $idUser = htmlspecialchars($_SESSION['myProfile']['id_user'], ENT_QUOTES, 'UTF-8');
                $username = isset($_POST['isLoggedIn']) ? htmlspecialchars($_SESSION['myProfile']['username']) : htmlspecialchars($_POST['username']);
                $this->commentModel->addComment($data['post']['post']['id_post'], $idUser, $username, $comment);
                Flasher::setFlash(true, ['message' => 'Komentar telah disubmit!']);
                header('Location: ' . BASEURL . '/posts/detail/' . $data['post']['post']['id_post']);
                exit;
            } else {
                Flasher::setFlash(false, ['message' => 'Semua field wajib diisi!']);
            }
        }

        // head
        $this->view('templates/header', $data);

        $this->view('home/detailposts', $data);
        // footer
        $this->view('templates/footer');
    }

    public function search(String $keyword)
    {
        var_dump($this->postModel->getPostByKeywordAndTags($keyword));
    }
}
