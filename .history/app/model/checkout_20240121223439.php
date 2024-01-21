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
    
    

}
