<?php

require_once 'Tags_model.php';
require_once 'Comment_model.php';

class Post_model
{
    private $table = 'post';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllPost()
    {
        $query = 'SELECT p.id_post, p.title, p.content, p.image, u.username, u.name, u.profile_picture_url, p.created_at, p.deleted_at
                  FROM ' . $this->table . ' p
                  JOIN user u ON p.id_user = u.id_user
                  WHERE p.deleted_at IS NULL
                  ORDER BY p.created_at DESC';
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getAllPostSoftDelete()
    {
        $query = 'SELECT p.id_post, p.title, p.content, p.image, u.username, u.name, u.profile_picture_url, p.created_at, p.deleted_at
                  FROM ' . $this->table . ' p
                  JOIN user u ON p.id_user = u.id_user
                  ORDER BY p.created_at DESC';
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getAllPostRandom(Int $limit)
    {
        $query = 'SELECT p.id_post, p.title, p.content, p.image, u.username, u.name, u.profile_picture_url, p.created_at, p.deleted_at
                  FROM ' . $this->table . ' p
                  JOIN user u ON p.id_user = u.id_user
                  WHERE p.deleted_at IS NULL
                  ORDER BY RAND()
                  LIMIT :limit';
        $this->db->query($query);
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }

    public function getPostById(Int $id)
    {
        $query = 'SELECT p.id_post, p.title, p.content, p.image, p.created_at, p.updated_at, u.username, u.name, u.profile_picture_url
                  FROM ' . $this->table . ' p
                  JOIN user u ON p.id_user = u.id_user
                  WHERE p.id_post = :id AND p.deleted_at IS NULL';
        $this->db->query($query);
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function createPost($data)
    {
        $this->db->beginTransaction();

        try {
            $query = 'INSERT INTO post (title, content, image, id_user, created_at, updated_at)
                      VALUES (:title, :content, :image, :id_user, :created_at, :updated_at)';
            $this->db->query($query);
            $this->db->bind('title', $data['title']);
            $this->db->bind('content', $data['content']);
            $this->db->bind('image', $data['image']);
            $this->db->bind('id_user', $data['id_user']);
            $this->db->bind('created_at', date("Y-m-d H:i:s"));
            $this->db->bind('updated_at', date("Y-m-d H:i:s"));

            $this->db->execute();
            $postId = $this->db->lastInsertId();

            // Handle tags - OPTIMIZED
            if (isset($data['tags']) && !empty($data['tags'])) {
                $this->createPostTags($postId, $data['tags']);
            }

            $this->db->commit();
            return $postId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function createPostTags($postId, $tagsJson)
    {
        $tags = json_decode($tagsJson, true);
        if (!is_array($tags)) return;

        // BULK INSERT - Much faster than individual inserts
        $values = [];
        $now = date("Y-m-d H:i:s");

        foreach ($tags as $index => $tag) {
            if (isset($tag['value']) && !empty(trim($tag['value']))) {
                $values[] = "(:post_id_$index, :tag_name_$index, :created_at_$index, :updated_at_$index)";
            }
        }

        if (!empty($values)) {
            $query = "INSERT INTO tags (id_post, tag_name, created_at, updated_at) VALUES " . implode(', ', $values);
            $this->db->query($query);

            $bindIndex = 0;
            foreach ($tags as $index => $tag) {
                if (isset($tag['value']) && !empty(trim($tag['value']))) {
                    $this->db->bind("post_id_$index", $postId);
                    $this->db->bind("tag_name_$index", trim($tag['value']));
                    $this->db->bind("created_at_$index", $now);
                    $this->db->bind("updated_at_$index", $now);
                }
            }

            $this->db->execute();
        }
    }

    public function updatePost($data)
    {
        $this->db->beginTransaction();

        try {
            $query = 'UPDATE post
                      SET title = :title, content = :content, image = :image, updated_at = :updated_at
                      WHERE id_post = :id_post';
            $this->db->query($query);
            $this->db->bind('title', $data['title']);
            $this->db->bind('content', $data['content']);
            $this->db->bind('image', $data['image']);
            $this->db->bind('updated_at', date("Y-m-d H:i:s"));
            $this->db->bind('id_post', $data['id_post']);

            $this->db->execute();

            // Delete and recreate tags
            $this->deleteTags($data['id_post']);

            if (isset($data['tags']) && !empty($data['tags'])) {
                $this->createPostTags($data['id_post'], $data['tags']);
            }

            $this->db->commit();
            return $this->db->rowCount();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function deleteTags($postId)
    {
        $query = 'DELETE FROM tags WHERE id_post = :id';
        $this->db->query($query);
        $this->db->bind('id', $postId);
        $this->db->execute();
    }

    public function deletePost($id)
    {
        $query = 'UPDATE post SET deleted_at = :deleted_at WHERE id_post = :id_post';
        $this->db->query($query);
        $this->db->bind('deleted_at', date("Y-m-d H:i:s"));
        $this->db->bind('id_post', $id);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function recoverPost($id)
    {
        $query = 'UPDATE post SET deleted_at = null WHERE id_post = :id_post';
        $this->db->query($query);
        $this->db->bind('id_post', $id);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function getPostByKeywordAndTags(string $keyword)
    {
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
              FROM ' . $this->table . ' p
              JOIN user u ON p.id_user = u.id_user
              LEFT JOIN tags t ON p.id_post = t.id_post
              WHERE (p.title LIKE :keyword OR p.content LIKE :keyword OR t.tag_name LIKE :keyword) 
              AND p.deleted_at IS NULL
              GROUP BY p.id_post, p.title, p.content, p.created_at, p.image, u.username, u.name, u.profile_picture_url
              ORDER BY p.created_at DESC';

        $this->db->query($query);
        $this->db->bind('keyword', '%' . $keyword . '%');
        $results = $this->db->resultSet();

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
                'content' => Helper::excerpt($result['content'], 150), // Excerpt untuk preview
                'thumbnail' => $result['thumbnail'],
                'username' => $result['username'],
                'name' => $result['name'],
                'profile_picture_url' => $result['profile_picture_url'],
                'created_at' => $result['created_at'],
                'tags' => $tags
            ];
        }

        return $formattedResults;
    }

    public function getPostTagsCommentById(Int $id)
    {
        // Single query for post
        $post = $this->getPostById($id);
        if (!$post) return null;

        // Single query for tags
        $tagsQuery = 'SELECT tag_name FROM tags WHERE id_post = :id';
        $this->db->query($tagsQuery);
        $this->db->bind('id', $id);
        $tags = $this->db->resultSet();

        // Single query for comments
        $commentsQuery = 'SELECT comment, username, created_at FROM comment WHERE id_post = :id ORDER BY created_at DESC';
        $this->db->query($commentsQuery);
        $this->db->bind('id', $id);
        $comments = $this->db->resultSet();

        // Random posts
        $randomPosts = $this->getAllPostRandom(2);

        return [
            'post' => $post,
            'tags' => $tags,
            'comments' => $comments,
            'randomPosts' => $randomPosts,
        ];
    }

    public function getPostTagsById(Int $id)
    {
        $post = $this->getPostById($id);
        if (!$post) return null;

        $tagsQuery = 'SELECT tag_name FROM tags WHERE id_post = :id';
        $this->db->query($tagsQuery);
        $this->db->bind('id', $id);
        $tags = $this->db->resultSet();

        return [
            'post' => $post,
            'tags' => $tags,
        ];
    }

    public function getAllPostTagsById()
    {
        $query = 'SELECT 
                p.id_post, p.title, p.content, p.image, p.created_at, p.deleted_at,
                u.username, u.name, u.profile_picture_url,
                GROUP_CONCAT(t.tag_name) as tags
              FROM ' . $this->table . ' p
              JOIN user u ON p.id_user = u.id_user
              LEFT JOIN tags t ON p.id_post = t.id_post
              WHERE p.deleted_at IS NULL
              GROUP BY p.id_post, u.username, u.name, u.profile_picture_url
              ORDER BY p.created_at DESC';

        $this->db->query($query);
        $posts = $this->db->resultSet();

        $data = [];
        foreach ($posts as $post) {
            $tags = [];
            if (!empty($post['tags'])) {
                $tagNames = explode(',', $post['tags']);
                foreach ($tagNames as $tagName) {
                    $tags[] = ['tag_name' => trim($tagName)];
                }
            }

            unset($post['tags']); // Remove the concatenated tags

            $data[] = [
                'post' => $post,
                'tags' => $tags,
            ];
        }

        return $data;
    }

    public function getRecentPostByUserId(Int $id)
    {
        $query = 'SELECT p.id_post, p.title, p.content, p.image, u.username, u.name, u.profile_picture_url, p.created_at, p.deleted_at
                  FROM ' . $this->table . ' p
                  JOIN user u ON p.id_user = u.id_user
                  WHERE p.id_user = :id
                  ORDER BY p.created_at DESC';
        $this->db->query($query);
        $this->db->bind('id', $id);
        return $this->db->resultSet();
    }
}
