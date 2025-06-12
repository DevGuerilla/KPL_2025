<?php

class Comment_model
{
    private $table = 'comment';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllComment()
    {
        $query = 'SELECT c.comment, c.username
                  FROM ' . $this->table . ' c';
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getAllCommentByPostId($id)
    {
        $query = 'SELECT c.comment, c.username, c.created_at
                  FROM ' . $this->table . ' c
                  WHERE id_post = :id
                  ORDER BY c.created_at DESC';
        $this->db->query($query);
        $this->db->bind('id', $id);
        return $this->db->resultSet();
    }

    public function addComment(Int $id, $user, String $username, String $comment)
    {
        try {
            // Validate input
            if (empty(trim($comment))) {
                throw new Exception('Comment cannot be empty');
            }

            if (empty(trim($username))) {
                throw new Exception('Username cannot be empty');
            }

            // Limit comment length
            if (strlen($comment) > 1000) {
                throw new Exception('Comment too long');
            }

            // Limit username length
            if (strlen($username) > 100) {
                throw new Exception('Username too long');
            }

            $query = 'INSERT INTO ' . $this->table . ' (id_post, id_user, username, comment, created_at, updated_at) 
                      VALUES (:id, :user, :username, :comment, :created_at, :updated_at)';
            $this->db->query($query);
            $this->db->bind('id', $id);
            $this->db->bind('user', $user); // This can be null for guests
            $this->db->bind('username', trim($username));
            $this->db->bind('comment', trim($comment));
            $this->db->bind('created_at', date("Y-m-d H:i:s"));
            $this->db->bind('updated_at', date("Y-m-d H:i:s"));

            $this->db->execute();

            Logger::activity('Comment added successfully', [
                'post_id' => $id,
                'username' => $username,
                'is_guest' => is_null($user)
            ]);

            return true;

        } catch (Exception $e) {
            Logger::error('Failed to add comment', [
                'post_id' => $id,
                'username' => $username,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function getCommentCount(Int $postId)
    {
        $query = 'SELECT COUNT(*) as count FROM ' . $this->table . ' WHERE id_post = :id';
        $this->db->query($query);
        $this->db->bind('id', $postId);
        $result = $this->db->single();
        return $result['count'] ?? 0;
    }

    public function deleteComment(Int $commentId, Int $userId = null)
    {
        try {
            // If user is provided, ensure they own the comment or are the post owner
            $query = 'DELETE FROM ' . $this->table . ' WHERE id_comment = :comment_id';

            if ($userId !== null) {
                // Allow deletion if user owns the comment or owns the post
                $query = 'DELETE c FROM ' . $this->table . ' c 
                         LEFT JOIN post p ON c.id_post = p.id_post
                         WHERE c.id_comment = :comment_id 
                         AND (c.id_user = :user_id OR p.id_user = :user_id)';
            }

            $this->db->query($query);
            $this->db->bind('comment_id', $commentId);

            if ($userId !== null) {
                $this->db->bind('user_id', $userId);
            }

            $this->db->execute();
            return $this->db->rowCount();

        } catch (Exception $e) {
            Logger::error('Failed to delete comment', [
                'comment_id' => $commentId,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}