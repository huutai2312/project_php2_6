<?php

namespace App\model;

use PDO;
use PDOException;

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = new database();
    }

    private function getConnection()
    {
        return $this->db->connection_database();
    }

    public function getAllCategories()
    {
        $conn = $this->getConnection();
        $query = "SELECT * FROM ps_category";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getAllCategoriesDesc()
    {
        $conn = $this->getConnection();
        $query = "SELECT * FROM ps_category ORDER BY id DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function adminAddCategory($name, $slug, $date_created)
    {
        $conn = $this->getConnection();
        $query = "INSERT INTO ps_category (name, slug, date_created) 
              VALUES (:name, :slug, :quantity, :image, :short_desc, :long_desc, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':short_desc', $shortDesc, PDO::PARAM_STR);
        $stmt->bindParam(':long_desc', $longDesc, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            // echo "Lỗi: " . $e->getMessage();
            throw $e;
        }
    }

}
