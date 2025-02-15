<?php

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
    $query = 'SELECT p.id_post, p.title, p.content, p.image, u.username, u.profile_picture_url, p.created_at, p.deleted_at
              FROM ' . $this->table . ' p
              JOIN user u
              ON p.id_user =  u.id_user
              WHERE p.deleted_at IS NULL';
    $this->db->query($query);
    return $this->db->resultSet();
  }

  public function getPostById($id)
  {
    $query = 'SELECT p.id_post, p.title, p.content, p.image, u.username, u.profile_picture_url
              FROM ' . $this->table . ' p
              JOIN user u
              ON p.id_user =  u.id_user
              WHERE p.id_post = :id';
    $this->db->query($query);
    $this->db->bind('id', $id);
    return $this->db->single();
  }

  public function createPost($data)
  {
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
    return $this->db->rowCount();
  }

  public function updatePost($data)
  {
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
    return $this->db->rowCount();
  }

  public function deletePost($id)
  {
    $query = 'UPDATE post
              SET deleted_at = :deleted_at
              WHERE id_post = :id_post';
    $this->db->query($query);
    $this->db->bind('deleted_at', date("Y-m-d H:i:s"));
    $this->db->bind('id_post', $id);

    $this->db->execute();
    return $this->db->rowCount();
  }
}
