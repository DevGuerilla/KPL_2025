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
    $query = 'SELECT p.title, p.content, p.image, u.username, u.profile_picture_url, p.created_at, p.deleted_at
              FROM ' . $this->table . ' p
              JOIN user u
              ON p.id_user =  u.id_user';
    $this->db->query($query);
    return $this->db->resultSet();
  }

  public function getPostById($id)
  {
    $query = 'SELECT p.title, p.content, p.image, u.username, u.profile_picture_url
              FROM ' . $this->table . ' p
              JOIN user u
              ON p.id_user =  u.id_user
              WHERE p.id_post = :id';
    $this->db->query($query);
    $this->db->bind('id', $id);
    return $this->db->single();
  }
}
