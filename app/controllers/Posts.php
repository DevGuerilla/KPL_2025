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

        $this->view('templates/header', $data);
        $this->view('home/posts', $data);
        $this->view('templates/footer');
    }

    public function detail(Int $id)
    {
        $data['post'] = $this->postModel->getPostTagsCommentById($id);

        if (!$data['post'] || !$data['post']['post']) {
            header('Location: ' . BASEURL . '/posts');
            exit();
        }

        $data['judul'] = 'Posts: ' . $data['post']['post']['title'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Logger::debug('Comment submission attempt', [
                    'post_data' => $_POST,
                    'session_exists' => isset($_SESSION['isLoggedIn']),
                    'user_id' => $_SESSION['myProfile']['id_user'] ?? 'guest'
                ]);

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

                $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

                if (empty($comment)) {
                    Flasher::setFlash(false, ['message' => 'Komentar tidak boleh kosong!']);
                    header('Location: ' . BASEURL . '/posts/detail/' . $id);
                    exit();
                }

                if (strlen($comment) > 1000) {
                    Flasher::setFlash(false, ['message' => 'Komentar terlalu panjang! Maksimal 1000 karakter.']);
                    header('Location: ' . BASEURL . '/posts/detail/' . $id);
                    exit();
                }

                if (isset($_SESSION['isLoggedIn'])) {
                    $username = $_SESSION['myProfile']['username'];
                    $idUser = $_SESSION['myProfile']['id_user'];
                } else {
                    $username = 'Tamu';
                    $idUser = null;
                }

                $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
                $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

                $result = $this->commentModel->addComment($id, $idUser, $username, $comment);

                if ($result) {
                    Flasher::setFlash(true, ['message' => 'Komentar berhasil ditambahkan!']);
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
                Logger::error('Failed to add comment', [
                    'post_id' => $id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'user_id' => $_SESSION['myProfile']['id_user'] ?? 'guest',
                    'comment_length' => strlen($comment ?? '')
                ]);

                if (strpos($e->getMessage(), 'too long') !== false) {
                    Flasher::setFlash(false, ['message' => 'Komentar terlalu panjang! Maksimal 1000 karakter.']);
                } elseif (strpos($e->getMessage(), 'empty') !== false) {
                    Flasher::setFlash(false, ['message' => 'Komentar tidak boleh kosong!']);
                } else {
                    Flasher::setFlash(false, ['message' => 'Gagal menambahkan komentar. Silakan coba lagi.']);
                }
            }

            header('Location: ' . BASEURL . '/posts/detail/' . $id);
            exit();
        }

        $this->view('templates/header', $data);
        $this->view('home/detailposts', $data);
        $this->view('templates/footer');
    }

    public function search($keyword = null)
    {
        header('Content-Type: application/json');

        try {
            // Jika tidak ada keyword
            if (!$keyword) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Keyword cannot be empty',
                    'data' => []
                ]);
                exit;
            }

            // Decode URL encoded keyword
            $keyword = urldecode($keyword);

            // Sanitize keyword
            $keyword = trim($keyword);
            if (empty($keyword)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Keyword cannot be empty',
                    'data' => []
                ]);
                exit;
            }

            // Limit keyword length
            if (strlen($keyword) > 100) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Keyword too long',
                    'data' => []
                ]);
                exit;
            }

            // Log search attempt
            Logger::debug('Search attempt', [
                'raw_keyword' => $keyword,
                'search_pattern' => '%' . $keyword . '%'
            ]);

            // Use database directly for more control
            $db = new Database();

            $query = 'SELECT DISTINCT 
                    p.id_post, 
                    p.title, 
                    p.content, 
                    p.created_at, 
                    p.image as thumbnail,
                    u.username, 
                    u.name, 
                    u.profile_picture_url,
                    GROUP_CONCAT(DISTINCT t.tag_name) as tags
                  FROM post p
                  JOIN user u ON p.id_user = u.id_user
                  LEFT JOIN tags t ON p.id_post = t.id_post
                  WHERE (
                    p.title LIKE :keyword1 OR 
                    p.content LIKE :keyword2 OR 
                    t.tag_name LIKE :keyword3
                  ) 
                  AND p.deleted_at IS NULL
                  GROUP BY p.id_post, p.title, p.content, p.created_at, p.image, u.username, u.name, u.profile_picture_url
                  ORDER BY p.created_at DESC
                  LIMIT 10';

            $db->query($query);
            $searchPattern = '%' . $keyword . '%';
            $db->bind('keyword1', $searchPattern);
            $db->bind('keyword2', $searchPattern);
            $db->bind('keyword3', $searchPattern);

            $results = $db->resultSet();

            // Format results untuk frontend
            $formattedResults = [];
            foreach ($results as $result) {
                $tags = [];
                if (!empty($result['tags'])) {
                    $tagNames = explode(',', $result['tags']);
                    $tags = array_map('trim', $tagNames);
                }

                $formattedResults[] = [
                    'id_post' => $result['id_post'],
                    'title' => $result['title'],
                    'content' => Helper::excerpt($result['content'], 150),
                    'thumbnail' => $result['thumbnail'],
                    'username' => $result['username'],
                    'name' => $result['name'],
                    'profile_picture_url' => $result['profile_picture_url'],
                    'created_at' => $result['created_at'],
                    'tags' => $tags
                ];
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Search completed',
                'data' => $formattedResults,
                'count' => count($formattedResults),
                'keyword' => $keyword
            ]);

            // Log search activity
            Logger::activity('Search performed', [
                'keyword' => $keyword,
                'results_count' => count($formattedResults)
            ]);

        } catch (Exception $e) {
            Logger::error('Search failed', [
                'keyword' => $keyword ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            echo json_encode([
                'status' => 'error',
                'message' => 'Search failed: ' . $e->getMessage(),
                'data' => []
            ]);
        }

        exit;
    }


}