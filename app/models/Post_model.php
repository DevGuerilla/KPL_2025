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
              JOIN user u
              ON p.id_user =  u.id_user';
    $this->db->query($query);
    return $this->db->resultSet();
  }

  public function getAllPostRandom(Int $limit)
  {
    $query = 'SELECT p.id_post, p.title, p.content, p.image, u.username, u.name, u.profile_picture_url, p.created_at, p.deleted_at
              FROM ' . $this->table . ' p
              JOIN user u
              ON p.id_user =  u.id_user
              ORDER BY RAND()
              LIMIT :limit';
    $this->db->query($query);
    $this->db->bind('limit', $limit);
    return $this->db->resultSet();
  }

  public function getPostById(Int $id)
  {
    $query = 'SELECT p.id_post,p.title, p.content, p.image, p.created_at, u.username, u.name, u.profile_picture_url
              FROM ' . $this->table . ' p
              JOIN user u
              ON p.id_user =  u.id_user
              WHERE p.id_post = :id';
    $this->db->query($query);
    $this->db->bind('id', $id);
    return $this->db->single();
  }

  public function getPostByKeywordAndTags(string $keyword)
  {
    $query = 'SELECT p.id_post, p.title, p.content, p.created_at, p.image, u.username, u.name, u.profile_picture_url
                FROM ' . $this->table . ' p
                JOIN user u ON p.id_user = u.id_user
                LEFT JOIN tags t ON p.id_post = t.id_post
                WHERE (p.title LIKE :keyword OR t.tag_name LIKE :keyword)';

    $this->db->query($query);
    $this->db->bind('keyword', '%' . $keyword . '%');
    return $this->db->resultSet();
  }


  public function getPostTagsCommentById(Int $id)
  {
    $tag = new Tags_model;
    $comment = new Comment_model;
    $data = [
      'post' => $this->getPostById($id),
      'tags' => $tag->getAllTagsByPostId($id),
      'comments' => $comment->getAllCommentByPostId($id),
      'randomPosts' => $this->getAllPostRandom(2),
    ];

    return $data;
  }
}
