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
        $data['posts'] = $this->postModel->getAllPostTagsById();
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
            try {
                // Validate CSRF token ONLY for logged-in users
                if (isset($_SESSION['isLoggedIn'])) {
                    if (!isset($_POST['csrf_token']) || !Helper::validateCSRFToken($_POST['csrf_token'])) {
                        Flasher::setFlash(false, ['message' => 'Invalid security token']);
                        header('Location: ' . BASEURL . '/posts/detail/' . $id);
                        exit();
                    }
                }

                // Validate input data
                $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

                if (empty($comment)) {
                    Flasher::setFlash(false, ['message' => 'Komentar tidak boleh kosong!']);
                    header('Location: ' . BASEURL . '/posts/detail/' . $id);
                    exit();
                }

                // Handle username based on login status
                if (isset($_SESSION['isLoggedIn'])) {
                    // For logged-in users, use their username from session
                    $username = $_SESSION['myProfile']['username'];
                    $idUser = $_SESSION['myProfile']['id_user'];
                } else {
                    // For guests, use "Tamu" as in original design
                    $username = 'Tamu';
                    $idUser = null;
                }

                // Sanitize input
                $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
                $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

                // Add comment
                $this->commentModel->addComment($id, $idUser, $username, $comment);

                // Set success message
                Flasher::setFlash(true, ['message' => 'Komentar telah disubmit!']);

                // Log activity
                Logger::activity('Comment added', [
                    'post_id' => $id,
                    'username' => $username,
                    'is_guest' => is_null($idUser)
                ]);

            } catch (Exception $e) {
                // Log error
                Logger::error('Failed to add comment', [
                    'post_id' => $id,
                    'error' => $e->getMessage()
                ]);

                Flasher::setFlash(false, ['message' => 'Gagal menambahkan komentar. Silakan coba lagi.']);
            }

            // Redirect to prevent form resubmission
            header('Location: ' . BASEURL . '/posts/detail/' . $id);
            exit();
        }

        // head
        $this->view('templates/header', $data);
        $this->view('home/detailposts', $data);
        // footer
        $this->view('templates/footer');
    }

    public function search(String $keyword)
    {
        header('Content-Type: application/json');

        try {
            // Sanitize keyword
            $keyword = trim($keyword);
            if (empty($keyword)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Keyword cannot be empty'
                ]);
                exit;
            }

            // Limit keyword length
            if (strlen($keyword) > 100) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Keyword too long'
                ]);
                exit;
            }

            $results = $this->postModel->getPostByKeywordAndTags($keyword);

            echo json_encode([
                'status' => 'success',
                'data' => $results
            ]);

        } catch (Exception $e) {
            Logger::error('Search failed', [
                'keyword' => $keyword,
                'error' => $e->getMessage()
            ]);

            echo json_encode([
                'status' => 'error',
                'message' => 'Search failed'
            ]);
        }

        exit;
    }
}