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

  public function getAllCommentByPostId($id){
    $query = 'SELECT c.comment, c.username
              FROM ' . $this->table . ' c
              WHERE id_post = :id';
    $this->db->query($query);
    $this->db->bind('id', $id);
    return $this->db->resultSet();
  }
}
