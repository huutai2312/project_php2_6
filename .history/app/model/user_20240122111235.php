<?php
namespace App\model;
use PDO;
use PDOException;

class User
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

    public function getUserByEmail($email)
    {
        $conn = $this->getConnection();

        $query = "SELECT * FROM ps_user WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRoleAdmin($is_admin)
    {
        $conn = $this->getConnection();

        $query = "SELECT * FROM ps_user WHERE is_admin = :is_admin";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':is_admin', $is_admin, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registerUser($name, $email, $password, $date_created)
    {
        $conn = $this->getConnection();

        $query = "INSERT INTO ps_user (name, email, password, address, phone, is_admin, date_created) VALUES (:name, :email, :password, :address, :phone, :is_admin, :date_created)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':address', '', PDO::PARAM_STR);
        $stmt->bindValue(':phone', '0', PDO::PARAM_STR);
        $stmt->bindValue(':is_admin', '0', PDO::PARAM_STR);
        $stmt->bindParam(':date_created', $date_created, PDO::PARAM_STR);

        $stmt->execute();

        header("Location: /?registerSuccess=1"); 
        exit();
    }
    
    public function getAllUsersDesc()
    {
        $conn = $this->getConnection();
        $query = "SELECT * FROM ps_user ORDER BY id DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function adminUpdateUser($id, $name, $first_name, $last_name, $image, $shortDesc, $longDesc)
    {
        $conn = $this->getConnection();
        $query = "UPDATE ps_users 
              SET name = :name, first_name = :first_name, last_name = :last_name, image = :image, short_desc = :short_desc, long_desc = :long_desc
              WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $last_name, PDO::PARAM_INT);
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
