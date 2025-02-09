<?php

class User_model
{
  private $table = 'user';
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllUser()
  {
    $query = 'SELECT u.username, u.name, u.email, u.profile_picture_url 
              FROM ' . $this->table . ' u';
    $this->db->query($query);
    return $this->db->resultSet();
  }

  public function getUserById($id)
  {
    $query = 'SELECT u.username, u.name, u.email, u.profile_picture_url 
              FROM ' . $this->table . ' u 
              WHERE u.id_user = :id';
    $this->db->query($query);
    $this->db->bind('id', $id);
    return $this->db->single();
  }
}
