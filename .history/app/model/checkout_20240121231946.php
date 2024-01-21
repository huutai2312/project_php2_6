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

    public function saveOrder($email, $first_name, $last_name, $company, $address, $phone, $city, $country, $postal_code, $payment)
    {
        try {
            $conn = $this->getConnection();
            $time = date("Y-m-d H:i:s"); // Lấy thời gian hiện tại
            $status = "pending"; // Bạn có thể thiết lập trạng thái khác tùy thuộc vào quy trình của bạn
            $payment

            // Thực hiện insert vào bảng ps_order
            $stmt = $conn->prepare("INSERT INTO ps_order (email, first_name, last_name, company, address, phone, city, country, postal_code, payment, status, time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$email, $first_name, $last_name, $company, $address, $phone, $city, $country, $postal_code, $payment $status, $time]);

            // Lấy ID đơn hàng vừa thêm vào để làm khóa ngoại trong bảng ps_order_detail
            $order_id = $conn->lastInsertId();

            // Lưu thông tin chi tiết đơn hàng từ localStorage vào bảng ps_order_detail
            $cartItems = $_SESSION['cart'] ?? [];
            foreach ($cartItems as $item) {
                $product_id = $item['id'];
                $quantity = $item['quantity'];
                $product_price = $item['price'];

                // Thực hiện insert vào bảng ps_order_detail
                $stmt = $conn->prepare("INSERT INTO ps_order_detail (order_id, product_id, quantity, product_price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $quantity, $product_price]);
            }

            // Xóa giỏ hàng sau khi đã đặt hàng thành công
            unset($_SESSION['cart']);

            // Đóng kết nối
            $conn = null;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
