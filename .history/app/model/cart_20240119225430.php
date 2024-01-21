<?php

namespace App\model;

use PDO;
use PDOException;

class Cart
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

    public function addToCart($id_billcart, $id_user, $id_product, $name, $price, $quantity, $image, $total_price)
    {
        $conn = $this->getConnection();
        $query = "INSERT INTO ps_cart (id_billcart, id_user, id_product, name, price, quantity, image, total_price, date_created) 
              VALUES (:id_billcart, :id_user, :id_product, :name, :price, :quantity, :image, :total_price, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_billcart', $id_billcart, PDO::PARAM_STR);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':total_price', $total_price, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            // Xử lý lỗi nếu cần thiết
            throw $e;
        }
    }
}
