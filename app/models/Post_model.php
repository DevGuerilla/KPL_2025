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
              ON p.id_user =  u.id_user
              WHERE p.deleted_at IS NULL';
    $this->db->query($query);
    return $this->db->resultSet();
  }

  public function getAllPostSoftDelete()
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
               WHERE p.deleted_at IS NULL
              ORDER BY RAND()
              LIMIT :limit';
    $this->db->query($query);
    $this->db->bind('limit', $limit);
    return $this->db->resultSet();
  }

  public function getPostById(Int $id)
  {
    $query = 'SELECT p.id_post,p.title, p.content, p.image, p.created_at, p.updated_at, u.username, u.name, u.profile_picture_url
              FROM ' . $this->table . ' p
              JOIN user u
              ON p.id_user =  u.id_user
              WHERE p.id_post = :id AND p.deleted_at IS NULL';
    $this->db->query($query);
    $this->db->bind('id', $id);
    return $this->db->single();
  }

  public function createPost($data)
  {
    $this->db->beginTransaction();

    $modelTag = new Tags_model;
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
      $data['tags'] = json_decode($data['tags'], true);
      foreach ($data['tags'] as $tag) {
        $modelTag->createTags($postId, $tag['value']);
      }

      $this->db->commit();
      return $this->db->rowCount();
    } catch (PDOException $e) {
      $this->db->rollBack();
      return 0;
    }
  }

  public function updatePost($data)
  {

    $this->db->beginTransaction();
    $modelTag = new Tags_model;

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

      $modelTag->deleteTags($data['id_post']);
      $data['tags'] = json_decode($data['tags'], true);
      foreach ($data['tags'] as $tag) {
        $modelTag->createTags($data['id_post'], $tag['value']);
      }

      $this->db->commit();
      return $this->db->rowCount();
    } catch (PDOException $e) {
      $this->db->rollBack();
      return 0;
    }
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

  public function recoverPost($id)
  {
    $query = 'UPDATE post
              SET deleted_at = null
              WHERE id_post = :id_post';
    $this->db->query($query);
    $this->db->bind('id_post', $id);

    $this->db->execute();
    return $this->db->rowCount();
  }

  public function getPostByKeywordAndTags(string $keyword)
  {
    $query = 'SELECT p.id_post, p.title, p.content, p.created_at, p.image, u.username, u.name, u.profile_picture_url
                FROM ' . $this->table . ' p
                JOIN user u ON p.id_user = u.id_user
                LEFT JOIN tags t ON p.id_post = t.id_post
                WHERE (p.title LIKE :keyword OR t.tag_name LIKE :keyword) AND p.deleted_at IS NULL';

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

  public function getPostTagsById(Int $id)
  {
    $tag = new Tags_model;
    $data = [
      'post' => $this->getPostById($id),
      'tags' => $tag->getAllTagsByPostId($id),
    ];

    return $data;
  }

  // get all post with they tags
  public function getAllPostTagsById()
  {
    $tag = new Tags_model;
    $query = 'SELECT p.id_post, p.title, p.content, p.image, u.username, u.name, u.profile_picture_url, p.created_at, p.deleted_at
              FROM ' . $this->table . ' p
              JOIN user u
              ON p.id_user =  u.id_user
              WHERE p.deleted_at IS NULL';
    $this->db->query($query);
    $posts = $this->db->resultSet();
    $data = [];
    foreach ($posts as $post) {
      $data[] = [
        'post' => $post,
        'tags' => $tag->getAllTagsByPostId($post['id_post']),
      ];
    }
    return $data;
  }


  // get recent top 3 post by id user
  public function getRecentPostByUserId(Int $id)
  {
    $query = 'SELECT p.id_post, p.title, p.content, p.image, u.username, u.name, u.profile_picture_url, p.created_at, p.deleted_at
              FROM ' . $this->table . ' p
              JOIN user u
              ON p.id_user =  u.id_user
              WHERE p.id_user = :id
              ORDER BY p.created_at DESC
              LIMIT 3';
    $this->db->query($query);
    $this->db->bind('id', $id);
    return $this->db->resultSet();
  }
}
