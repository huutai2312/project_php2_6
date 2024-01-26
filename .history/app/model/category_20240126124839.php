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

    public function adminAddCategory($name, $slug)
    {
        $conn = $this->getConnection();
        $query = "INSERT INTO ps_category (name, slug, date_created) 
              VALUES (:name, :slug, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            // echo "Lỗi: " . $e->getMessage();
            throw $e;
        }
    }

    public function adminUpdateCategory($id, $name, $price, $quantity, $image, $shortDesc, $longDesc)
    {
        $conn = $this->getConnection();
        $query = "UPDATE ps_products 
              SET name = :name, price = :price, quantity = :quantity, image = :image, short_desc = :short_desc, long_desc = :long_desc
              WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':price', $price, PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindValue(':image', $image, PDO::PARAM_STR);
        $stmt->bindValue(':short_desc', $shortDesc, PDO::PARAM_STR);
        $stmt->bindValue(':long_desc', $longDesc, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            // echo "Lỗi: " . $e->getMessage();
            throw $e;
        }
    }
}
