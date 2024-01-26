<?php

namespace App\model;

use PDO;
use PDOException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

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
            $time = date("d-m-Y H:i:s"); // Lấy thời gian hiện tại
            $status = "pending"; // Bạn có thể thiết lập trạng thái khác tùy thuộc vào quy trình của bạn

            // Thực hiện insert vào bảng ps_order
            $stmt = $conn->prepare("INSERT INTO ps_order (email, first_name, last_name, company, address, phone, city, country, postal_code, payment, status, time) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$email, $first_name, $last_name, $company, $address, $phone, $city, $country, $postal_code, $payment, $status, $time]);

            // Lấy ID đơn hàng vừa thêm vào để làm khóa ngoại trong bảng ps_order_detail
            $order_id = $conn->lastInsertId();

            // Lưu thông tin chi tiết đơn hàng từ localStorage vào bảng ps_order_detail
            $cartItems = $_SESSION['cart'] ?? [];
            if (is_array($cartItems)) {
                foreach ($cartItems as $item) {
                    $product_id = $item[0];
                    $quantity = $item[1];
                    $product_price = $item[3];

                    // Thực hiện insert vào bảng ps_order_detail
                    $stmt = $conn->prepare("INSERT INTO ps_order_detail (order_id, product_id, quantity, product_price) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$order_id, $product_id, $quantity, $product_price]);
                }
            } else {
                echo "Không lấy được key cart";
            }

            // Xóa giỏ hàng sau khi đã đặt hàng thành công
            unset($_SESSION['cart']);

            // Đóng kết nối
            $conn = null;

            header("Location: /tai-khoan");
            exit;

            // Send notification email
            $emailSubject = 'Order Confirmation';
            $emailTemplate = file_get_contents('path/to/email/template.html');
            $emailTemplate = str_replace('{{order_id}}', $order_id, $emailTemplate);
            // Replace other placeholders with corresponding order data

            $mailer = new PHPMailer();
            // Configure the mailer with your SMTP settings
            $mailer->isSMTP();
            $mailer->Host = 'your-smtp-host';
            $mailer->Port = 587;
            $mailer->SMTPAuth = true;
            $mailer->Username = 'your-smtp-username';
            $mailer->Password = 'your-smtp-password';

            $mailer->setFrom('your-email@example.com', 'Your Name');
            $mailer->addAddress($email, $first_name . ' ' . $last_name);
            $mailer->Subject = $emailSubject;
            $mailer->Body = $emailTemplate;
            $mailer->isHTML(true);

            if ($mailer->send()) {
                // Email sent successfully
                header("Location: /tai-khoan");
                exit;
            } else {
                // Failed to send email
                echo 'Failed to send email.';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
