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
    
    public function checkCart($id){
        session_start();
        for ($i=0; $i < sizeof($_SESSION['cart']); $i++) { 
            if ($_SESSION['cart'][$i][0] == $id[0]) {
                return $i;
            }
        }
        return -1;
    }
sản phẩm
}
