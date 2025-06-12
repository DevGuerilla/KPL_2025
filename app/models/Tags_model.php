<?php
class Tags_model
{
    private $table = 'tags'; // Changed from 'Tags' to 'tags' to match database
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

    // delete all tags with post id
    public function deleteTags(Int $id)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id_post = :id';
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
    }

    // create tags by post id with bind one tag
    public function createTags(Int $id, String $tag)
    {
        try {
            // Sanitize the tag name - remove extra spaces and trim
            $cleanTag = trim(preg_replace('/\s+/', ' ', $tag));

            // Skip empty tags
            if (empty($cleanTag)) {
                return false;
            }

            $query = 'INSERT INTO ' . $this->table . ' (id_post, tag_name, created_at, updated_at) 
                VALUES (:id, :tag, :created_at, :updated_at)';
            $this->db->query($query);
            $this->db->bind('id', $id);
            $this->db->bind('tag', $cleanTag);
            $this->db->bind('created_at', date("Y-m-d H:i:s"));
            $this->db->bind('updated_at', date("Y-m-d H:i:s"));

            $this->db->execute();
            return true;

        } catch (Exception $e) {
            // Log the error for debugging
            Logger::error('Failed to create tag', [
                'post_id' => $id,
                'tag' => $tag,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}