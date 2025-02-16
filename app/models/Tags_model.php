<?php
class Tags_model
{
  private $table = 'tags';
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllTags()
  {
    $query = 'SELECT t.id_tag, t.tag_name
              FROM ' . $this->table . ' t';
    $this->db->query($query);
    return $this->db->resultSet();
  }

  public function getAllTagsByPostId($id)
  {
    $query = 'SELECT t.tag_name
              FROM ' . $this->table . ' t
              WHERE id_post = :id';
    $this->db->query($query);
    $this->db->bind('id', $id);
    return $this->db->resultSet();
  }

  // delete all tags with post iii
  public function deleteTags(Int $id)
  {
    $query = 'DELETE FROM ' . $this->table . ' WHERE id_post = :id';
    $this->db->query($query);
    $this->db->bind('id', $id);
    $this->db->execute();
  }

  // creata tags by post id with bind one tag
  public function createTags(Int $id, String $tag)
  {
    $query = 'INSERT INTO ' . $this->table . ' (id_post, tag_name) VALUES (:id, :tag)';
    $this->db->query($query);
    $this->db->bind('id', $id);
    $this->db->bind('tag', $tag);
    $this->db->execute();
  }
}
