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
    $query = 'SELECT * FROM ' . $this->table;
    $this->db->query($query);
    return $this->db->resultSet();
  }

  public function getUserByUsername($username)
  {
    $query = 'SELECT * FROM ' . $this->table . ' u WHERE u.username = :username';
    $this->db->query($query);
    $this->db->bind('username', $username);
    return $this->db->single();
  }

  public function createUser($data)
  {
    $query = 'INSERT INTO ' . $this->table . ' VALUES(null, :username, :name, :email, :password, :profile_picture_url, :created_at, :updated_at)';
    $this->db->query($query);
    $this->db->bind('username', $data['username']);
    $this->db->bind('name', $data['name']);
    $this->db->bind('email', $data['email']);
    $this->db->bind('password', $data['password']);
    $this->db->bind('profile_picture_url', $data['profile_picture_url']);
    $this->db->bind('created_at', date("Y-m-d H:i:s"));
    $this->db->bind('updated_at', date("Y-m-d H:i:s"));
    $this->db->execute();
    return $this->db->rowCount();
  }
}
