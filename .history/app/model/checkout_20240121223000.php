<?php

namespace App\model;

use PDO;
use PDOException;

class Checkout
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

    public function saveOrder($data)
    {

        $sql = "INSERT INTO ps_order VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(1, $data['email']);
        // bind other params

        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function saveOrderDetails($orderId, $items)
    {

        foreach ($items as $item) {

            $sql = "INSERT INTO ps_order_detail VALUES(NULL,?,?,?,?)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $orderId);
            $stmt->bindParam(2, $item['id']);
            $stmt->bindParam(3, $item['quantity']);
            $stmt->bindParam(4, $item['price']);

            $stmt->execute();
        }
    }
}
