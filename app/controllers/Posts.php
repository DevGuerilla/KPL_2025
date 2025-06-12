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

        // Check if post exists
        if (!$data['post'] || !$data['post']['post']) {
            // Redirect to 404 or posts list
            header('Location: ' . BASEURL . '/posts');
            exit();
        }

        $data['judul'] = 'Posts: ' . $data['post']['post']['title'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Debug: Log what we received
                Logger::debug('Comment submission attempt', [
                    'post_data' => $_POST,
                    'session_exists' => isset($_SESSION['isLoggedIn']),
                    'user_id' => $_SESSION['myProfile']['id_user'] ?? 'guest'
                ]);

                // Validate CSRF token ONLY for logged-in users
                if (isset($_SESSION['isLoggedIn'])) {
                    if (!isset($_POST['csrf_token']) || !Helper::validateCSRFToken($_POST['csrf_token'])) {
                        Logger::warning('CSRF token validation failed', [
                            'post_id' => $id,
                            'user_id' => $_SESSION['myProfile']['id_user'] ?? 'unknown'
                        ]);
                        Flasher::setFlash(false, ['message' => 'Token keamanan tidak valid. Silakan muat ulang halaman.']);
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

                // Check comment length (show user-friendly message)
                if (strlen($comment) > 1000) {
                    Flasher::setFlash(false, ['message' => 'Komentar terlalu panjang! Maksimal 1000 karakter.']);
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
                $result = $this->commentModel->addComment($id, $idUser, $username, $comment);

                if ($result) {
                    // Set success message
                    Flasher::setFlash(true, ['message' => 'Komentar berhasil ditambahkan!']);

                    // Log activity
                    Logger::activity('Comment added successfully', [
                        'post_id' => $id,
                        'username' => $username,
                        'is_guest' => is_null($idUser),
                        'comment_length' => strlen($comment)
                    ]);
                } else {
                    throw new Exception('Failed to save comment to database');
                }

            } catch (Exception $e) {
                // Log error with more details
                Logger::error('Failed to add comment', [
                    'post_id' => $id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'user_id' => $_SESSION['myProfile']['id_user'] ?? 'guest',
                    'comment_length' => strlen($comment ?? '')
                ]);

                // Show user-friendly error message
                if (strpos($e->getMessage(), 'too long') !== false) {
                    Flasher::setFlash(false, ['message' => 'Komentar terlalu panjang! Maksimal 1000 karakter.']);
                } elseif (strpos($e->getMessage(), 'empty') !== false) {
                    Flasher::setFlash(false, ['message' => 'Komentar tidak boleh kosong!']);
                } else {
                    Flasher::setFlash(false, ['message' => 'Gagal menambahkan komentar. Silakan coba lagi.']);
                }
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

    // Debug method - remove in production
    public function testComment($postId)
    {
        if (!isset($_SESSION['isLoggedIn'])) {
            echo "Not logged in\n";
        } else {
            echo "User: " . $_SESSION['myProfile']['username'] . "\n";
            echo "User ID: " . $_SESSION['myProfile']['id_user'] . "\n";
        }

        echo "Post ID: " . $postId . "\n";
        echo "CSRF Token: " . Helper::generateCSRFToken() . "\n";

        exit;
    }
}