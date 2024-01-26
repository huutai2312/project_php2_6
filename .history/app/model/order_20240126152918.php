<?php

namespace App\model;

use PDO;
use PDOException;

class Order
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

    public function getAllOrders()
    {
        $conn = $this->getConnection();
        $query = "SELECT * FROM ps_order";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


}
