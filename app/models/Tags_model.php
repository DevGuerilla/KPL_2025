<?php 
class Tags_model
{
  private $table = 'tags';
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllTags(){
    $query = 'SELECT t.id_tag, t.tag_name
              FROM ' . $this->table . ' t';
    $this->db->query($query);
    return $this->db->resultSet();
  }
}