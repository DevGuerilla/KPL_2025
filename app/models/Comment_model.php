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
  }

  public function getAllCommentByPostId($id)
  {
    $query = 'SELECT c.comment, c.username, c.created_at
              FROM ' . $this->table . ' c
              WHERE id_post = :id';
    $this->db->query($query);
    $this->db->bind('id', $id);
    return $this->db->resultSet();
  }

  public function addComment(Int $id, Int $user, String $username, String $comment)
  {
    $query = 'INSERT INTO ' . $this->table . ' (id_post, id_user, username, comment, created_at, updated_at) VALUES (:id, :user, :username, :comment, :created_at, :updated_at)';
    $this->db->query($query);
    $this->db->bind('id', $id);
    $this->db->bind('user', $user);
    $this->db->bind('username', $username);
    $this->db->bind('comment', $comment);
    $this->db->bind('created_at', date("Y-m-d H:i:s"));
    $this->db->bind('updated_at', date("Y-m-d H:i:s"));
    $this->db->execute();
  }
}
